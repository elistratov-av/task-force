<?php

/* @var $this yii\web\View */
/* @var $model LoginForm01 */

use app\models\LoginForm01;
use yii\widgets\ActiveForm;
?>
<section class="modal enter-form form-modal" id="enter-form">
    <h2>Вход на сайт</h2>
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true, 'action' => ['auth/login']]); ?>
        <?= $form->field($model, 'email', ['labelOptions' => ['class' => 'form-modal-description'],
            'inputOptions' => ['class' => 'enter-form-email input input-middle']]); ?>

        <?= $form->field($model, 'password', [ 'labelOptions' => ['class' => 'form-modal-description'],
            'inputOptions' => ['class' => 'enter-form-email input input-middle']])->passwordInput(); ?>

        <button class="button" type="submit">Войти</button>
    <?php ActiveForm::end(); ?>
    <button class="form-modal-close" type="button">Закрыть</button>
</section>
