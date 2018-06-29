<?php

namespace common\handlers;


use common\models\EmailNotification;
use Namshi\Notificator\Notification\Handler\HandlerInterface;
use Namshi\Notificator\NotificationInterface;
use Yii;

class YiiMailerNotifyHandler implements HandlerInterface
{
    /**
     * @inheritDoc
     */
    function shouldHandle(NotificationInterface $notification)
    {
        return $notification instanceof EmailNotification;
    }

    /**
     * @inheritDoc
     */
    function handle(NotificationInterface $notification)
    {
        Yii::$app->mailer->send($notification->getMessage());
    }
}
