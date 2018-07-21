<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_answer".
 *
 * @property int $id
 * @property int $questionnaire_id
 * @property string $ins_time
 * @property string $up_time
 *
 * @property UaDetails[] $uaDetails
 * @property Questionnaire $questionnaire
 */
class UserAnswer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_answer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['questionnaire_id', 'ins_time', 'up_time'], 'required'],
            [['questionnaire_id'], 'integer'],
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
            'ins_time' => 'Ins Time',
            'up_time' => 'Up Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUaDetails()
    {
        return $this->hasMany(UaDetails::className(), ['user_answer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionnaire()
    {
        return $this->hasOne(Questionnaire::className(), ['id' => 'questionnaire_id']);
    }
}
