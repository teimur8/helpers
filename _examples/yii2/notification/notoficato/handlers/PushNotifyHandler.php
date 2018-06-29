<?php
namespace common\handlers;


use common\models\PushNotification;
use Namshi\Notificator\Notification\Handler\HandlerInterface;
use Namshi\Notificator\NotificationInterface;
use Yii;

class PushNotifyHandler implements HandlerInterface
{
    /**
     * @inheritDoc
     */
    function shouldHandle(NotificationInterface $notification)
    {
        return $notification instanceof PushNotification;
    }

    /**
     * @inheritDoc
     */
    function handle(NotificationInterface $notification)
    {
        Yii::$app->fcm->send($notification->getMessage());
    }
}
