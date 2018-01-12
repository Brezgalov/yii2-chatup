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

class UserController extends Controller
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
                    'login', 
                    'register', 
                ],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['login', 'register'],
                        'allow' => true,
                        'roles' => ['?']
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['login', 'register'],
                        'allow' => false,
                        'roles' => ['@'],
                        'denyCallback' => function ($rule, $action) {
                            return $action->controller->redirect(['site/landing']);
                        },
                    ],
                ],
            ],
            // 'verbs' => [
            //     'class' => VerbFilter::className(),
            //     'actions' => [
            //         'chatup' => ['post'],
            //         'new-message' => ['post'],
            //     ],
            // ],
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
     * Logout action
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['/']);
    }

    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            return $this->render('error', ['exception' => $exception]);
        }
    }
}
