<?php

use yii\db\Migration;

class m250407_090531_task_city extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tasks', 'city_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250407_090531_task_city cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250407_090531_task_city cannot be reverted.\n";

        return false;
    }
    */
}
