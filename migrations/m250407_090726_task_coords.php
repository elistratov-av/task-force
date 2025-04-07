<?php

use yii\db\Migration;

class m250407_090726_task_coords extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tasks', 'lat', $this->decimal(9, 7));
        $this->addColumn('tasks', 'long', $this->decimal(9, 7));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250407_090726_task_coords cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250407_090726_task_coords cannot be reverted.\n";

        return false;
    }
    */
}
