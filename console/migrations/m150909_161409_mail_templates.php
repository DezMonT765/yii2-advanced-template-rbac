<?php

use yii\db\Migration;
use yii\db\mysql\Schema;

class m150909_161409_mail_templates extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%mail_templates}}', [
            'id' => Schema::TYPE_PK,
            'template_type' => Schema::TYPE_STRING . '(50) NULL',
            'subject' => Schema::TYPE_STRING . '(150) NOT NULL',
            'template' => Schema::TYPE_TEXT,
        ], $tableOptions);
        $this->insert('{{%mail_templates}}',[
            'template_type' => 'email-verification',
            'subject' => 'Email Verification',
            'template' => '<p>Dear {user_name},</p>
                <p>Thank you for expressing your interest in our site. To finalize your request we need to verify your email address.</p>
                <p>Please <a href="{email_verification_url}">click here</a> to complete the registration process.</p>
                <p>Thank you again for your interest.</p>
                <p>Best regards,</p>
                <p>Advanced Template</p>
                <p>advanced-template.dev</p>'
        ]);

        $this->insert('{{%mail_templates}}',[
            'template_type' => 'welcome-mail',
            'subject' => 'Welcome',
            'template' => '<p>Dear {user_name},</p>
                <p>Thank you completing your registration with our site.</p>
                <p>Best regards,</p>
                <p>Advanced Template</p>
                <p>advanced-template.dev</p>'
        ]);

        $this->insert('{{%mail_templates}}', [
            'template_type' => 'password-token',
            'subject' => 'Reset password',
            'template' => '<p>Dear {user_name},</p>
                <p>we have received a request to reset your password. If you have initiated this request, please click on the following link to complete the process:</p>
                <p><a href="{password-reset-link}">{password-reset-link}</a></p>
                <p>In case you have not initiated this request, please ignore this message.</p>
                <p>Best regards,</p>
                <p>advanced-template.dev</p>'
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%mail_templates}}');
    }
}
