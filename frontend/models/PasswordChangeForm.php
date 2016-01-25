<?php
namespace frontend\models;

use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;

/**
 * Password reset form
 */
class PasswordChangeForm extends Model
{
    public $password;
    public $passwordConfirm;


    /**
     * @var \common\models\User
     */
    private $_user;


    /**
     * Creates a form model given a token.
     *
     * @param  string                          $token
     * @param  array                           $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException('Password reset token cannot be blank.');
        }
        $this->_user = User::findByPasswordResetToken($token);
        if (!$this->_user) {
            throw new InvalidParamException('Wrong password reset token.');
        }
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password','passwordConfirm'], 'required'],
            ['passwordConfirm','compare','compareAttribute'=>'password','skipOnEmpty'=>false],
        ];
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function changePassword()
    {
        $user = $this->_user;
        $user->scenario = User::PASSWORD_CHANGE;
        $user->password = $this->password;
        $user->passwordConfirm = $this->passwordConfirm;
        $user->removePasswordResetToken();

        $result = $user->save();
        return $result;
    }
}
