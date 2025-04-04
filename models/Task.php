<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property string $name
 * @property int $category_id
 * @property string $description
 * @property string|null $location
 * @property int|null $budget
 * @property string|null $expire_dt
 * @property string|null $dt_add
 * @property int $client_id
 * @property int|null $performer_id
 * @property int $status_id
 *
 * @property Category $category
 * @property File[] $files
 * @property Reply[] $replies
 * @property Status $status
 */
class Task extends \yii\db\ActiveRecord
{
    public $noPerformer;
    public $filterPeriod;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks';
    }

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'client_id',
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
            [['location', 'budget', 'expire_dt', 'performer_id'], 'default', 'value' => null],
            [['status_id'], 'default', 'value' => function($model, $attr) {
                return Status::find()->select('id')->where('id=1')->scalar();
            }],
/*            [['city_id'], 'default', 'value' => function($model, $attr) {
                if ($model->location) {
                    return \Yii::$app->user->getIdentity()->city_id;
                }

                return null;
            }],*/
            [['category_id', 'budget', 'performer_id', 'status_id'], 'integer'],
            [['description'], 'string'],
            [['name', 'category_id', 'description', 'status_id', 'expire_dt', 'dt_add', 'noPerformer', 'filterPeriod'], 'safe'],
            [['name', 'location'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::class, 'targetAttribute' => ['status_id' => 'id']],
            [['noPerformer'], 'boolean'],
            [['filterPeriod'], 'number'],

            [['budget'], 'integer', 'min' => 1],
            [['expire_dt'], 'date', 'format' => 'php:Y-m-d', 'min' => date('Y-m-d'), 'minString' => 'чем текущий день'],
            [['name', 'category_id', 'description', 'status_id'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'category_id' => 'Категория',
            'description' => 'Описание',
            'location' => 'Место',
            'budget' => 'Бюджет',
            'expire_dt' => 'Крайний срок',
            'dt_add' => 'Дата создания',
            'client_id' => 'Заказчик',
            'performer_id' => 'Исполнитель',
            'status_id' => 'Статус',
            'noPerformer' => 'Без исполнителя'
        ];
    }

    public function getSearchQuery()
    {
        $query = self::find();
        $query->where(['status_id' => Status::STATUS_NEW]);

        $query->andFilterWhere(['category_id' => $this->category_id]);

        if ($this->noPerformer) {
            $query->andWhere('performer_id IS NULL');
        }

        if ($this->filterPeriod) {
            $query->andWhere('UNIX_TIMESTAMP(tasks.dt_add) > UNIX_TIMESTAMP() - :period', [':period' => $this->filterPeriod]);
        }

        return $query->orderBy('dt_add DESC');
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
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
     * Gets query for [[Client]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(User::class, ['id' => 'client_id']);
    }

    /**
     * Gets query for [[Files]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Replies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReplies(IdentityInterface $user = null)
    {
        $allRepliesQuery = $this->hasMany(Reply::class, ['task_id' => 'id']);

        if ($user && $user->getId() !== $this->client_id) {
            $allRepliesQuery->where(['replies.user_id' => $user->getId()]);
        }

        return $allRepliesQuery;
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::class, ['id' => 'status_id']);
    }

}
