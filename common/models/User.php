<?php
namespace common\models;

use common\components\Alert;
use common\helpers\Helper;
use console\controllers\RbacController;
use dezmont765\yii2bundle\models\MainActiveRecord;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * User model
 *
 * @property integer $id
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $username
 * @property string $email
 * @property string $file
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $role
 * @property integer $email_verification_status
 * @property string $email_verification_code
 */
class User extends MainActiveRecord implements IdentityInterface
{

    public   $uploadedFile;
    const PASSWORD_CHANGE = 'password-change';
    const EMAIL_VERIFIED = 1;
    const EMAIL_NOT_VERIFIED = 0;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email','filter','filter'=>'trim'],
            ['username','string'],
            ['email','email'],
            ['email','unique'],
            [['email'],'required'],
            [['password','passwordConfirm'],'required','on'=>'create'],
            ['passwordConfirm','compare','compareAttribute'=>'password','on'=>'create'],
            ['role','default','value'=> self::user],
            ['role','in','range'=> array_flip(User::roles())],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
        ];
    }


    public $password;
    public $passwordConfirm;

    const super_admin = 'super_admin';
    const admin = 'admin';
    const user = 'user';


    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    private static $_logged_user = null;
    private static $_is_need_update = false;

    public static function statuses()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('app',':user_status_active'),
            self::STATUS_INACTIVE => Yii::t('app',':user_status_inactive')
        ];
    }

    public static function  getStatus($status)
    {
        return (isset(self::statuses()[$status]) ? self::statuses()[$status] : null);
    }

    public function getCurrentStatus()
    {
        return (isset(self::statuses()[$this->status]) ? self::statuses()[$this->status] : null);
    }


    public static function  getRole($role)
    {
        return (isset(self::roles()[$role]) ? self::roles()[$role] : null);
    }

    public function getCurrentRole()
    {
        return (isset(self::roles()[$this->role]) ? self::roles()[$this->role] : null);
    }

    public static function roles() {
        return [
            self::user => Yii::t('app',':user_role_user'),
            self::admin => Yii::t('app',':user_role_admin'),
            self::super_admin => Yii::t('app',':user_role_super_admin'),
        ];
    }

    public static $status_colors = [
      self::STATUS_INACTIVE  => 'red',
      self::STATUS_ACTIVE => 'green'
    ];



    public function getEditableRoles($user_id = null)
    {
        $editable_roles = RbacController::getEditableRoles();
        if(isset($editable_roles[$this->role]))
        {
            array_walk($editable_roles[$this->role],function(&$value,$key) {
                $value = isset(self::roles()[$key]) ? self::roles()[$key] : $value;
            });
            if($user_id !== null && $user_id === $this->id)
                $editable_roles[$this->role][$this->role] = $this->getCurrentRole();
        }
        return isset($editable_roles[$this->role]) ? $editable_roles[$this->role] : [];
    }

    public function  canEdit($checking_role)
    {
        foreach (self::getEditableRoles() as  $role => $label)
        {
            if($checking_role == $role)
                return true;
        }
        return false;
    }

    public function canDelete($checking_role)
    {
        return self::canEdit($checking_role);
    }

    /**
     * @param bool $safe
     * @return null|User
     * @throws NotFoundHttpException
     */
    public static  function getLogged($safe = false)
    {
        $user = Yii::$app->user->identity;
        if($safe && !($user instanceof User))
        {
            throw new NotFoundHttpException;
        }
        return $user;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }


    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert))
        {
            if($this->isNewRecord)
            {
                $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
                $this->generateAuthKey();
                if($this->email_verification_code === null)
                {
                    $this->email_verification_code = Yii::$app->security->generateRandomString(16);
                }
            }
            return true;
        }
        return false;
    }



    public function afterSave($insert,$oldAttributes)
    {
        parent::afterSave($insert,$oldAttributes);
        if($insert)
        {
                $this->sendVerificationEmail($this->email_verification_code);
        }
        self::commitLocalTransaction();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
*/
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByEmail($email)
    {
        return static::findOne(['email'=>$email,'status'=>self::STATUS_ACTIVE]);
    }

    public static function findAdminsByEmail($email)
    {
        $query = static::find();
        $query->andFilterWhere(['email'=>$email,'status'=>self::STATUS_ACTIVE]);
        $query->andFilterWhere(['not',['role'=>self::user]]);
        return $query->one();
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function attributeLabels()
    {
        return [
            'email' => Yii::t('user','Email'),
            'status' => Yii::t('user','Status'),
            'role' => Yii::t('user','Role'),
            'password' => Yii::t('user','Password'),
            'passwordConfirm' => Yii::t('user','Confirm password'),
            'created_at' => Yii::t('user','Created At'),
            'updated_at' => Yii::t('user','Updated At'),
        ];
    }

    public function sendWelcomeMail()
    {
        if($this->email_verification_status == self::EMAIL_VERIFIED)
        {
            Yii::$app->mailer->compose('welcome-mail',['user_name' => (empty($this->username) ? $this->email : $this->username), 'user_email' => $this->email])->setTo($this->email)->send();
            Alert::addSuccess(Yii::t('messages','Your email has been verified'));
        }
    }

    public function sendVerificationEmail($code)
    {
        if($this->email_verification_status == self::EMAIL_NOT_VERIFIED)
        {
            if(Yii::$app->mailer->compose('email-verification',[
                'user_name' =>(empty($this->username) ? $this->email : $this->username),
                'email_verification_url' => Url::to(['user/verify-email','code'=>$code],true),
            ])->setTo($this->email)->setSubject('Dear,'.(empty($this->username) ? $this->email : $this->username))->send()){
                Alert::addSuccess(Yii::t('messages','Verification Email has been sent!'));
            }
            else
                Alert::addError(Yii::t('messages','Verification Email hasn\'t been sent'));
        }
    }

    public function renewVerificationCode()
    {
        $this->email_verification_code = Yii::$app->security->generateRandomString(16);
        return $this->save();
    }
}
