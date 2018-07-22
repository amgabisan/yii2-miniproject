<?php

namespace app\models;

use Yii;
use \yii\db\Query;
use yii\base\Exception;

/**
 * This is the model class for table "questionnaire".
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $ins_time
 * @property string $up_time
 *
 * @property Question[] $questions
 * @property User $user
 * @property UserAnswer[] $userAnswers
 */
class Questionnaire extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'questionnaire';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'name'], 'required'],
            [['user_id'], 'integer'],
            [['ins_time', 'up_time'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Account::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'name' => 'Name',
            'ins_time' => 'Ins Time',
            'up_time' => 'Up Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(Question::className(), ['questionnaire_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAnswers()
    {
        return $this->hasMany(UserAnswer::className(), ['questionnaire_id' => 'id']);
    }

    public function getQuestionnaires($userId)
    {
        $query = new Query();
        $query->params([':userId' => $userId]);
        $query->select(['q.*', 'count(ua.questionnaire_id) AS questionnaire_count'])
              ->from('questionnaire q')
              ->leftJoin('user_answer ua', 'q.id = ua.questionnaire_id')
              ->where('q.user_id=:userId')
              ->groupBy('q.id')
              ->orderBy('q.up_time DESC');

        $command = $query->createCommand();

        return $command->queryAll();
    }

    public function saveSurvey($survey, $questions, $id=null)
    {
        $connection = Yii::$app->db;
        $transaction =  $connection->beginTransaction();

        try {
            if (empty($id)) { // New Survey
                $survey->ins_time = $survey->up_time = Yii::$app->formatter->asDatetime('now');
            } else { // Update Survey
                $survey->up_time = Yii::$app->formatter->asDatetime('now');

                // Delete existing questions and add yhe new questions
                $questionCount = Question::find()->where(['questionnaire_id' => $id])->count();
                $deletedCount = Question::deleteAll(['questionnaire_id' => $id]);

                if ($questionCount != $deletedCount) {
                    $transaction->rollBack();
                    return false;
                }
            }

            if ($survey->save()) {
                foreach ($questions as $value) {
                    $model = new Question;
                    $model->questionnaire_id = $survey->id;
                    $model->question = trim($value['question']);
                    $model->type = $value['type'];
                    $model->required_flag = ($value['required'] == 'Yes') ? 1 : 0;
                    $model->ins_time = $model->up_time = Yii::$app->formatter->asDatetime('now');

                    if ($model->type == 'single_choice' || $model->type == 'multiple_choice') {
                        $model->type_details = $value['choices'];
                    } else if ($model->type == 'rating') {
                        $model->type_details = $value['no_of_stars'];
                    }

                    if (!$model->save()) {
                        $transaction->rollBack();
                        return false;
                    }
                }

                $transaction->commit();
                return true;
            } else {
                $transaction->rollBack();
                return false;
            }

        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }
}
