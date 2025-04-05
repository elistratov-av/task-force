<?php

use yii\db\Migration;

class m250404_181413_status_slug extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('statuses', 'slug', $this->char(16));

        $this->update('statuses', ['slug' => 'new'], ['id' => 1]);
        $this->update('statuses', ['slug' => 'cancel'], ['id' => 2]);
        $this->update('statuses', ['slug' => 'proceed'], ['id' => 3]);
        $this->update('statuses', ['slug' => 'complete'], ['id' => 4]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250404_181413_status_slug cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250404_181413_status_slug cannot be reverted.\n";

        return false;
    }
    */
}
