<?php
/**
 * @var User $model
 */

use app\helpers\UIHelper;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Профиль пользователя';
?>
<div class="left-column">
    <h3 class="head-main"><?= Html::encode($model->name) ?></h3>
    <div class="user-card">
        <div class="photo-rate">
            <img class="card-photo" src="<?= $model->avatar ?>" width="191" height="190" alt="Фото пользователя">
            <div class="card-rate">
                <?= UIHelper::showStarRating($model->getRating(), 'big') ?>
                <span class="current-rate"><?= $model->getRating() ?></span>
            </div>
        </div>
        <p class="user-description">
            <?= Html::encode($model->description) ?>
        </p>
    </div>
    <div class="specialization-bio">
        <div class="specialization">
            <p class="head-info">Специализации</p>
            <ul class="special-list">
                <?php foreach ($model->categories as $category): ?>
                    <li class="special-item">
                        <a href="#" class="link link--regular"><?= $category->name ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="bio">
            <p class="head-info">Био</p>
            <p class="bio-info"><span class="country-info">Россия</span>,
                <span class="town-info"><?= $model->city->name ?></span>, <?php if ($model->bd_date): ?><span class="age-info"><?= $model->getAge() ?></span> лет<?php endif;?></p>
        </div>
    </div>
    <h4 class="head-regular">Отзывы заказчиков</h4>
    <?php foreach ($model->opinions as $opinion): ?>
        <div class="response-card">
            <img class="customer-photo" src="<?= $opinion->owner->avatar ?>" width="120" height="127" alt="Фото заказчиков">
            <div class="feedback-wrapper">
                <p class="feedback">«<?=Html::encode($opinion->description) ?>»</p>
                <p class="task">Задание «<a href="<?= Url::to(['tasks/view', 'id' => $opinion->task_id]) ?>"
                                            class="link link--small"><?= Html::encode($opinion->task->name) ?></a>» выполнено</p>
            </div>
            <div class="feedback-wrapper">
                <?= UIHelper::showStarRating($opinion->rate) ?>
                <p class="info-text"><span class="current-time"><?= Yii::$app->formatter->asRelativeTime($opinion->dt_add) ?></span></p>
            </div>
        </div>
    <?php endforeach; ?>
    <!--<div class="response-card">
        <img class="customer-photo" src="/img/man-coat.png" width="120" height="127" alt="Фото заказчиков">
        <div class="feedback-wrapper">
            <p class="feedback">«Кумар сделал всё в лучшем виде. Буду обращаться к нему в
                будущем, если возникнет такая необходимость!»</p>
            <p class="task">Задание «<a href="#" class="link link--small">Повесить полочку</a>» выполнено</p>
        </div>
        <div class="feedback-wrapper">
            <div class="stars-rating small"><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span>&nbsp;</span></div>
            <p class="info-text"><span class="current-time">25 минут </span>назад</p>
        </div>
    </div>
    <div class="response-card">
        <img class="customer-photo" src="/img/man-sweater.png" width="120" height="127" alt="Фото заказчиков">
        <div class="feedback-wrapper">
            <p class="feedback">«Кумар сделал всё в лучшем виде. Буду обращаться к нему в
                будущем, если возникнет такая необходимость!»</p>
            <p class="task">Задание «<a href="#" class="link link--small">Повесить полочку</a>» выполнено</p>
        </div>
        <div class="feedback-wrapper">
            <div class="stars-rating small"><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span>&nbsp;</span></div>
            <p class="info-text"><span class="current-time">25 минут </span>назад</p>
        </div>
    </div>-->
</div>
<div class="right-column">
    <div class="right-card black">
        <h4 class="head-card">Статистика исполнителя</h4>
        <dl class="black-list">
            <dt>Всего заказов</dt>
            <dd><?= $model->getAssignedTasks()->count() ?> выполнено, <?= $model->fail_count ?> провалено</dd>
            <?php if ($position = $model->getRatingPosition()): ?>
                <dt>Место в рейтинге</dt>
                <dd><?= $position ?> место</dd>
            <?php endif; ?>
            <dt>Дата регистрации</dt>
            <dd><?= Yii::$app->formatter->asDate($model->dt_add) ?></dd>
            <dt>Статус</dt>
            <?php if (!$model->isBusy()): ?>
                <dd>Открыт для новых заказов</dd>
            <?php else: ?>
                <dd>Занят</dd>
            <?php endif; ?>
        </dl>
    </div>
    <div class="right-card white">
        <h4 class="head-card">Контакты</h4>
        <ul class="enumeration-list">
            <?php if ($model->phone): ?>
            <li class="enumeration-item">
                <a href="tel:<?= $model->phone ?>" class="link link--block link--phone"><?= $model->phone ?></a>
            </li>
            <?php endif; ?>
            <li class="enumeration-item">
                <a href="mailto:<?= $model->email ?>" class="link link--block link--email"><?= $model->email ?></a>
            </li>
            <?php if ($model->tg): ?>
            <li class="enumeration-item">
                <a href="https://t.me/<?= $model->tg ?>" class="link link--block link--tg"><?= $model->tg ?></a>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</div>
