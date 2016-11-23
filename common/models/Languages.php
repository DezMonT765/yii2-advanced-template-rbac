<?php
namespace common\models;

use dezmont765\yii2bundle\models\MainActiveRecord;
use Yii;

class Languages extends MainActiveRecord
{
    /**
     * Status of inactive language.
     */
    const STATUS_INACTIVE = 0;
    /**
     * Status of active language.
     */
    const STATUS_ACTIVE = 1;
    /**
     * Status of ‘beta’ language.
     */
    const STATUS_BETA = 2;
    /**
     * Array containing possible states.
     * @var array
     * @translate
     */
    private static $_CONDITIONS = [
        self::STATUS_INACTIVE => 'Inactive',
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_BETA => 'Beta',
    ];


    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'language';
    }


    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['language_id', 'language', 'country', 'name', 'name_ascii', 'status'], 'required'],
            [['language_id'], 'string', 'max' => 5],
            [['language_id'], 'unique'],
            [['language_id'], 'match', 'pattern' => '/^([a-z]{2}[_-][A-Z]{2}|[a-z]{2})$/'],
            [['language', 'country'], 'string', 'max' => 2],
            [['language', 'country'], 'match', 'pattern' => '/^[a-z]{2}$/i'],
            [['name', 'name_ascii'], 'string', 'max' => 32],
            [['status'], 'integer'],
            [['status'], 'in', 'range' => array_keys(Languages::$_CONDITIONS)]
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'language_id' => Yii::t('app', ':language_model_language_id'),
            'language' => Yii::t('app', ':language_model_language'),
            'country' => Yii::t('app', ':language_model_country'),
            'name' => Yii::t('app', ':language_model_name'),
            'name_ascii' => Yii::t('app', ':language_model_name_ascii'),
            'status' => Yii::t('app', ':language_model_status'),
        ];
    }


    /**
     * Returns the list of languages stored in the database in an array.
     * @param boolean $active True/False according to the status of the language.
     * @return array
     */
    public static function getLanguageNames($active = false) {
        $languageNames = [];
        foreach(self::getLanguages($active) as $language) {
            $languageNames[$language->language_id] = $language->name;
        }
        return $languageNames;
    }


    /**
     * Returns language objects.
     * @param boolean $active True/False according to the status of the language.
     * @return Languages|array
     */
    public static function getLanguages($active = true) {
        if($active) {
            return Languages::find()->where(['status' => static::STATUS_ACTIVE])->all();
        }
        else {
            return Languages::find()->all();
        }
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguageTranslate() {
        return $this->hasOne(Message::className(), ['language' => 'language_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIds() {
        return $this->hasMany(Message::className(), ['id' => 'id'])
                    ->viaTable(Message::tableName(), ['language' => 'language_id']);
    }
}