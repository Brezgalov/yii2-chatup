<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\RegisterForm;
use app\models\CreateChatForm;
use app\models\SendMessageForm;
use app\models\User;
use app\models\UserChats;
use app\models\Chat;
use yii\base\UserException;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [
                    'logout', 
                    'landing', 
                    'index', 
                    'login', 
                    'register', 
                    'chatup', 
                    'chat',
                    'new-message'
                ],
                'rules' => [
                    [
                        'actions' => ['landing', 'chatup', 'chat', 'new-message'],
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['?']
                    ],
                    [
                        'actions' => ['landing', 'chatup', 'chat', 'new-message'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => false,
                        'roles' => ['@'],
                        'denyCallback' => function ($rule, $action) {
                            return $action->controller->redirect(['site/landing']);
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'chatup' => ['post'],
                    'new-message' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->redirect(['user/login']);
    }

    /**
     * Display Landing Hub
     */
    public function actionLanding() {
        return $this->render('landing');
    }

    public function actionChatup() 
    {
        $currentUser = Yii::$app->user->getIdentity();
        if (!$currentUser) {
            return $this->redirect(['/']);
        }
        $chat = new CreateChatForm();
        if ($chat->load(Yii::$app->request->post())) {
            $chatId = $chat->create();         
            return $this->redirect(['site/chat', 'id' => $chatId]);
        } else {
            return $this->redirect(['/']);
        }
    }

    public function actionChat() {
        $data = Yii::$app->request->get();
        if (!isset($data['id'])) {
            return $this->redirect(['/']);
        }

        $chat = Chat::findOne($data['id']);
        $userId = Yii::$app->user->id;
        if (!$chat || !$chat->hasUser($userId)) {
            return $this->redirect(['/']);
        }

        $model = new SendMessageForm();
        $model->user_id = $userId;
        $model->chat_id = $chat->id;
        $users = $chat->getUsers();
        $messages = $chat->getMessages();
        
        return $this->render(
            'chat',
            [
                'name' => $chat->name,
                'users' => $users,
                'messages' => $messages,
                'model' => $model,
            ]
        );
    }

    public function actionNewMessage()
    {
        $model = new SendMessageForm();
        if ($model->load(Yii::$app->request->post())) {
            $model->send();
        }        
        return $this->redirect(['site/chat', 'id' => $model->chat_id]);
    }

    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            return $this->render('error', ['exception' => $exception]);
        }
    }
}
