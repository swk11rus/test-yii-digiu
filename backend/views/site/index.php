<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
$user = \common\components\Utility::getCurrentUser();
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent">
        <h1 class="display-4"><?= $user->email?></h1>

        <p class="lead">Мой партнерский ID: <?= $user->partner_id?></p>
        <p class="lead">Партнерский ID под кем зарегистрировался: <?= $user->menu->parents(1)->one()
                ? $user->menu->parents(1)->one()->user->partner_id
                : 'Отсутствует'?></p>
    </div>


</div>
