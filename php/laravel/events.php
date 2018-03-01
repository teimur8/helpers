// http://laravel.su/docs/5.2/events

//1 создаем событие
php artisan make:event UserEvents      // app/Events

//2 создаем слушатель
UserEventsListener в app/Listeners

//3 регестрируем в EventServiceProvider
protected $listen = [
    'App\Events\UserEvents' => [
        'App\Listeners\UserEventsListener',
    ],
];

//4 запускаем event(new UserEvents($что то))

события можно втыкать в очереди, передавать им что хочешь.



