<?php

namespace common\jobs;


use common\base\BaseJob;
use Namshi\Notificator\Manager;
use Namshi\Notificator\NotificationInterface;
use yii\queue\Queue;

class SendNotificationJob extends BaseJob
{
    /**
     * @var NotificationInterface
     */
    public $notification;

    /**
     * @inheritDoc
     */
    public function __construct(NotificationInterface $notification, $config = [])
    {
        parent::__construct($config);
        $this->notification = $notification;
    }

    public function executeByDI(Manager $manager, Queue $queue)
    {
        return $manager->trigger($this->notification);
    }
}
