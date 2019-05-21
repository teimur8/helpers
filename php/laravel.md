```php
// overwrite view
\View::composer('admin::partials.footer', function ($view) {
    $view->setPath( \View::getFinder()->find('admin.user.footer'));
});
```
