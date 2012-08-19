<?php

namespace {

require dirname(dirname(__DIR__)) .
        DIRECTORY_SEPARATOR . 'Data' .
        DIRECTORY_SEPARATOR . 'Core.link.php';

from('Hoa')
-> import('Database.Dal')
-> import('Dispatcher.Basic')
-> import('Router.Http')
-> import('Xyl.~')
-> import('Xyl.Interpreter.Html.~')
-> import('File.Read')
-> import('Http.Response');

Hoa\Database\Dal::initializeParameters(array(
    'connection.list.default.dal' => Hoa\Database\Dal::PDO,
    'connection.list.default.dsn' => 'sqlite:hoa://Data/Variable/Database/Blog.sqlite',
    'connection.autoload'         => 'default'
));

$dispatcher = new Hoa\Dispatcher\Basic();
$dispatcher->setKitName('Application\Kit\Redirect');
$router     = new Hoa\Router\Http();
$router->get('posts', '/', 'posts', 'index')
       ->get('post', '/posts/(?<id>\d+)', 'posts', 'show')
       ->get('new_post', '/posts/new', 'posts', 'new')
       ->post('create_post', '/posts/create', 'posts', 'create')
       ->get('edit_post', '/posts/(?<id>\d+)/edit', 'posts', 'edit')
       ->post('update_post', '/posts/(?<id>\d+)', 'posts', 'update');

try {

    $dispatcher->dispatch(
        $router,
        new Hoa\Xyl(
            new Hoa\File\Read('hoa://Application/View/Main.xyl'),
            new Hoa\Http\Response(),
            new Hoa\Xyl\Interpreter\Html(),
            $router
        )
    );
}
catch ( Hoa\Router\Exception\NotFound $e ) {

    echo 'Your page seems to be not found /o\.', "\n";
}

}
