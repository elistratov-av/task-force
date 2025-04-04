<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\web\UploadedFile;

/**
 * This is the model class for table "files".
 *
 * @property int $id
 * @property string $name
 * @property string $path
 * @property int $task_id
 * @property int $user_id
 * @property string $dt_add
 *
 * @property Task $task
 * @property User $user
 */
class File extends \yii\db\ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $file;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'files';
    }

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => null
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'path', 'task_uid'], 'required'],
            [['task_id'], 'integer'],
            [['name', 'path', 'task_id', 'task_uid', 'dt_add'], 'safe'],
            [['name', 'path'], 'string', 'max' => 255],
            [['path'], 'unique'],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::class, 'targetAttribute' => ['task_id' => 'id']],
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'path' => 'Path',
            'task_id' => 'Task ID',
            'user_id' => 'User ID',
            'dt_add' => 'Dt Add',
        ];
    }

    public function upload()
    {
        $this->name = $this->file->name;
        $newname = uniqid() . '.' . $this->file->getExtension();
        $this->path = '/uploads/' . $newname;
        $this->size = $this->file->size;

        if ($this->save()) {
            return $this->file->saveAs('@webroot/uploads/' . $newname);
        }

        return false;
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTaskByUid()
    {
        return $this->hasOne(Task::class, ['uid' => 'task_uid']);
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

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}
