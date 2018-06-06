<?php

namespace app\models;

use Yii;

use yii\base\Exception;

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
    public $old_password;
    public $confirm_password;

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
            [['password', 'password_reset_token', 'auth_key'], 'string', 'max' => 255],
            [['email_address'], 'string', 'max' => 100],
            [['last_name', 'first_name'], 'string', 'max' => 50],
            [['email_address'], 'unique'],
            ['email_address', 'email'],
            // Forgot Password and Change Password
            [['password', 'old_password', 'confirm_password'], 'required', 'on' => 'passwordUpdate'],
            ['confirm_password', 'compare', 'compareAttribute' => 'password',
             'message' => 'Passwords don\'t match.', 'on' => 'passwordUpdate'],
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
            'last_name' => 'Surname',
            'first_name' => 'Given Name',
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

    public function generatePassword($password)
    {
        return Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        return $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generatePasswordResetToken()
	{
		return $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
	}

    public static function validatePassword($password, $hash_password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $hash_password);
    }

    public function saveRecord($post, $id=null)
    {
        $connection = Yii::$app->db;
        $transaction =  $connection->beginTransaction();

        try {
            if (!empty($id)) {
                $model = self::findOne($id);
            } else {
                $model = new Account;
                $model->ins_time = Yii::$app->formatter->asDatetime('now');
            }

            $model->user_type = 'user';
            $model->auth_key = $this->generateAuthKey();
            $model->password = Yii::$app->getSecurity()->generatePasswordHash($post['password']);
            $model->password_reset_token = $this->generatePasswordResetToken();
            $model->email_address = $post['email_address'];
            $model->last_name = ucwords(strtolower($post['last_name']));
            $model->first_name = ucwords(strtolower($post['first_name']));
            $model->status = 'unconfirmed';
            $model->up_time = Yii::$app->formatter->asDatetime('now');

            if ($model->save()) {
                $transaction->commit();
                return $model;
            } else {
                var_dump($model->errors); exit;
                $transaction->rollBack();
                return false;
            }

        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }

}
