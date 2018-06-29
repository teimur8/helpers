<?php

namespace common\base;

use common\handlers\PushNotifyHandler;
use common\handlers\SmscNotifyHandler;
use common\handlers\YiiMailerNotifyHandler;
use common\services\DummyChatService;
use Namshi\Notificator\Manager;
use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\data\Pagination;

class ContainerDefinitions implements BootstrapInterface
{

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        Yii::$container->setSingletons([
            \Namshi\Notificator\Manager::class => function () {
                $handlers[] = new SmscNotifyHandler(Yii::$app->params['smsc']['login'], Yii::$app->params['smsc']['password']);
                $handlers[] = new YiiMailerNotifyHandler();
                $handlers[] = new PushNotifyHandler();

                $manager = new Manager($handlers, Yii::$app->monolog->logger);

                return $manager;
            }
        ]);


    }

}
