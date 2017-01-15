<?php
namespace common\models;
use dezmont765\yii2bundle\components\Alert;
use common\components\KReader;
use common\components\xlsImport;
use Yii;
use yii\base\Model;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 19.04.2015
 * Time: 14:21
 *
 */

class TranslationLoad extends Model
{

    public $file;
    public $language;
    public $isUpdate;



    public function rules()
    {
        return [
          [['language','isUpdate'],'required']
        ];
    }

    public function attributeLabels()
    {
        return [
          'isUpdate' => Yii::t('app',':translation_form_isUpdate'),
          'file' => Yii::t('app',':translation_form_file'),
        ];
    }


    public function loadTranslation()
    {
        if(!$this->validate())
        {
            Alert::addError('Translation has not been loaded',$this->errors);
            return false;
        }

        $xlsImport = new xlsImport(Yii::$app->controller, Yii::$app->request->referrer,SourceMessage::className(), KReader::className(),$this,'file',$this->isUpdate);
        $xlsImport->ignoreAttributes(['id']);
        $xlsImport->additionalAttributes(['category'=>'app']);
        $xlsImport->oldModelFindAttributes(['category','message']);
        $xlsImport->run();

        return true;
    }






}