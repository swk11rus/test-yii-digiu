<?php

namespace common\components;

use common\models\User;
use DateTime;
use Yii;

class Utility
{
    public static function getDateNow($format='Y-m-d H:i:s'): string
    {
        $date = new DateTime('now');
        return $date->format($format);
    }

    public static function getCurrentUserId() : int
    {
        return  Yii::$app->user->id;
    }

    /**
     * @throws \Exception
     */
    public static function getCurrentUser(): ?User
    {
        return User::findOne(['id' => static::getCurrentUserId()]);
    }
}