<?php

use yii\db\Migration;

class m250404_093431_file_uid extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tasks', 'uid', $this->char(64)->unique());
        $this->addColumn('files', 'task_uid', $this->char(64));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250404_093431_file_uid cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250404_093431_file_uid cannot be reverted.\n";

        return false;
    }
    */
}
