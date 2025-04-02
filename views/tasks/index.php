<?php
/**
 * @var Task[] $models
 * @var Task $task
 * @var View $this
 * @var Category[] $categories
 */

use app\models\Category;
use app\models\Task;
use yii\helpers\BaseStringHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

$this->title = 'Просмотр новых заданий';
?>
<div class="left-column">
    <h3 class="head-main head-task">Новые задания</h3>
    <?php foreach ($models as $model): ?>
    <div class="task-card">
        <div class="header-task">
            <a  href="#" class="link link--block link--big"><?= Html::encode($model->name) ?></a>
            <p class="price price--task"><?= $model->budget ?></p>
        </div>
        <p class="info-text"><?= Yii::$app->formatter->asRelativeTime($model->dt_add) ?></p>
        <p class="task-text"><?= Html::encode(BaseStringHelper::truncate($model->description, 200)) ?></p>
        <div class="footer-task">
            <?php if ($model->location): ?>
            <p class="info-text town-text"><?= $model->location ?></p>
            <?php endif; ?>
            <p class="info-text category-text"><?= $model->category->name ?></p>
            <a href="#" class="button button--black">Смотреть Задание</a>
        </div>
    </div>
    <?php endforeach; ?>
    <!--<div class="task-card">
        <div class="header-task">
            <a  href="#" class="link link--block link--big">Убраться в квартире после вписки</a>
            <p class="price price--task">4700 ₽</p>
        </div>
        <p class="info-text"><span class="current-time">4 часа </span>назад</p>
        <p class="task-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas varius tortor nibh, sit amet tempor
            nibh finibus et. Aenean eu enim justo. Vestibulum aliquam hendrerit molestie. Mauris malesuada nisi sit amet augue accumsan tincidunt.
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas varius tortor nibh, sit amet tempor
            nibh finibus et. Aenean eu enim justo. Vestibulum aliquam hendrerit molestie. Mauris malesuada nisi sit amet augue accumsan tincidunt.
        </p>
        <div class="footer-task">
            <p class="info-text town-text">Санкт-Петербург, Центральный район</p>
            <p class="info-text category-text">Переводы</p>
            <a href="#" class="button button--black">Смотреть Задание</a>
        </div>
    </div>
    <div class="task-card">
        <div class="header-task">
            <a  href="#" class="link link--block link--big">Перевезти груз на новое место</a>
            <p class="price price--task">18750 ₽</p>
        </div>
        <p class="info-text"><span class="current-time">4 часа </span>назад</p>
        <p class="task-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas varius tortor nibh, sit amet tempor
            nibh finibus et. Aenean eu enim justo. Vestibulum aliquam hendrerit molestie. Mauris malesuada nisi sit amet augue accumsan tincidunt.
        </p>
        <div class="footer-task">
            <p class="info-text town-text">Санкт-Петербург, Центральный район</p>
            <p class="info-text category-text">Переводы</p>
            <a href="#" class="button button--black">Смотреть Задание</a>
        </div>
    </div>-->
    <div class="pagination-wrapper">
        <ul class="pagination-list">
            <li class="pagination-item mark">
                <a href="#" class="link link--page"></a>
            </li>
            <li class="pagination-item">
                <a href="#" class="link link--page">1</a>
            </li>
            <li class="pagination-item pagination-item--active">
                <a href="#" class="link link--page">2</a>
            </li>
            <li class="pagination-item">
                <a href="#" class="link link--page">3</a>
            </li>
            <li class="pagination-item mark">
                <a href="#" class="link link--page"></a>
            </li>
        </ul>
    </div>
</div>
<div class="right-column">
    <div class="right-card black">
        <div class="search-form">
            <?php $form = ActiveForm::begin(); ?>
                <h4 class="head-card">Категории</h4>
                <div class="form-group">
                    <div class="checkbox-wrapper">
                        <?= Html::activeCheckboxList($task, 'category_id', array_column($categories, 'name', 'id'),
                        ['tag' => null, 'itemOptions' => ['labelOptions' => ['class' => 'control-label']]]) ?>
                    </div>
                </div>
                <h4 class="head-card">Дополнительно</h4>
                <div class="form-group">
                    <!--<label class="control-label" for="without-performer">
                        <input id="without-performer" type="checkbox" checked>
                        Без исполнителя</label>-->
                    <?=$form->field($task, 'noPerformer')->checkbox(['labelOptions' => ['class' => 'control-label']]); ?>
                </div>
                <h4 class="head-card">Период</h4>
                <div class="form-group">
                    <!--<label for="period-value"></label>
                    <select id="period-value">
                        <option>1 час</option>
                        <option>12 часов</option>
                        <option>24 часа</option>
                    </select>-->
                    <?=$form->field($task, 'filterPeriod', ['template' => '{input}'])->dropDownList([
                        '3600' => '1 час', '43200' => '12 часов', '86400' => '24 часа'
                    ], ['prompt' => 'Выбрать']); ?>
                </div>
                <input type="submit" class="button button--blue" value="Искать">
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
