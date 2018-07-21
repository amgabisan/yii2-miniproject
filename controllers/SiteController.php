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
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
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
            $model->up_time = Yii::$app->formatter->asDatetime('now');
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

    public function actionForgotpassword()
    {
        $model = new Account;

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post('Account');

            $model = Account::findOne(['email_address' => $post['email_address']]);
            if (empty($model)) {
                Yii::$app->session->setFlash('error', 'Account not found.');
            } else {
                $body = '<td style="padding-top: 10px; text-align: center">
                            <a href="http://amg.com/site/resetpassword/'.$model->password_reset_token.'" class="btn">Reset Password</a>
                        </td>';

                $mail = Mail::sendMail($body, $model->first_name.' '.$model->last_name);
                if (Yii::$app->mailer->compose()
                            ->setCharset('UTF-8')
                            ->setFrom(['admin@amg.com' => 'AMG'])
                            ->setTo($model->email_address)
                            ->setSubject('Reset Password')
                            ->setHtmlBody($mail)
                            ->send()) {
                   Yii::$app->session->setFlash('success', 'Please check your e-mail for the reset link.');
                } else {
                    Yii::$app->session->setFlash('error', 'There was an error while looking for your account. Please try again later.');
                }
            }
        }

        return $this->render('forgotpassword', [
            'model' => $model,
        ]);
    }

    public function actionResetpassword($auth)
    {
        $model = Account::findOne(['password_reset_token' => $auth]);

        if (empty($model)) {
            Yii::$app->session->setFlash('error', 'Account not found. Please check if you have the correct link to reset your password');
        } else {
           $model->scenario = 'passwordUpdate';
           $oldPassword = $model->password;
        }

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post('Account');

            if (!$model->validatePassword($post['old_password'], $oldPassword)) {
                $model->addError('old_password', 'Your old password is incorrect');
            } else {
                $model->password_reset_token = $model->generatePasswordResetToken();
                $model->old_password = $post['old_password'];
                $model->password = $model->confirm_password = Yii::$app->getSecurity()->generatePasswordHash($post['password']);
                $model->up_time = Yii::$app->formatter->asDatetime('now');

                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Your password has been updated. You may now <a href="/site/login">login</a>');
                } else {
                    Yii::$app->session->setFlash('error', 'There was an error while saving your password. Please try again later');
                }
            }


        }

        return $this->render('resetpassword', [
            'model' => $model,
        ]);
    }

    public function onAuthSuccess($client)
    {
       $attributes = $client->getUserAttributes();

        $auth = Auth::find()->where([
            'source' => $client->getId(),
            'source_id' => $attributes['id'],
        ])->one();

        if ($client->getId() == 'google') {
            $email = $attributes['emails'][0]['value'];
//            $logins = explode('@', $email);
//            $login = $logins[0];

            $userInfoArray = [
                'last_name' => ucwords($attributes['name']['familyName']),
                'first_name' => ucwords($attributes['name']['givenName']),
            ];
        } else {
            $email = $attributes['email'];
//            $login = $attributes['login'];

            $name = explode(' ', $attributes['name']);
            $userInfoArray = [
                'last_name' => ucwords(array_pop($name)),
                'first_name' => ucwords(implode(" ", $name)),
            ];
        }

        if (Yii::$app->user->isGuest) {
            if ($auth) { // login
                $user_id = $auth->user_id;
                $user = Account::findOne($user_id);
                Yii::$app->user->login($user);

                $this->sessionLogin();
                $this->redirect(['/site/dashboard']);

            } else { // signup
                if (Account::find()->where(['email_address' => $email])->exists()) {
                    Yii::$app->getSession()->setFlash('error',  "Account already exist.");
                } else {
                    $password = Yii::$app->security->generateRandomString(6);

                    $user = [
                        'email_address' => $email,
                        'password' => $password,
                        'last_name' => $userInfoArray['last_name'],
                        'first_name' => $userInfoArray['first_name']
                    ];

//                    $user = new Account;
//                    $user->user_type = 'user';
//                    $user->email_address = $email;
//                    $user->password = $user->generatePassword($password);
//                    $user->generateAuthKey();
//                    $user->generatePasswordResetToken();
                    $connection = Yii::$app->db;
                    $transaction =  $connection->beginTransaction();

                    try {
                        $userModel = new Account;
                        if ($userModel = $userModel->saveRecord($user)) {
                            $userModel->status = 'active';

                            if ($userModel->save()) {
                                $auth = new Auth([
                                    'user_id' => $userModel->id,
                                    'source' => $client->getId(),
                                    'source_id' => (string)$attributes['id'],
                                ]);
                                if ($auth->save()) {
                                    $transaction->commit();
                                    Yii::$app->user->login($userModel);

                                    $this->sessionLogin();
                                    // Send Email to user for new password
                                    $body = '<td style="padding-top: 10px; padding-bottom: 10px">
                                                <h3>Your password is <b>'.$password.'.</b></h3>
                                            </td>';

                                    $mail = Mail::sendMail($body, $userModel->first_name.' '.$userModel->last_name);
                                    if (Yii::$app->mailer->compose()
                                            ->setCharset('UTF-8')
                                            ->setFrom(['admin@gladeye-test.dev' => 'Gladeye'])
                                            ->setTo($userModel->email_address)
                                            ->setSubject('Registration')
                                            ->setHtmlBody($mail)
                                            ->send()) {
                                       Yii::$app->session->setFlash('success', 'A generated password has been sent to your email.');
                                    } else {
                                        Yii::$app->session->setFlash('error', 'There was an error while registering your account. Please try again later.');
                                    }
                                    $this->redirect(['/site/dashboard']);
                                } else {
                                    $transaction->rollBack();
                                    print_r($auth->getErrors());
                                }
                            } else {
                                $transaction->rollBack();
                                print_r($userInfo->getErrors()); exit;
                            }
                        } else {
                             $transaction->rollBack();
                             print_r($user->getErrors()); exit;
                        }
                    } catch (Exception $e) {
                        $transaction->rollBack();
                    }
                }
            }
        } else { // user already logged in
            if (!$auth) { // add auth provider
                $auth = new Auth([
                    'user_id' => Yii::$app->user->id,
                    'source' => $client->getId(),
                    'source_id' => $attributes['id'],
                ]);
                $auth->save();
            }
        }
    }

    private function sessionLogin()
    {
        $user = Yii::$app->user->getIdentity()->attributes;
        $session = Yii::$app->session;
        $session->open();
        foreach ($user as $userKey => $userValue) {
            $session[$userKey] = $userValue;
        }
        $session->close();
    }
}
