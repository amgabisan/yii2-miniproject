<?php

namespace app\modules\survey\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\Questionnaire;

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
                'only'  => ['dashboard'],
                'rules' => [
                    [ // Pages that can be accessed when logged in
                        'allow'     => true,
                        'actions'   => ['dashboard'],
                        'roles'     => ['@']
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

}
