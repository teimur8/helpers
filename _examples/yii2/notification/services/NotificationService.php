<?php
namespace common\services;


use common\jobs\SendNotificationJob;
use Namshi\Notificator\NotificationInterface;
use Yii;

abstract class NotificationService
{
    protected final function send(NotificationInterface $notification)
    {
        $job = new SendNotificationJob($notification);
        if (!YII_ENV_TEST) {
            Yii::$app->queue->push($job);
        }
    }
}
