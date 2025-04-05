<?php

use yii\db\Migration;

class m250404_172629_reply_deny extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('replies', 'is_denied', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250404_172629_reply_deny cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250404_172629_reply_deny cannot be reverted.\n";

        return false;
    }
    */
}
