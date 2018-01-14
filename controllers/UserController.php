<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\forms\LoginForm;
use app\models\forms\RegisterForm;
use app\models\forms\SetStatusForm;
use app\models\forms\UserProfileForm;
use app\models\User;
use yii\base\UserException;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;

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
                        'actions' => ['logout', 'status', 'save-profile'],
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['login', 'register', 'profile'],
                        'allow' => true,
                        'roles' => ['?']
                    ],
                    [
                        'actions' => ['logout', 'status', 'profile', 'save-profile'],
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'status' => ['post'],
                    'save-profile' => ['post'],
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
     * Helper to populate users checklist html with data in valid format
     */
    public static function getAvailableContactsList($id) {
        if ($id) {
            $users = User::find()
                ->where(['not', 'id='.$id])
                ->all();
            $result = [];
            foreach ($users as $user) {
                $userState = $user->getState();
                $result[$user->id] = Html::a(
                    $user->username, 
                    [
                        'user/profile', 
                        'id' => $user->id
                    ]
                ).HTML::tag(
                    'span', 
                    '', 
                    [
                        'class' => ['user-state', 'user-'.$userState]
                    ]
                );
            }
            return $result;
        } else {
            return [];
        }
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

    public function actionStatus() 
    {
        $model = new SetStatusForm();
        if (
            $model->load(Yii::$app->request->post()) && 
            $model->setStatus()
        ) {
            return $this->redirect(
                Yii::$app->request->referrer
            );
        }
    }

    public function actionProfile()
    {
        $data = Yii::$app->request->get();
        if (!isset($data['id']) || !$data['id']) {
            throw new NotFoundHttpException();
        }
        $user = User::findOne(['id' => $data['id']]);
        if (!$user) {
            throw new NotFoundHttpException();
        }

        return $this->render(
            'profile',
            [
                'user' => $user,
            ]
        );
    }

    public function actionSaveProfile() 
    {
        $model = new UserProfileForm();
        if (
            $model->load(Yii::$app->request->post()) && 
            $model->saveProfile()
        ) {
            return $this->redirect(
                Yii::$app->request->referrer
            );
        } 
    }
}
