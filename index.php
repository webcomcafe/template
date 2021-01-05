<?php

require __DIR__.'/vendor/autoload.php';

$view = new Webcomcafe\Templating\View([
    'url' => 'http://templating.local',
    'dir' => __DIR__ . DS . 'resources',
    'ext' => 'phtml',
]);

try {

     print $view->render('backend.index', [
         'title' => 'Home index',
         'pageTitle' => 'Admin Dashboard',
     ]);
    //$view->render('web.about');
    //$view->render('web.contact');

} catch (\Throwable $e) {
    echo $e->getMessage();
}