<?php
namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
                                  'status' => User::STATUS_ACTIVE,
                                  'email' => $this->email,
                              ]);

        if ($user) {
            if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
                $user->generatePasswordResetToken();
            }

            if ($user->save()) {
                return \Yii::$app->mailer->compose('password-token',['user_name' => $user->username,
                                                                     'password-reset-link' => Yii::$app->urlManager->createAbsoluteUrl(['site/password-change', 'token' => $user->password_reset_token]),
                ])
                    ->setTo($this->email)
                    ->send();
            }
        }

        return false;
    }
}
