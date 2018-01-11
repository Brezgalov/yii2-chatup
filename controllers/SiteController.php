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
                'only' => ['logout', 'landing', 'index', 'login', 'register', 'chatup', 'chat'],
                'rules' => [
                    [
                        'actions' => ['logout', 'landing', 'chatup', 'chat'],
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index', 'login', 'register'],
                        'allow' => true,
                        'roles' => ['?']
                    ],
                    [
                        'actions' => ['logout', 'landing', 'chatup', 'chat'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index', 'login', 'register'],
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
        return $this->actionLogin();
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        //throw new NotFoundHttpException("Something unexpected happened");
        $model = new LoginForm();


        if (
            !Yii::$app->user->isGuest || 
            $model->load(Yii::$app->request->post()) && 
            $model->login()
        ) {
            return $this->redirect(['site/landing']);
        }
        
        return $this->render(
            'index', 
            [
                'model' => $model,
            ]
        );
    }

    /**
     * Displays register form.
     *
     * @return string
     */
    public function actionRegister()
    {   
        $model = new RegisterForm();

        if (
            Yii::$app->user->isGuest &&
            $model->load(Yii::$app->request->post()) && 
            $model->register()
        ) {
            return $this->redirect(['site/landing']);
        }

        return $this->render(
            'register',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * Display Landing Hub
     */
    public function actionLanding() {
        return $this->render('landing');
    }

    /**
     * Logout action
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['/']);
    }

    public function actionChatup() 
    {
        $currentUser = Yii::$app->user->getIdentity();
        if (!$currentUser) {
            return $this->redirect(['/']);
        }
        $model = new CreateChatForm();
        if ($model->load(Yii::$app->request->post())) {
            $chat = new Chat();
            $chat->name = $model->name;
            $chat->save();

            $saves = [];
            foreach ($model->users as $uid) {
                $userChat = new UserChats();
                $userChat->user_id = $uid;
                $userChat->chat_id = $chat->id;
                $userChat->save();
            }

            $userChatSelf = new UserChats();
            $userChatSelf->user_id = $currentUser->id;
            $userChatSelf->chat_id = $chat->id;
            $userChatSelf->save();
            return $this->redirect(['site/chat', 'id' => $chat->id]);
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
        if (!$chat || !$chat->hasUser(Yii::$app->user->id)) {
            return $this->redirect(['/']);
        }
        $users = $chat->getUsers();
        $messages = $chat->getMessages();
        $model = null;//add message
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

    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            return $this->render('error', ['exception' => $exception]);
        }
    }
}
