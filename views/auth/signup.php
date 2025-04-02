<?php
/**
 * @var User $model
 * @var City[] $cities
 */

use app\models\City;
use app\models\User;
use yii\widgets\ActiveForm;

$this->title = 'Регистрация пользователя';
?>
<div class="center-block">
    <div class="registration-form regular-form">
        <?php $form = ActiveForm::begin() ?>
        <h3 class="head-main head-task">Регистрация нового пользователя</h3>
        <div class="form-group">
            <?= $form->field($model, 'name') ?>
        </div>
        <div class="half-wrapper">
            <div class="form-group">
                <?= $form->field($model, 'email') ?>
            </div>
            <div class="form-group">
                <?= $form->field($model, 'city_id')->dropDownList(array_column($cities, 'name', 'id')) ?>
            </div>
        </div>
        <div class="half-wrapper">
            <div class="form-group">
                <?= $form->field($model, 'password')->passwordInput() ?>
            </div>
        </div>
        <div class="half-wrapper">
            <div class="form-group">
                <?= $form->field($model, 'password_repeat')->passwordInput() ?>
            </div>
        </div>
        <?= $form->field($model, 'is_contractor')
            ->checkbox(['labelOptions' => ['class' => 'control-label checkbox-label']]) ?>
        <input type="submit" class="button button--blue" value="Создать аккаунт">
        <?php ActiveForm::end(); ?>
    </div>
</div>
