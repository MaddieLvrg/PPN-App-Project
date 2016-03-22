<?php

require_once __DIR__ . '/vendor/autoload.php';

#$loader = new Twig_Loader_Filesystem('views');
#$twig = new Twig_Environment($loader);

$app = new Silex\Application();
$app['debug'] = true;

$app -> run();

$app->get('/', function(){
    return "Hello world";
});
/*
$app->get('/home', function () use ($twig) {
    $twig->render('hello.twig', array(
        'name' => 'Maddie',
        'age' => 23
    ));
    return $twig;
});
*/