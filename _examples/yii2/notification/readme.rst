1. ставим очередь queue
    - php composer.phar require --prefer-dist yiisoft/yii2-queue
    - https://github.com/yiisoft/yii2-queue
    - https://github.com/yiisoft/yii2-queue/tree/master/docs/guide-ru
    - https://github.com/yiisoft/yii2-queue/blob/master/docs/guide-ru/driver-db.md


2. нотификатор
    - php composer.phar require --prefer-dist namshi/notificato
    - https://github.com/namshi/notificator
    - принцип работы: создаем менеджера \Namshi\Notificator\Manager, цепляем к нему $handlers. Создаем например
      SmsNotification и передаед экземпляр менеджеру.

3.монлог
    - php composer.phar require "monolog/monolog" "^1.22"
    - php composer.phar require "mero/yii2-monolog" "^0.1"
    'components' => [
        'monolog' => [
            'class' => \Mero\Monolog\MonologComponent::class,
            'channels' => [
                'main' => [
                    'handler' => [
                        [
                            'type' => 'stream',
                            'path' => '@app/runtime/logs/main_' . date('Y-m-d') . '.log',
                            'level' => 'debug'
                        ]
                    ],
                    'processor' => [],
                ],
            ],
        ],
    ],


// отправка

4. устанавливаем в проект ConfirmToken, накатываем миграцию, добавляем сервис. Сервис будет генерить токен

$token = $this->tokenService->generate($item, ConfirmTokenType::SMS, ConfirmTokenAction::RESET_PASSWORD);

5. создаем нотификтаор
$notificator = new Namshi\Notificator\Notification\Sms\SmsNotification(
    $user->phone,
    Yii::t('app', 'Password reset code: {token}', ['token' => $token->token])
);

6.создаем SendNotificationJob, это контейнер для уведомления и обработчик очереди. метод executeByDI нужен для того,
что бы автоматом подтянуть зависимость

7. оборачиваем уведомление в SendNotificationJob, и помещаем в очередь
$job = new SendNotificationJob($notification);
if (!YII_ENV_TEST) {
    Yii::$app->queue->push($job);
}

// обработка

8.прописываем зависимости в ContainerDefinitions, сингелтон, инициализации менеджера уведомлени и навешивание
обработчиков: SmscNotifyHandler и YiiMailerNotifyHandler. Он прогоняет циклом и если метод shouldHandle даст true,
он выполянет событие.


9.очередь запускает SendNotificationJob, в которой менеджер запускает срабатывает уведолмения


