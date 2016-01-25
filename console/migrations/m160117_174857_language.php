<?php

use yii\db\Schema;
use yii\db\Migration;

class m160117_174857_language extends Migration
{
    public function safeUp()
    {
        $sql = file_get_contents(__DIR__ . '/language.sql');
        $this->execute($sql);
    }

    public function safeDown()
    {
        $this->dropTable('language');
    }

}
