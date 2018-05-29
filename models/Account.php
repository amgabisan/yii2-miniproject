<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $user_type
 * @property string $username
 * @property string $auth_key
 * @property string $password
 * @property string $password_reset_token
 * @property string $email_address
 * @property string $last_name
 * @property string $first_name
 * @property string $status
 * @property string $ins_time
 * @property string $up_time
 */
class Account extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_type', 'status'], 'string'],
            [['password', 'email_address', 'last_name', 'first_name', 'status'], 'required'],
            [['ins_time', 'up_time'], 'safe'],
            [['username'], 'string', 'max' => 30],
            [['auth_key'], 'string', 'max' => 32],
            [['password', 'password_reset_token'], 'string', 'max' => 255],
            [['email_address'], 'string', 'max' => 100],
            [['last_name', 'first_name'], 'string', 'max' => 50],
            [['email_address'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_type' => 'User Type',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password' => 'Password',
            'password_reset_token' => 'Password Reset Token',
            'email_address' => 'Email Address',
            'last_name' => 'Last Name',
            'first_name' => 'First Name',
            'status' => 'Status',
            'ins_time' => 'Ins Time',
            'up_time' => 'Up Time',
        ];
    }

    /*****************************     IDENTITY INTERFACE   *****************************/


    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /*****************************     CUSTOM FUNCTIONS   *****************************/

    public static function findByEmailAddress($email)
    {
        return static::findOne(['email_address' => $email]);
    }

}
