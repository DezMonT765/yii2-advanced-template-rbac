<?php

namespace common\models;

use common\components\Alert;
use common\components\xlsImport;
use Yii;
use yii\i18n\DbMessageSource;
use yii\web\BadRequestHttpException;

/**
 * This is the model class for table "source_message".
 *
 * @property integer $id
 * @property string $category
 * @property string $message
 * @property string $messageTranslation
 *
 * @property Message[] $messages
 */
class SourceMessage extends MainActiveRecord
{

    public $language;

    public function getMessageTranslation()
    {
        if(!empty($this->messageTranslation))
            return $this->messageTranslation;
        else
        {
            $translation = isset($this->messages[0]) ? $this->messages[0]->translation : null;
            return $translation;
        }
    }


    public function setMessageTranslation($translation)
    {
        $this->attributes;
        $this->messageTranslation = $translation;
    }

    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            xlsImport::XLS_IMPORT => ['id','category','message','messageTranslation','language']
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        $messageSource = Yii::$app->i18n->getMessageSource('*');
        if($messageSource instanceof DbMessageSource)
        {
            return $messageSource->sourceMessageTable;
        }
        else return 'source_message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category','message','messageTranslation'],'required'],
            [['message'], 'string'],
            [['category'], 'string', 'max' => 32],
            ['language','safe']
        ];
    }

    public function beforeSave($insert)
    {
        parent::beforeSave($insert);
        self::initLocalTransaction();
        return true;
    }

    public function afterSave($insert,$changedAttributes)
    {
        $this->activeAttributes();
        if($insert)
        {
            $message = new Message();
        }
        else
        {
            $message = Message::findOne(['id'=>$this->id,'language'=>$this->language]);
            if(!($message instanceof Message))
            {
                $message = new Message();
            }
        }
        $message->id = $this->id;
        $message->language = $this->language;
        $message->translation = $this->messageTranslation;
        if($message->save())
        {
            Alert::addSuccess(Yii::t('app',':source_message_saved'));
            self::commitLocalTransaction();
            return true;

        }
        Alert::addError(Yii::t('app',':source_message_not_saved'),$message->errors);
        self::rollbackLocalTransaction();
        throw new BadRequestHttpException(Alert::popError());
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category' => Yii::t('app', ':source_message_category'),
            'message' => Yii::t('app', ':source_message_message'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['id' => 'id'])->from(Message::tableName(). ' messages');
    }



}
