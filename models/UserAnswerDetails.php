<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ua_details".
 *
 * @property int $id
 * @property int $user_answer_id
 * @property int $question_id
 * @property string $answer
 * @property string $ins_time
 * @property string $up_time
 *
 * @property Question $question
 * @property UserAnswer $userAnswer
 */
class UserAnswerDetails extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ua_details';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_answer_id', 'question_id', 'answer', 'ins_time', 'up_time'], 'required'],
            [['user_answer_id', 'question_id'], 'integer'],
            [['answer'], 'string'],
            [['ins_time', 'up_time'], 'safe'],
            [['question_id'], 'exist', 'skipOnError' => true, 'targetClass' => Question::className(), 'targetAttribute' => ['question_id' => 'id']],
            [['user_answer_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserAnswer::className(), 'targetAttribute' => ['user_answer_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_answer_id' => 'User Answer ID',
            'question_id' => 'Question ID',
            'answer' => 'Answer',
            'ins_time' => 'Ins Time',
            'up_time' => 'Up Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(Question::className(), ['id' => 'question_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAnswer()
    {
        return $this->hasOne(UserAnswer::className(), ['id' => 'user_answer_id']);
    }
}
