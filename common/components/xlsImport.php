<?php
namespace common\components;
use common\controllers\MainController;
use InvalidArgumentException;
use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * Created by JetBrains PhpStorm.
 * User: DezMonT
 * Date: 13.08.14
 * Time: 23:09
 * To change this template use File | Settings | File Templates.
 * @property MainController $controller
 * @method saveByMode($model)
 */



class xlsImport extends Component
{

    const SAVE_BEHAVIOR = 'saveBehavior';

    const XLS_IMPORT = 'xlsimport';

    public function behaviors()
    {
        return [
          self::SAVE_BEHAVIOR => [
              'class'=>xlsSaveBehavior::className()
          ]
        ];
    }



    protected $base_url;

    protected  $ignore_attributes = array();

    protected  $additional_fields = [];

    protected $file_reader;

    protected $file_name;

    protected $file_size;

    protected $file_type = null;

    protected $file_reader_params;

    protected $controller;

    public function setController($controller)
    {
        $this->controller = $controller;
    }

    public  $errors;

    private $error;

    public  $warnings;

    private $transaction;

    private $is_update = false;

    public $modelName;

    private $xls_reader_class;

    public $file_model;

    public $file_attribute;

    public $old_model_find_attributes = [];

    /** @var  FieldWrap[] a field wrap array
     */
    protected $field_wrap_array;

    public function __construct(&$controller,$base_url,$modelName, $xls_reader_class,$file_model,$file_attribute,$is_update)
    {
        Yii::setAlias('@xls_save_dir','@app/files');
        $this->controller = $controller;
        $this->modelName = $modelName;
        $this->base_url = $base_url;
        $this->xls_reader_class = $xls_reader_class;
        $this->error = null;
        $this->errors = array();
        $this->warnings = array();
        $this->file_model = $file_model;
        $this->file_attribute = $file_attribute;
        $this->is_update = $is_update;
        $this->attachBehaviors($this->behaviors());

    }

    public function oldModelFindAttributes($attributes) {
        $this->old_model_find_attributes = array_flip($attributes);
    }


    public   function ignoreAttributes($attributes)
    {
        $this->ignore_attributes = array_flip($attributes);
    }

    public function additionalAttributes($attributes)
    {
        $this->additional_fields = $attributes;
    }

    public function setFileReader(callable $file_reader,$params)
    {
        if(is_callable($file_reader))
        {
            $this->file_reader = $file_reader;
            $this->file_reader_params = $params;
        }
        else throw new InvalidArgumentException('File reader must be a correct callable!');
    }

    public function setFieldWrap($field_wrap_array)
    {
        foreach ($field_wrap_array as $attribute => $callable_wrap)
        {
            $this->field_wrap_array[$attribute] = new FieldWrap($callable_wrap);
        }
    }

    protected  function callFieldWrap($attribute,$params)
    {
        if(isset($this->field_wrap_array[$attribute]))
        {
            $field_wrap = $this->field_wrap_array[$attribute];
           return $field_wrap->callWithParams($params);
        }
        return array_shift($params);
    }

    protected function setIsUpdate()
    {
        if($this->isUpdate())
        {
            $attributes = $this->old_model_find_attributes;
            if(count($attributes)) {
                foreach($attributes as $attribute) {
                    if(isset($this->ignore_attributes[$attribute]) || array_key_exists($attribute, $this->ignore_attributes))
                    {
                        unset($this->ignore_attributes[$attribute]);
                    }
                }
            }
            else
            {
                if(isset($this->ignore_attributes['id']) || array_key_exists('id', $this->ignore_attributes))
                {
                    unset($this->ignore_attributes['id']);
                }
            }
        }
    }

    public function isUpdate()
    {
        return $this->is_update;
    }
    const XLS_FILE = 'xls_file';

    public function isFileValid()
    {
        $result = is_null($this->file_type) || isset(self::$types[$this->file_type]) || array_key_exists($this->file_type,self::$types);
        return $result;
    }

    public static $types = [
        'application/vnd.ms-excel' => true,
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => true,
    ];



    public function prepareFile()
    {
        $xls_file = UploadedFile::getInstance($this->file_model,$this->file_attribute);
        if($xls_file instanceof UploadedFile)
        {
            $this->file_name = Yii::getAlias('@file_save_dir').'xls-import.'.$xls_file->extension;
            if(!is_dir(Yii::getAlias('@file_save_dir')))
              FileHelper::createDirectory(Yii::getAlias('@file_save_dir'));
            $xls_file->saveAs($this->file_name);
            $this->file_size = $xls_file->size;
            $this->file_type = $xls_file->type;
            if(!$this->isFileValid())
            {
                Alert::addError(Yii::t('messages','Invalid filetype'));
               return false;
            }
            set_time_limit(0);
        }
        else
        {
            Alert::addError(Yii::t('messages','Nothing to upload'));
            return false;
        }
        return true;
    }

