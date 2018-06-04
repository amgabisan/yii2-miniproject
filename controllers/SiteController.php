<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\Auth;
use app\models\Account;
use app\components\helpers\Mail;
use yii\helpers\Url;

class SiteController extends Controller
{
    public $layout = 'loginLayout';
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                // Pages that are included in the rule set
                'only'  => ['login', 'logout', 'dashboard', 'confirm'],
                'rules' => [
                    [ // Pages that can be accessed when logged in
                        'allow'     => true,
                        'actions'   => ['logout', 'dashboard'],
                        'roles'     => ['@']
                    ],
                    [ // Pages that can be accessed without logging in
                        'allow'     => true,
                        'actions'   => ['login', 'confirm'],
                        'roles'     => ['?']
                    ]
                ],
                'denyCallback' => function ($rule, $action) {
                    // Action when user is denied from accessing a page
                    if (Yii::$app->user->isGuest) {
                        $this->goHome();
                    } else {
                        $this->redirect(['/site/dashboard']);
                    }
                }
            ]
        ];
    }

    /**
     * {@inheritdoc}
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
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $user = Yii::$app->user->getIdentity()->attributes;
            $session = Yii::$app->session;
            $session->open();
            foreach ($user as $userKey => $userValue) {
                $session[$userKey] = $userValue;
            }
            $session->close();

            return $this->redirect(['/site/dashboard']);
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        $session = Yii::$app->session;
        $session->removeAll();          // Removes all the session variables
        $session->destroy();            // Destroy session
        Yii::$app->response->clear();   // Clears the headers, cookies, content, status code of the response.
        Yii::$app->user->logout();
        $this->goHome();
    }

    public function actionRegister()
    {
        $model = new Account;

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post('Account');

            if ($model = $model->saveRecord($post)) {
                $body = '<td style="padding-top: 10px; text-align: center">
                            <a href="http://amg.com/site/confirm/'.$model->auth_key.'" class="btn">Confirm Account</a>
                        </td>';

                $mail = Mail::sendMail($body, $model->first_name.' '.$model->last_name);

                if (Yii::$app->mailer->compose()
                            ->setCharset('UTF-8')
                            ->setFrom(['admin@amg.com' => 'AMG'])
                            ->setTo($model->email_address)
                            ->setSubject('Registration')
                            ->setHtmlBody($mail)
                            ->send()) {
                   Yii::$app->session->setFlash('success', 'You have successfully registered. Please confirm your account to login</a>.');
                } else {
                    Yii::$app->session->setFlash('error', 'There was an error while registering your account. Please try again later.');
                }
            } else {
                 Yii::$app->session->setFlash('error', 'There was an error while registering your account. Please try again later.');
            }

            return $this->refresh();
        }

        return $this->render('register', [
            'model' => $model,
        ]);
    }

    public function actionConfirm($auth)
    {
        $loginFormModel = new LoginForm();
        $model = Account::findOne(['auth_key' => $auth]);

        if (empty($model)) {
            Yii::$app->session->setFlash('error', 'There was an error confirming your account. Please check if you have the correct link.');
        } else {
            $model->status = 'active';
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Account confirmed. You may now login');
            } else {
                Yii::$app->session->setFlash('error', 'An error confirming your account.');
            }
        }

         return $this->render('login', [
            'model' => $loginFormModel,
        ]);
    }

    public function actionDashboard()
    {
        return $this->render('dashboard');
    }
}
