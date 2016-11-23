<?php

namespace common\models;

use dezmont765\yii2bundle\models\MainActiveRecord;
use Yii;
use yii\i18n\DbMessageSource;

/**
 * This is the model class for table "message".
 *
 * @property integer $id
 * @property string $language
 * @property string $translation
 *
 * @property SourceMessage $sourceMessage
 */
class Message extends MainActiveRecord
{
    const PAGE_COUNT = 10;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        $messageSource = Yii::$app->i18n->getMessageSource('*');
        if($messageSource instanceof DbMessageSource)
        {
            return $messageSource->messageTable;
        }
        else return 'messages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'language'], 'required'],
            [['id'], 'integer'],
            [['translation'], 'string'],
            [['language'], 'string', 'max' => 16]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'language' => Yii::t('app', 'Language'),
            'translation' => Yii::t('app', 'Translation'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSourceMessage()
    {
        return $this->hasOne(SourceMessage::className(), ['id' => 'id'])
            ->from([SourceMessage::tableName() .' sourceMessage']);
    }


}
