<?php

namespace common\models;


use Namshi\Notificator\Notification;
use Namshi\Notificator\NotificationInterface;
use paragraph1\phpFCM\Recipient\Device;
use Yii;

class PushNotification extends Notification implements NotificationInterface
{
    /**
     * PushNotification constructor.
     * @param string $title
     * @param string $body
     * @param string $token
     * @param array $parameters
     */
    public function __construct(string $title, string $body, string $token, array $parameters = array())
    {
        $note = Yii::$app->fcm->createNotification($title, $body);

        $message = Yii::$app->fcm->createMessage();
        $message->addRecipient(new Device($token));
        $message->setNotification($note)->setData($parameters);

        parent::__construct($message, $parameters);
    }
}
