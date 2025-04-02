<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "opinions".
 *
 * @property int $id
 * @property int $owner_id
 * @property int $performer_id
 * @property int $task_id
 * @property int $rate
 * @property string $description
 * @property string|null $dt_add
 *
 * @property User $owner
 * @property User $performer
 */
class Opinion extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'opinions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['owner_id', 'performer_id', 'rate', 'description'], 'required'],
            [['owner_id', 'performer_id', 'rate'], 'integer'],
            [['description'], 'string'],
            [['dt_add'], 'safe'],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['owner_id' => 'id']],
            [['performer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['performer_id' => 'id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::class, 'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'owner_id' => 'Owner ID',
            'performer_id' => 'Performer ID',
            'rate' => 'Rate',
            'description' => 'Description',
            'dt_add' => 'Dt Add',
        ];
    }

    /**
     * Gets query for [[Owner]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOwner()
    {
        return $this->hasOne(User::class, ['id' => 'owner_id']);
    }

    /**
     * Gets query for [[Performer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPerformer()
    {
        return $this->hasOne(User::class, ['id' => 'performer_id']);
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }

}
