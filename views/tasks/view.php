<?php
/**
 * @var Task $model
 */

use app\helpers\UIHelper;
use app\models\Opinion;
use app\models\Reply;
use app\models\Task;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Просмотр задания';

/**
 * @var User $user
 * @var View $this
 * @var Reply $newReply
 * @var Opinion $opinion
 */
$user = null;
$this->registerJsFile('/js/main.js');
?>
<div class="left-column">
    <div class="head-wrapper">
        <h3 class="head-main"><?= Html::encode($model->name) ?></h3>
        <p class="price price--big"><?= $model->budget ?></p>
    </div>
    <p class="task-description"><?= Html::encode($model->description) ?></p>
    <a href="#" class="button button--blue action-btn" data-action="act_response">Откликнуться на задание</a>
    <a href="#" class="button button--orange action-btn" data-action="refusal">Отказаться от задания</a>
    <a href="#" class="button button--pink action-btn" data-action="completion">Завершить задание</a>
    <div class="task-map">
        <img class="map" src="/img/map.png"  width="725" height="346" alt="Новый арбат, 23, к. 1">
        <p class="map-address town">Москва</p>
        <p class="map-address">Новый арбат, 23, к. 1</p>
    </div>
    <h4 class="head-regular">Отклики на задание</h4>
    <?php foreach ($model->getReplies($user)->all() as $reply): ?>
    <div class="response-card">
        <img class="customer-photo" src="<?= $reply->user->avatar ?>" width="146" height="156" alt="Фото заказчиков">
        <div class="feedback-wrapper">
            <a href="<?= Url::to(['user/view', 'id' => $reply->user_id]) ?>" class="link link--block link--big"><?= Html::encode($reply->user->name) ?></a>
            <div class="response-wrapper">
                <!--<div class="stars-rating small"><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span>&nbsp;</span></div>-->
                <?= UIHelper::showStarRating($reply->user->rating) ?>
                <?php $reviewsCount = $reply->user->getOpinions()->count(); ?>
                <p class="reviews"><?= Yii::t('app',
                        '{n,plural,=0{нет отзывов} one{# отзыв} few{# отзыва} other{# отзывов}}',
                        ['n' => $reviewsCount]); ?></p>
            </div>
            <p class="response-message">
                <?= Html::encode($reply->description) ?>
            </p>

        </div>
        <div class="feedback-wrapper">
            <p class="info-text"><span class="current-time"><?= Yii::$app->formatter->asRelativeTime($reply->dt_add) ?></p>
            <p class="price price--small"><?= $model->budget ?></p>
        </div>
        <div class="button-popup">
            <a href="#" class="button button--blue button--small">Принять</a>
            <a href="#" class="button button--orange button--small">Отказать</a>
        </div>
    </div>
    <?php endforeach; ?>
    <!--<div class="response-card">
        <img class="customer-photo" src="/img/man-glasses.png" width="146" height="156" alt="Фото заказчиков">
        <div class="feedback-wrapper">
            <a href="#" class="link link--block link--big">Астахов Павел</a>
            <div class="response-wrapper">
                <div class="stars-rating small"><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span>&nbsp;</span></div>
                <p class="reviews">2 отзыва</p>
            </div>
            <p class="response-message">
                Могу сделать всё в лучшем виде. У меня есть необходимый опыт и инструменты.
            </p>

        </div>
        <div class="feedback-wrapper">
            <p class="info-text"><span class="current-time">25 минут </span>назад</p>
            <p class="price price--small">3700 ₽</p>
        </div>
        <div class="button-popup">
            <a href="#" class="button button--blue button--small">Принять</a>
            <a href="#" class="button button--orange button--small">Отказать</a>
        </div>
    </div>
    <div class="response-card">
        <img class="customer-photo" src="/img/man-sweater.png" width="146" height="156" alt="Фото заказчиков">
        <div class="feedback-wrapper">
            <a href="#" class="link link--block link--big">Дмитриев Андрей</a>
            <div class="response-wrapper">
                <div class="stars-rating small"><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span>&nbsp;</span></div>
                <p class="reviews">8 отзывов</p>
            </div>
            <p class="response-message">
                Примусь за выполнение задания в течение часа, сделаю быстро и качественно.
            </p>

        </div>
        <div class="feedback-wrapper">
            <p class="info-text"><span class="current-time">2 часа </span>назад</p>
            <p class="price price--small">1999 ₽</p>
        </div>
        <div class="button-popup">
            <a href="#" class="button button--blue button--small">Принять</a>
            <a href="#" class="button button--orange button--small">Отказать</a>
        </div>
    </div>-->
</div>
<div class="right-column">
    <div class="right-card black info-card">
        <h4 class="head-card">Информация о задании</h4>
        <dl class="black-list">
            <dt>Категория</dt>
            <dd><?= $model->category->name ?></dd>
            <dt>Дата публикации</dt>
            <dd><?= Yii::$app->formatter->asRelativeTime($model->dt_add) ?></dd>
            <dt>Срок выполнения</dt>
            <dd><?= Yii::$app->formatter->asDatetime($model->expire_dt) ?></dd>
            <dt>Статус</dt>
            <dd><?= $model->status->name ?></dd>
        </dl>
    </div>
    <div class="right-card white file-card">
        <h4 class="head-card">Файлы задания</h4>
        <ul class="enumeration-list">
            <li class="enumeration-item">
                <a href="#" class="link link--block link--clip">my_picture.jpg</a>
                <p class="file-size">356 Кб</p>
            </li>
            <li class="enumeration-item">
                <a href="#" class="link link--block link--clip">information.docx</a>
                <p class="file-size">12 Кб</p>
            </li>
        </ul>
    </div>
</div>
