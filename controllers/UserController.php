<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\RegisterForm;
use app\models\User;
use app\models\Client;
use yii\base\UserException;
use yii\helpers\Html;

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
        $model = new LoginForm();
        if (
            !Yii::$app->user->isGuest || 
            $model->load(Yii::$app->request->post()) && 
            $model->login()
        ) {
            return $this->redirect(['site/landing']);
        }
        
        return $this->render(
            'login', 
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

    public static function getAvailableContactsList($id) {
        if ($id) {
            $users = Client::find()
                ->where(['not', 'id='.$id])
                ->all();
            $result = [];
            foreach ($users as $user) {
                $result[$user->id] = Html::a(
                    $user->username, 
                    [
                        'site/profile', 
                        'id' => $user->id
                    ]
                ).HTML::tag(
                    'span', 
                    'user-'.$user->state,//'', 
                    [
                        'class' => ['user-state', 'user-'.$user->state]
                    ]
                );
            }
            return $result;
        } else {
            return [];
        }
    }
}
