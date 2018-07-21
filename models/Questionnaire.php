<?php

namespace app\models;

use Yii;
use \yii\db\Query;

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
            [['user_id', 'name', 'ins_time', 'up_time'], 'required'],
            [['user_id'], 'integer'],
            [['ins_time', 'up_time'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
              ->groupBy('ua.questionnaire_id')
              ->orderBy('q.up_time DESC');

        $command = $query->createCommand();

        return $command->queryAll();
    }
}
