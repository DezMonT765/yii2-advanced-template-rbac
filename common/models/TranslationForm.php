<?php
namespace common\models;
use common\components\Alert;
use Yii;
use yii\base\Model;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 16.04.2015
 * Time: 12:53
 */

class TranslationForm extends Model
{
    public $language;
    public $category;
    public $translation;
    public $source_message;

    public function rules()
    {
        return [
            ['category','default','value'=>'app'],
            [['language','category','translation','source_message'],'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'category' =>  Yii::t('app', ':translation_model_category'),
            'source_message' =>  Yii::t('app', ':translation_model_source_message'),
            'translation' =>  Yii::t('app', ':translation_model_translation'),
        ];
    }

    public function createMessage()
    {
        if(!$this->validate()){
            Alert::addError(Yii::t('app',':translation_error_translation_not_saved'),$this->errors);
            return false;
        }
        $sourceMessage = new SourceMessage();
        $sourceMessage->language = $this->language;
        $sourceMessage->category = $this->category;
        $sourceMessage->message = $this->source_message;
        $sourceMessage->messageTranslation = $this->translation;
        if(!$sourceMessage->save())
             Alert::addError(Yii::t('app',':translation_error_translation_not_saved'),$sourceMessage->errors);
        return false;
    }


}