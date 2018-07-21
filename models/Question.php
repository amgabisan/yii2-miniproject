<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "question".
 *
 * @property int $id
 * @property int $questionnaire_id
 * @property string $question
 * @property string $type
 * @property string $type_details For single/multiple choice, CSV of choices while for rating, no. of stars
 * @property int $required_flag 1-required, 0-not required
 * @property string $ins_time
 * @property string $up_time
 *
 * @property Questionnaire $questionnaire
 * @property UaDetails[] $uaDetails
 */
class Question extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'question';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['questionnaire_id', 'question', 'type', 'required_flag', 'ins_time', 'up_time'], 'required'],
            [['questionnaire_id', 'required_flag'], 'integer'],
            [['question', 'type', 'type_details'], 'string'],
            [['ins_time', 'up_time'], 'safe'],
            [['questionnaire_id'], 'exist', 'skipOnError' => true, 'targetClass' => Questionnaire::className(), 'targetAttribute' => ['questionnaire_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'questionnaire_id' => 'Questionnaire ID',
            'question' => 'Question',
            'type' => 'Type',
            'type_details' => 'Type Details',
            'required_flag' => 'Required Flag',
            'ins_time' => 'Ins Time',
            'up_time' => 'Up Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionnaire()
    {
        return $this->hasOne(Questionnaire::className(), ['id' => 'questionnaire_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUaDetails()
    {
        return $this->hasMany(UaDetails::className(), ['question_id' => 'id']);
    }
}
