<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * User model
 *
 * @property integer $id
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $file
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $role
 */
class User extends ActiveRecord implements IdentityInterface
{

    public   $resumeFile;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email','filter','filter'=>'trim'],
            ['email','email'],
            ['email','unique'],
            [['email'],'required'],
            [['password','passwordConfirm'],'required','on'=>'create'],
            ['passwordConfirm','compare','compareAttribute'=>'password','on'=>'create'],
            ['role','default','value'=> self::user],
            ['role','in','range'=>!Yii::$app->user->isGuest ? array_flip(Yii::$app->user->identity->getEditableRoles()) : array_flip(User::$roles)],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
        ];
    }

    public function beforeValidate()
    {
        if(parent::beforeValidate())
        {
            if($this->resumeFile instanceof UploadedFile)
            {
                if(!$this->isNewRecord)
                {

                    if(is_file($this->getFileSavePath(). $this->oldAttributes['file']))
                    {
                        unlink($this->getFileSavePath(). $this->oldAttributes['file']);
                    }
                }
                $this->file = $this->getFileName() . $this->resumeFile->extension;
            }
            else
            {
                if(isset($this->oldAttributes['file']))
                    $this->file = $this->oldAttributes['file'];
            }
            return true;
        }
        else return false;
    }

    public function getFileSaveDir()
    {
        return Yii::getAlias('@file_save_dir');
    }

    public function getFileViewDir()
    {
       return Yii::getAlias('@file_view_dir');
    }

    public function getFileViewUrl()
    {
        return Yii::getAlias('@file_view_url');
    }

    public function getFileSavePath()
    {
        return self::getFileSaveDir() . $this->id . DIRECTORY_SEPARATOR;
    }

    public function getFileViewPath()
    {
        return self::getFileViewUrl() . $this->id . '/';
    }

    public function getFile()
    {
        return self::getFileViewPath() . $this->file;
    }

    public function getFileName()
    {
        return Yii::$app->security->generateRandomString(16) . '.';
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

    public static $statuses = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'Inactive'
    ];

    public static function  getStatus($status)
    {
        return (isset(self::$statuses[$status]) ? self::$statuses[$status] : null);
    }

    public function getCurrentStatus()
    {
        return (isset(self::$statuses[$this->status]) ? self::$statuses[$this->status] : null);
    }


    public static function  getRole($role)
    {
        return (isset(self::$roles[$role]) ? self::$roles[$role] : null);
    }

    public function getCurrentRole()
    {
        return (isset(self::$roles[$this->role]) ? self::$roles[$this->role] : null);
    }

    public static $roles = [
      self::user => 'User',
      self::admin => 'Admin',
      self::super_admin => 'Super Admin',
    ];

    public static $status_colors = [
      self::STATUS_INACTIVE  => 'red',
      self::STATUS_ACTIVE => 'green'
    ];



    public function getEditableRoles()
    {
        $editable_roles = [
            self::super_admin => [self::admin => 'Admin',self::user=>'User'],
            self::admin => [self::user=>'User'],
            self::user => []
        ];
        return isset($editable_roles[$this->role]) ? $editable_roles[$this->role] : [];
    }

    public function  canEdit($checking_role)
    {
        foreach (self::getEditableRoles() as  $role=>$label)
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
        if(!self::$_logged_user || self::$_is_need_update)
        {
            self::$_logged_user = self::findOne(['id'=>Yii::$app->user->id]);
            if($safe && !(self::$_logged_user  instanceof User))
            {
                throw new NotFoundHttpException;
            }
        }
        return self::$_logged_user ;
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
            }
            return true;
        }
        return false;
    }



    public function afterSave($insert,$oldAttributes)
    {
        parent::afterSave($insert,$oldAttributes);
        if(!is_dir($this->getFileSavePath()))
        {
            FileHelper::createDirectory($this->getFileSavePath());
        }
        if(!is_dir($this->getFileViewDir()))
        {
            symlink($this->getFileSaveDir(),$this->getFileViewDir());
        }
        if($this->resumeFile instanceof UploadedFile)
            $this->resumeFile->saveAs($this->getFileSavePath() . $this->file);
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
}
