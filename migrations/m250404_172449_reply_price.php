<?php

use yii\db\Migration;

class m250404_172449_reply_price extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('replies', 'budget', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250404_172449_reply_price cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250404_172449_reply_price cannot be reverted.\n";

        return false;
    }
    */
}
