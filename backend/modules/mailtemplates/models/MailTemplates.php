<?php

namespace dezmont765\MailTemplatesModule\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "mail_templates".
 *
 * @property integer $id
 * @property string $template_type
 * @property string $subject
 * @property string $template
 */
class MailTemplates extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mail_templates';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['template_type','unique'],
            [['template_type','subject', 'template'], 'required'],
            [['template'], 'string'],
            [['template_type'], 'string', 'max' => 50],
            [['subject'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'template_type' => 'Template Type',
            'subject' => 'Subject',
            'template' => 'Template',
        ];
    }
}
