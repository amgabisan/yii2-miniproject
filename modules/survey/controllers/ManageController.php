<?php

namespace app\modules\survey\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\Questionnaire;
use app\models\UserAnswer;
use app\models\Question;

class ManageController extends \yii\web\Controller
{
    public $layout = '/main';
    public $route_nav;
    public $viewPath = 'app/modules/survey/views';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                // Pages that are included in the rule set
                'only'  => ['index', 'create', 'edit', 'delete', 'answer', 'thanks'],
                'rules' => [
                    [ // Pages that can be accessed when logged in
                        'allow'     => true,
                        'actions'   => ['index', 'create', 'edit', 'delete', 'answer', 'thanks'],
                        'roles'     => ['@']
                    ],
                    [ // Pages that can be accessed when not logged in
                        'allow'     => true,
                        'actions'   => ['answer', 'thanks'],
                        'roles'     => ['?']
                    ]
                ],
                'denyCallback' => function ($rule, $action) {
                    // Action when user is denied from accessing a page
                    if (Yii::$app->user->isGuest) {
                        $this->goHome();
                    } else {
                        $this->redirect(['/dashboard']);
                    }
                }
            ]
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function init()
    {
        if(Yii::$app->user->isGuest) {
            $url = '';
        } else {
            $url = '/dashboard';
        }
    }

    public function actionIndex()
    {
        $userId = Yii::$app->user->identity->id;
        $model = new Questionnaire();
        $result = $model->getQuestionnaires($userId);

        return $this->render('index', [
            'records' => $result
        ]);
    }

    public function actionCreate()
    {
        $model = new Questionnaire;

        if (Yii::$app->request->isPost) {
            $model->name = trim(Yii::$app->request->post('Questionnaire')['name']);
            $model->user_id = Yii::$app->user->identity->id;
            if ($model->validate()) {
                $question = Yii::$app->request->post('Question');
                if ($model->saveSurvey($model, $question)) {
                    return $this->redirect('/dashboard');
                } else {
                    Yii::$app->session->setFlash('error', 'There was an error while saving your survey. Please try again later.');
                }
            } else {
                if (in_array("name", $model->errors['name'])) {
                    $model->addError('name', $model->errors['name']);
                }
            }

        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    public function actionEdit($id)
    {
        $model = Questionnaire::find()->where(['id' => $id, 'user_id' => Yii::$app->user->identity->id])->one();
        $answerCount = UserAnswer::find()->where(['questionnaire_id' => $id])->count();

        if (empty($model) || $answerCount != 0) {
            return $this->redirect('/dashboard');
        }

        $questions = Question::find()->where(['questionnaire_id' => $id])->all();

        if (Yii::$app->request->isPost) {
            $model->name = trim(Yii::$app->request->post('Questionnaire')['name']);
            $model->user_id = Yii::$app->user->identity->id;
            if ($model->validate()) {
                $question = Yii::$app->request->post('Question');
                if ($model->saveSurvey($model, $question, $id)) {
                    return $this->redirect('/dashboard');
                } else {
                    Yii::$app->session->setFlash('error', 'There was an error while editing your survey. Please try again later.');
                }
            } else {
                if (in_array("name", $model->errors['name'])) {
                    $model->addError('name', $model->errors['name']);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'questions' => $questions
        ]);
    }

    public function actionDelete($id)
    {
        $model = new Questionnaire;

        if ($model->deleteSurvey($id)) {
            return '/dashboard';
        } else {
            return false;
        }
    }

    public function actionAnswer($id)
    {
        $model = Questionnaire::findOne($id);

        if (empty($model)) {
            return $this->redirect('/');
        }

        $question = Question::find()->where(['questionnaire_id' => $id])->all();

        if (Yii::$app->request->isPost) {
            $answers = Yii::$app->request->post('UserAnswer');
            $userAnswerModel = new UserAnswer;
            if ($userAnswerModel->saveAnswer($id, $answers)) {
                return $this->redirect('/thanks');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error while saving your answer. Please try again later.');
            }
        }

        return $this->render('answer', [
            'model' => $model,
            'question' => $question
        ]);
    }

    public function actionThanks()
    {
        return $this->render('thankyou');
    }
}
