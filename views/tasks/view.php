<?php
/**
 * @var Task $model
 */

use app\assets\YandexAsset;
use app\helpers\UIHelper;
use app\models\Opinion;
use app\models\Reply;
use app\models\Task;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

$this->title = 'Просмотр задания';

/**
 * @var User $user
 * @var View $this
 * @var Reply $newReply
 * @var Opinion $opinion
 */
$user = Yii::$app->user->getIdentity();
$this->registerJsFile('/js/main.js');

YandexAsset::register($this);
?>
<div class="left-column">
    <div class="head-wrapper">
        <h3 class="head-main"><?= Html::encode($model->name) ?></h3>
        <p class="price price--big"><?= $model->budget ?></p>
    </div>
    <p class="task-description"><?= Html::encode($model->description) ?></p>

    <?php foreach (UIHelper::getActionButtons($model, $user) as $button): ?>
        <?= $button ?>
    <?php endforeach; ?>
    <!--<a href="#" class="button button--blue action-btn" data-action="act_response">Откликнуться на задание</a>
    <a href="#" class="button button--orange action-btn" data-action="refusal">Отказаться от задания</a>
    <a href="#" class="button button--pink action-btn" data-action="completion">Завершить задание</a>-->

    <?php if ($model->city): ?>
    <div class="task-map">
        <!--img class="map" id="map" src="/img/map.png"  width="725" height="346" alt="Новый арбат, 23, к. 1"-->
        <div id="map" style="width: 725px; height: 346px"></div>
        <p class="map-address town"><?= $model->city->name ?></p>
        <p class="map-address"><?= Html::encode($model->location) ?></p>
    </div>
    <?php
    $lat = $model->lat; $long = $model->long;
    $this->registerJs(<<<JS
    ymaps.ready(init);
    function init(){
        var myMap = new ymaps.Map("map", {
            center: ["$lat", "$long"],
            zoom: 16
        });
        
        myMap.controls.remove('trafficControl');
        myMap.controls.remove('searchControl');
        myMap.controls.remove('geolocationControl');
        myMap.controls.remove('typeSelector');
        myMap.controls.remove('fullscreenControl');
        myMap.controls.remove('rulerControl');
    }
JS, View::POS_READY);
    ?>
    <?php endif; ?>

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
        <?php if ($user->id === $model->client_id && !$reply->isHolded): ?>
        <div class="button-popup">
            <a href="<?= Url::to(['reply/approve', 'id' => $reply->id]) ?>" class="button button--blue button--small">Принять</a>
            <a href="<?= Url::to(['reply/deny', 'id' => $reply->id]) ?>" class="button button--orange button--small">Отказать</a>
        </div>
        <?php endif; ?>
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
            <?php foreach ($model->files as $file): ?>
                <li class="enumeration-item">
                    <a href="<?= $file->path ?>" class="link link--block link--clip"><?= $file->name ?></a>
                    <p class="file-size"><?= Yii::$app->formatter->asShortSize($file->size) ?></p>
                </li>
            <?php endforeach; ?>
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
<section class="pop-up pop-up--refusal pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Отказ от задания</h4>
        <p class="pop-up-text">
            <b>Внимание!</b><br>
            Вы собираетесь отказаться от выполнения этого задания.<br>
            Это действие плохо скажется на вашем рейтинге и увеличит счетчик проваленных заданий.
        </p>
        <a class="button button--pop-up button--orange" href="<?=Url::to(['tasks/deny', 'id' => $model->id]) ?>">Отказаться</a>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
<section class="pop-up pop-up--completion pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Завершение задания</h4>
        <p class="pop-up-text">
            Вы собираетесь отметить это задание как выполненное.
            Пожалуйста, оставьте отзыв об исполнителе и отметьте отдельно, если возникли проблемы.
        </p>
        <div class="completion-form pop-up--form regular-form">
            <?php $form = ActiveForm::begin([
                'action' => Url::to(['opinion/create', 'task' => $model->id]),
                'enableAjaxValidation' => true,
                'validationUrl' => ['opinion/validate'],
            ]); ?>
            <?= $form->field($opinion, 'description')->textarea() ?>
            <?= $form->field($opinion, 'rate', ['template' => '{label}{input}' . UIHelper::showStarRating(0, 'big', 5, true) . '{error}'])
                ->hiddenInput() ?>
            <input type="submit" class="button button--pop-up button--blue" value="Завершить">
            <?php ActiveForm::end(); ?>
            <!--<form>
                <div class="form-group">
                    <label class="control-label" for="completion-comment">Ваш комментарий</label>
                    <textarea id="completion-comment"></textarea>
                </div>
                <p class="completion-head control-label">Оценка работы</p>
                <div class="stars-rating big active-stars"><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span></div>
                <input type="submit" class="button button--pop-up button--blue" value="Завершить">
            </form>-->
        </div>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
<section class="pop-up pop-up--act_response pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Добавление отклика к заданию</h4>
        <p class="pop-up-text">
            Вы собираетесь оставить свой отклик к этому заданию.
            Пожалуйста, укажите стоимость работы и добавьте комментарий, если необходимо.
        </p>
        <div class="addition-form pop-up--form regular-form">
            <?php $form = ActiveForm::begin(['enableAjaxValidation' => true,
                    'validationUrl' => ['reply/validate', 'task' => $model->id],
                    'action' => Url::to(['reply/create', 'task' => $model->id])]
            );
            ?>
            <?= $form->field($newReply, 'description')->textarea() ?>
            <?= $form->field($newReply, 'budget') ?>
            <input type="submit" class="button button--pop-up button--blue" value="Отправить">
            <?php ActiveForm::end(); ?>
            <!--<form>
                <div class="form-group">
                    <label class="control-label" for="addition-comment">Ваш комментарий</label>
                    <textarea id="addition-comment"></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label" for="addition-price">Стоимость</label>
                    <input id="addition-price" type="text">
                </div>
                <input type="submit" class="button button--pop-up button--blue" value="Завершить">
            </form>-->
        </div>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
