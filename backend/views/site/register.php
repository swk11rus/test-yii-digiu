<?php



/* @var $this \yii\web\View */
/* @var $model \backend\models\RegisterForm */

use common\models\User;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\helpers\Url;

$this->title = 'Register';
?>
<div class="site-login">
    <div class="mt-5 offset-lg-3 col-lg-6">
        <h1><?= Html::encode($this->title) ?> | <?= Html::a('Login', Url::to('login'))?> </h1>

        <p>Please fill out the following fields to register:</p>

        <?php if (Yii::$app->session->hasFlash('error')): ?>
            <div class="alert alert-danger alert-dismissable">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <h4><i class="icon fa fa-check"></i>Ошибка!</h4>
                <?= Yii::$app->session->getFlash('error') ?>
            </div>
        <?php endif; ?>

        <?php $form = ActiveForm::begin(['id' => 'register-form']); ?>

        <?php
        if (User::hasRecords()) {
            echo $form->field($model, 'parentPartnerId')->textInput([
                'type' => 'text',
                'maxlength' => '10',
                'autofocus' => true,
                'placeholder' => '10 цифр',
            ]);
        }
        ?>

        <?php
        if (User::hasNotRecords()) {
            echo $form->field($model, 'selfPartnerId')->textInput([
                'type' => 'text',
                'maxlength' => '10',
                'autofocus' => true,
                'placeholder' => '10 цифр',
            ]);
        }
        ?>

        <?= $form->field($model, 'email')->textInput(['placeholder' => 'example@email.com']) ?>

        <?= $form->field($model, 'password')->passwordInput() ?>


        <div class="form-group">
            <?= Html::submitButton('Register', ['class' => 'btn btn-primary btn-block', 'name' => 'register-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
