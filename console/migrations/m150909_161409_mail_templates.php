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
    }

    public function down()
    {
        $this->dropTable('{{%mail_templates}}');
    }
}
