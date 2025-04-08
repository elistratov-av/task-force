<?php
/**
 * @var Task[] $models
 * @var Task $task
 * @var View $this
 * @var Category[] $categories
 * @var Pagination $pages
 */

use app\models\Category;
use app\models\Task;
use yii\data\Pagination;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

$this->title = 'Просмотр новых заданий';
?>
<div class="left-column">
    <?php
/*    echo '@yii: ' . Yii::getAlias('@yii') .'<br>';
    echo '@app: ' . Yii::getAlias('@app') .'<br>';
    echo '@runtime: ' . Yii::getAlias('@runtime') .'<br>';
    echo '@vendor: ' . Yii::getAlias('@vendor') .'<br>';
    echo '@webroot: ' . Yii::getAlias('@webroot') .'<br>';
    echo '@web: ' . Yii::getAlias('@web') .'<br>';
    */?>
    <h3 class="head-main head-task">Новые задания</h3>
    <?php foreach ($models as $model): ?>
        <?= $this->render('//partials/_task', ['model' => $model]) ?>
    <?php endforeach; ?>
    <div class="pagination-wrapper">
        <?= LinkPager::widget([
            'pagination' => $pages,
            'options' => ['class' => 'pagination-list'],
            'prevPageCssClass' => 'pagination-item mark',
            'nextPageCssClass' => 'pagination-item mark',
            'pageCssClass' => 'pagination-item',
            'activePageCssClass' => 'pagination-item--active',
            'linkOptions' => ['class' => 'link link--page'],
            'nextPageLabel' => '',
            'prevPageLabel' => '',
            'maxButtonCount' => 5
        ]) ?>
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
