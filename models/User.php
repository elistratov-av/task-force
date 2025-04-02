<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $email
 * @property string $name
 * @property int $city_id
 * @property string $password
 * @property string $dt_add
 * @property int $blocked
 * @property string|null $last_activity
 * @property string $avatar
 * @property string $bd_date
 * @property string $description
 * @property int $fail_count
 * @property string $phone
 * @property string $tg
 * @property boolean $hide_contacts
 * @property boolean $is_contractor
 *
 * @property City $city
 * @property File[] $files
 * @property Opinion[] $opinions
 * @property Reply[] $replies
 * @property Category[] $categories
 * @property UserSettings $userSettings
 */
class User extends BaseUser implements IdentityInterface
{
    public $password_repeat;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dt_add', 'last_activity', 'password_repeat', 'categories'/*, 'old_password', 'new_password', 'new_password_repeat'*/], 'safe'],
            [['email', 'name'], 'required'],
            [['city_id', 'password'], 'required', 'on' => 'insert'],
            [['password'], 'compare', 'on' => 'insert'],
            [['is_contractor', 'hide_contacts'], 'boolean'],
            [['last_activity'], 'default', 'value' => null],
            [['blocked'], 'default', 'value' => 0],
            [['city_id', 'blocked'], 'integer'],
            [['bd_date'], 'date', 'format' => 'php:Y-m-d',],
            [['phone'], 'match', 'pattern' => '/^[+-]?\d{11}$/', 'message' => 'Номер телефона должен быть строкой в 11 символов'],
            [['phone'], 'number'],
            [['email', 'name'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['password', 'tg'], 'string', 'max' => 64],
            [['email'], 'unique'],
            [['description'], 'string'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
        ];

/*        return [
            [['new_password'], 'required', 'when' => function ($model) {
                return $model->old_password;
            }],
            [['avatarFile'], 'file', 'mimeTypes' => ['image/jpeg', 'image/png'], 'extensions' => ['png', 'jpg', 'jpeg']],
            [['new_password'], 'compare', 'on' => 'update'],
            [['is_contractor', 'hide_contacts'], 'boolean'],
            ['old_password', 'newPasswordValidation'],
        ];*/
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'name' => 'Имя',
            'city_id' => 'Город',
            'password' => 'Пароль',
            'dt_add' => 'Dt Add',
            'blocked' => 'Blocked',
            'last_activity' => 'Last Activity',
            'categories' => 'Выбранные категории',
/*            'old_password' => 'Старый пароль',
            'new_password' => 'Новый пароль',*/
            'password_repeat' => 'Повтор пароля',
//            'new_password_repeat' => 'Повтор пароля',
            'hide_contacts' => 'Показывать контакты только заказчику',
            'bd_date' => 'Дата рождения',
            'phone' => 'Номер телефона',
            'description' => 'Информация о себе',
            'tg' => 'Telegram',
            'is_contractor' => 'я собираюсь откликаться на заказы'
        ];
    }

    public function isBusy()
    {
        return $this->getAssignedTasks()->joinWith('status', true, 'INNER JOIN')->where(['statuses.id' => Status::STATUS_IN_PROGRESS])->exists();
    }

    public function isContactsAllowed(IdentityInterface $user)
    {
        $result = true;

        if (true/*$this->hide_contacts*/) {
            $result = $this->getAssignedTasks($user)->exists();
        }

        return $result;
    }

    public function getRating()
    {
        $rating = null;

        $opinionsCount = $this->getOpinions()->count();

        if ($opinionsCount) {
            $ratingSum = $this->getOpinions()->sum('rate');
            $failCount = 0;//$this->fail_count;
            $rating = round(intdiv($ratingSum, $opinionsCount + $failCount), 2);
        }

        return $rating;
    }

    public function getRatingPosition()
    {
        $result = null;

        $sql = "SELECT u.id, (SUM(o.rate) / (COUNT(o.id) + u.fail_count)) as rate FROM users u
                LEFT JOIN opinions o on u.id = o.performer_id
                GROUP BY u.id
                ORDER BY rate DESC";

        $records = Yii::$app->db->createCommand($sql)->queryAll(\PDO::FETCH_ASSOC);
        $index = array_search($this->id, array_column($records, 'id'));

        if ($index !== false) {
            $result = $index + 1;
        }

        return $result;
    }

    public function getAge()
    {
        $result = null;

        if ($this->bd_date) {
            $bd = new \DateTime($this->bd_date);
            $now = new \DateTime();
            $diff = $now->diff($bd);
            $result = $diff->y;
        }

        return $result;
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Files]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Opinions0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOpinions()
    {
        return $this->hasMany(Opinion::class, ['performer_id' => 'id']);
    }

    /**
     * Gets query for [[Replies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReplies()
    {
        return $this->hasMany(Reply::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Files]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAssignedTasks()
    {
        return $this->hasMany(Task::class, ['performer_id' => 'id']);
    }

    /**
     * Gets query for [[Categories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])->viaTable('user_categories', ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UserSetting]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserSettings()
    {
        return $this->hasOne(UserSettings::class, ['user_id' => 'id']);
    }

}
