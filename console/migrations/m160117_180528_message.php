<?php

use yii\db\Schema;
use yii\db\Migration;

class m160117_180528_message extends Migration
{
    public function safeUp()
    {
        $sql = file_get_contents(__DIR__ . '/message.sql');
        $this->execute($sql);
    }

    public function safeDown()
    {
        $this->dropTable('message');
    }
}
