<?php
namespace common\components;
use common\helpers\Helper;
use common\models\MainActiveRecord;
use Yii;
use yii\base\Behavior;
use yii\base\InvalidParamException;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 19.04.2015
 * Time: 14:25
 * Class FileSaveBehavior
 * @property MainActiveRecord $owner
 * @property MainActiveRecord $lama
 */

class FileSaveBehavior extends Behavior
{
    const INSTANCE = 'instance';
    const FILE_SAVE_DIR = 'file_save_dir';
    const PREFIXES = 'prefixes';
    const FILE_VIEW_DIR = 'file_view_dir';
    const FILE_VIEW_URL = 'file_view_url';
    const BACKEND_VIEW_DIR = 'backend_view_dir';
    const FRONTEND_VIEW_DIR = 'frontend_view_dir';
    const ON_SAVE = 'on_save';
    public $file_attributes;
    public $is_encrypt = false;


    /** @method addFileAttribute
     * @param $attribute
     * @param array $prefixes
     * @param $file_save_dir
     * @param $file_view_dir
     * @param $backend_view_dir
     * @param $frontend_view_dir
     * @param $file_view_url
     * @param callable $on_save
     * @param bool $is_encrypt
     */
    public function addFileAttribute($attribute,array $prefixes,$file_save_dir,$file_view_dir,$backend_view_dir,$frontend_view_dir,$file_view_url, callable $on_save = null,$is_encrypt = false) {
        $this->file_attributes[$attribute][self::FILE_SAVE_DIR] = $file_save_dir;
        $this->file_attributes[$attribute][self::PREFIXES] = $prefixes;
        $this->file_attributes[$attribute][self::FILE_VIEW_DIR] = $file_view_dir;
        $this->file_attributes[$attribute][self::FILE_VIEW_URL] = $file_view_url;
        $this->file_attributes[$attribute][self::BACKEND_VIEW_DIR] = $backend_view_dir;
        $this->file_attributes[$attribute][self::FRONTEND_VIEW_DIR] = $frontend_view_dir;
        $this->file_attributes[$attribute][self::ON_SAVE] = $on_save;
        $this->is_encrypt = $is_encrypt;
    }



    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE=> 'afterSave',
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete'
        ];
    }

    public function afterDeleteProcess($attribute) {
        FileHelper::removeDirectory($this->getFileSavePath($attribute));
    }

    public function afterDelete() {
        foreach($this->file_attributes as $file_attribute => $property) {
            self::afterDeleteProcess($file_attribute);
        }
    }

    public function preValidationProcess($attribute) {
        $this->file_attributes[$attribute][self::INSTANCE] = UploadedFile::getInstance($this->owner,$attribute);
        if($this->file_attributes[$attribute][self::INSTANCE] instanceof UploadedFile)
        {
            if(!$this->owner->isNewRecord)
            {

                self::deleteOldFile($this->getFileSavePath($attribute). $this->owner->oldAttributes[$attribute]);
                foreach(self::getPrefixes($attribute) as $prefix) {
                    self::deleteOldFile($this->getFileSavePath($attribute). $prefix . $this->owner->oldAttributes[$attribute]);
                }
            }
            $this->owner->$attribute = $this->getFileName($attribute) . $this->file_attributes[$attribute][self::INSTANCE]->extension;
        }
        else
        {
            if(isset($this->owner->oldAttributes[$attribute]) && $this->owner->oldAttributes)
                $this->owner->$attribute = $this->owner->oldAttributes[$attribute];
        }
    }

    protected function deleteOldFile($file_path) {
        if(is_file($file_path))
        {
            unlink($file_path);
        }
    }

    public function beforeValidate($event)
    {
        foreach($this->file_attributes as $file_attribute => $property) {
            self::preValidationProcess($file_attribute);
        }
    }

    public function postSavingProcess($attribute) {
        if(isset(self::getFileAttributeParams($attribute)[self::INSTANCE]))
            if($this->file_attributes[$attribute][self::INSTANCE] instanceof UploadedFile)
            {
                if(!is_dir($this->getFileSavePath($attribute)))
                {
                    FileHelper::createDirectory($this->getFileSavePath($attribute));
                }
                if(!Helper::_is_link($this->getBackendViewDir($attribute)))
                {
                    if(is_dir($this->getBackendViewDir($attribute)))
                    {
                        FileHelper::removeDirectory($this->getBackendViewDir($attribute));
                    }
                    symlink($this->getFileSaveDir($attribute), $this->getBackendViewDir($attribute));
                }
                if(!Helper::_is_link($this->getFrontendViewDir($attribute)))
                {
                    if(is_dir($this->getFrontendViewDir($attribute)))
                    {
                        FileHelper::removeDirectory($this->getFrontendViewDir($attribute));
                    }
                    symlink($this->getFileSaveDir($attribute), $this->getFrontendViewDir($attribute));
                }
                if($this->file_attributes[$attribute][self::INSTANCE]->saveAs($this->getFileSavePath($attribute) . $this->owner->$attribute))
                {
                    if(isset(self::getFileAttributeParams($attribute)[self::ON_SAVE]) && is_callable(self::getFileAttributeParams($attribute)[self::ON_SAVE]))
                    {
                        call_user_func_array(
                            self::getFileAttributeParams($attribute)[self::ON_SAVE],
                            [$attribute, $this->getFileSavePath($attribute) . $this->owner->$attribute]);
                    }
                }
            }
    }

    public function afterSave($event)
    {
        foreach($this->file_attributes as $file_attribute => $property) {
            self::postSavingProcess($file_attribute);
        }
    }

    public function getFileInstance($file_attribute) {
        if(isset($this->file_attributes[$file_attribute]))
            return $this->file_attributes[$file_attribute][self::INSTANCE];
        else return null;
    }

    public function getFileAttributeParams($file_attribute) {
        if(isset($this->file_attributes[$file_attribute])) {
            return $this->file_attributes[$file_attribute];
        }
        else return [];
    }


    public function getBackendViewDir($file_attribute) {
        $path = null;
        if(isset(self::getFileAttributeParams($file_attribute)[self::BACKEND_VIEW_DIR]))
            $path =  Yii::getAlias(self::getFileAttributeParams($file_attribute)[self::BACKEND_VIEW_DIR]);
        return $path;
    }

    public function getFrontendViewDir($file_attribute) {
        $path = null;
        if(isset(self::getFileAttributeParams($file_attribute)[self::FRONTEND_VIEW_DIR]))
            $path =  Yii::getAlias(self::getFileAttributeParams($file_attribute)[self::FRONTEND_VIEW_DIR]);
        return $path;
    }

    /**@method getFileSaveDir
     * @param $file_attribute
     * @return bool|string
     */
    public function getFileSaveDir($file_attribute)
    {
        $path = null;
        if(isset(self::getFileAttributeParams($file_attribute)[self::FILE_SAVE_DIR]))
        {
            $path = Yii::getAlias(self::getFileAttributeParams($file_attribute)[self::FILE_SAVE_DIR]);
            return $path;
        }
        else throw new InvalidParamException();
    }

    public function getPrefixes($file_attribute) {
        if(isset(self::getFileAttributeParams($file_attribute)[self::PREFIXES]))
        {
            $prefixes = self::getFileAttributeParams($file_attribute)[self::PREFIXES];
            return $prefixes;
        }
        else return [];
    }


    /**@method getFileViewDir
     * @param $file_attribute
     * @return bool|string
     */
    public function getFileViewDir($file_attribute)
    {
        $path = null;
        if(isset(self::getFileAttributeParams($file_attribute)[self::FILE_VIEW_DIR]))
        {
            $path = Yii::getAlias(self::getFileAttributeParams($file_attribute)[self::FILE_VIEW_DIR]);
            return $path;
        }
        else throw new InvalidParamException();
    }


    /**@method getFileViewUrl
     * @param $file_attribute
     * @return bool|string
     */
    public function getFileViewUrl($file_attribute)
    {
        $path = null;
        if(isset(self::getFileAttributeParams($file_attribute)[self::FILE_VIEW_URL]))
        {
            $path = Yii::getAlias(self::getFileAttributeParams($file_attribute)[self::FILE_VIEW_URL]);
            return $path;
        }
        else throw new InvalidParamException();
    }


    /** @method getFileSavePath
     * @param $file_attribute
     * @return string
     */
    public function getFileSavePath($file_attribute)
    {
        $path = null;
        $path = self::getFileSaveDir($file_attribute) . self::getObjectDir() . DIRECTORY_SEPARATOR;
        return $path;
    }

    public function getObjectDir() {
        if($this->is_encrypt)
            return Encryption::encode($this->owner->id);
        else return $this->owner->id;
    }


    /** @method getFileViewPath
     * @param $file_attribute
     * @return string
     */
    public function getFileViewPath($file_attribute)
    {
        $path = null;
        $path =  self::getFileViewUrl($file_attribute) . '/' .  self::getObjectDir() . '/';
        return $path;
    }


    /** @method getFileViewPath
     * @param $file_attribute
     * @param string $prefix
     * @return string
     */
    public function getFile($file_attribute,$prefix = "")
    {
        $path = null;
        $path = self::getFileViewPath($file_attribute) . $prefix . $this->owner->$file_attribute;
        return $path;
    }


    /** @method getFileName
     * @param $file_attribute
     * @return string
     */
    public function getFileName($file_attribute)
    {
        $name = Yii::$app->security->generateRandomString(16) . '.';
        return $name;
    }


    public function saveFiles() {
        $this->owner->id = Yii::$app->security->generateRandomString(8);
        foreach($this->file_attributes as $file_attribute => $property) {
            self::preValidationProcess($file_attribute);
            self::postSavingProcess($file_attribute);
        }
    }


}