    public function run()
    {
        if(!self::prepareFile())
            return false;
        self::setIsUpdate();
        $this->transaction = Yii::$app->getDb()->beginTransaction();
        $this->xlsRowToDb();
        if(count($this->errors) || $this->error)
        {
            $this->transaction->rollback();
            $error_array = array();
            if(count($this->errors))
            {
                $error_array[] = $this->errors;
            }
            if(count($this->error))
            {
                $error_array[] = $this->error;
            }
            Alert::addError(Yii::t('messages', 'Your request ends with errors'), $error_array);
            return $this->controller->redirect(Url::to([$this->base_url]));
        }
        else
        {
            $this->transaction->commit();
            if(count($this->warnings))
            {
                Alert::addWarning(Yii::t('messages', 'Your request ends with warnings'), $this->warnings);
            }
            return $this->controller->redirect(Url::to([$this->base_url]));
        }

    }



    protected function getAttributes($model_attributes)
    {
        $model_attributes = array_flip($model_attributes);
        foreach ($this->ignore_attributes as $ignore=>$value)
        {
            if(isset($model_attributes[$ignore]) || array_key_exists($ignore,$model_attributes))
                unset($model_attributes[$ignore]);
        }
        foreach ($this->additional_fields as $ignore=>$value)
        {
            if(isset($model_attributes[$ignore]) || array_key_exists($ignore,$model_attributes))
                unset($model_attributes[$ignore]);
        }
        return array_keys($model_attributes);
    }

    protected function getAttributesString($model_attributes)
    {
        return implode(',',self::getAttributes($model_attributes));
    }

    public function findOldModel($model) {
        $modelName = $this->modelName;
        $condition = [];
        $old_model_find_attributes = array_flip($this->old_model_find_attributes);
        foreach($old_model_find_attributes as $attribute) {
            $condition[$attribute] = $model->$attribute;
        }
        $old_model = $modelName::findOne($condition);
        return $old_model;
    }

    protected function xlsRowToDb()
    {
        /**@var XReaderInterface $reader*/
        $xls_reader_class = $this->xls_reader_class;
        $reader = new $xls_reader_class($this->file_name);
        $data = $reader->getData();
        $start_number = $reader->getStartPosition();
        if(!is_array($data))
            throw new Exception('Fatal error. Reader result must be an array');
        $title = array_shift($data);
        try
        {

            $attributes = self::validateData($title,$this->modelName);
            foreach ($data as $row)
            {
                $modelName = $this->modelName;
                if(self::checkIsEmptyRow($row))
                    continue;
                $model = new $modelName();
                $attributes_count = $start_number;
                $data  = array();
                foreach ($attributes as $key=>$value)
                {
                    if(!isset($row[$attributes_count]))
                        $row[$attributes_count] = null;
                    $row[$attributes_count] =  self::callFieldWrap($key,array($row[$attributes_count]));
                    $data[$key] = $row[$attributes_count];
                    $attributes_count++;
                }
                $model->attributes = $data;
                self::setAdditionalFields($model);
                $this->saveByMode($model);
            }
        }
        catch(Exception $e)
        {
            $this->errors[] = $e->getMessage();
        }

    }


    public function setAdditionalFields($model)
    {
        foreach($this->additional_fields as $key=>$value)
        {
               @$model->$key = $value;
        }
    }

    protected  function validateData($title,$modelName)
    {
        $model = new $modelName(['scenario'=>self::XLS_IMPORT]);
        $keys = self::getAttributes($model->safeAttributes());
        $title = array_values($title);
        $diff1 = array_diff($keys,$title);
        $diff2 = array_diff($title,$keys);
        if(count($diff1))
        {
            $this->errors[] = 'Attributes: ' .implode(',',$diff1) . " - are missing";
            throw new Exception(Yii::t('messages','You try to upload a wrong data-model. The title-row in your xls sheet must be : ').self::getAttributesString($model->safeAttributes()));
        }
        if(count($diff2))
        {
            $this->errors[] = 'Attributes: ' .implode(',',$diff2) . " - are missing";
            throw new Exception(Yii::t('messages','You try to upload a wrong data-model. The title-row in your xls sheet must be equal : ').self::getAttributesString($model->safeAttributes()));
        }
        $sk = serialize($keys);
        $st = serialize($title);
        if($sk != $st)
        {
            $this->errors['attributes'] = $keys;
            $this->errors['file'] = $title;
            throw new Exception(Yii::t('messages','You try to upload a wrong data-model. The title-row in your xls sheet must be equal to db fields.'));
        }
        return array_fill_keys($keys,null);

    }

    protected function  checkIsEmptyRow($row)
    {
        $array_walk = array_filter($row,function($var)
        {
            if(is_null($var))
                return false;
            else
                return true;
        });
        return empty($array_walk);
    }



    public  function saveModel($model)
    {
        if(!$model->save())
        {
            if($model->id)
                $this->errors[$model->id] = $model->errors;
            else
            {
                $this->errors[] = $model->errors;
            }
        }
    }
}