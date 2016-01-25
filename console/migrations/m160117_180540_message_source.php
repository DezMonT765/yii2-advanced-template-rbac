<?php

use yii\db\Schema;
use yii\db\Migration;

class m160117_180540_message_source extends Migration
{
    public function safeUp()
    {
        $sql = file_get_contents(__DIR__ . '/source_message.sql');
        $this->execute($sql);
    }

    public function safeDown()
    {
        $this->dropTable('source_message');
    }
}
