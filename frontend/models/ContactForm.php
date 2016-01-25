<?php

namespace frontend\models;

use himiklab\yii2\recaptcha\ReCaptchaValidator;
use Yii;
use yii\db\ActiveRecord;

/**
 * ContactForm is the model behind the contact form.
 * @property string $name
 * @property string $email
 * @property string $phone_number
 * @property string $text
 * @property string $subject
 */
class ContactForm extends ActiveRecord
{

    public function  __construct($config = [])
    {
        parent::__construct();
        if(!Yii::$app->user->isGuest)
        {
            $this->name = empty($this->name) ?  User::getLogged(true)->username : $this->name;
            $this->email = empty($this->email) ? User::getLogged(true)->email : $this->email;
        }
    }
    public $verifyCode;

    public static function  tableName()
    {
        return 'contact_letters';
    }


    public function afterSave($insert, $changedAttributes)
    {
        Yii::$app->mailer->compose('user-contact-email')
            ->setTo($this->email)
            ->send();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'phone_number', 'text'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
                ['verifyCode',  ReCaptchaValidator::className(),],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => Yii::t('app',':contact_form_verification_code'),
            'name' => Yii::t('',':contact_form_name'),
            'email' => ':contact_form_email',
            'phone_number' => ':contact_form_phone_number',
        ];
    }


}
