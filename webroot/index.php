<?php
require __DIR__.'/config_with_app.php';
$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);
$di  = new \Anax\DI\CDIFactoryDefault();

//user
$di->setShared('user', function () use ($di) {
    $user = new \Anax\Users\User();
    $user->setDI($di);
    return $user;
});


//tag
$di->setShared('tag', function () use ($di) {
    $tag = new \Anax\Tag\Tag();
    $tag->setDI($di);
    return $tag;
});

//tagController
$di->set('TagController', function() use ($di) {
    $controller = new \Anax\Tag\TagController();
    $controller->setDI($di);
    return $controller;
});

//userkontroller
$di->set('UsersController', function() use ($di) {
    $controller = new \Anax\Users\UsersController();
    $controller->setDI($di);
    return $controller;
});
//database
$di->setShared('db', function() {
    $db = new \Mos\Database\CDatabaseBasic();
    $db->setOptions(require ANAX_APP_PATH . 'config/config_mysql.php');
    $db->connect();
    return $db;
});

// commetn controller
$di->set('CommentController', function() use ($di) {
    $controller = new \Anax\Comment\CommentController();
    $controller->setDI($di);
    return $controller;
});

//comment
$di->setShared('comment', function () use ($di) {
    $comment = new \Anax\Comment\Comment();
    $comment->setDI($di);
    return $comment;
});
// answer controller

$di->set('AnswerController', function() use ($di) {
    $controller = new \erna13\Answer\AnswerController();
    $controller->setDI($di);
    return $controller;
});


// answerform controller
//formcontroller

$di->set('AnswerformController', function () use ($di) {
    $controller = new \Anax\HTMLForm\AnswerformController();
    $controller->setDI($di);
    return $controller;
});


//question controller
$di->set('QuestionController', function () use ($di) {
     $controller = new \Anax\Question\QuestionController();
     $controller->setDI($di);
     return $controller;
});
$di->setShared('question', function () use ($di) {
     $question = new \Anax\Question\Question();
     $question->setDI($di);
     return $question;
});

$di->setShared('answer', function () use ($di) {
     $answer = new \Anax\Answer\Answer();
     $answer->setDI($di);
     return $answer;
});

$di->set('QuestionformController', function () use ($di) {
     $controller = new \Anax\HTMLForm\QuestionformController();
     $controller->setDI($di);
     return $controller;
});

//formcontroller
$di->set('UserformController', function () use ($di) {
    $controller = new \Anax\HTMLForm\UserformController();
    $controller->setDI($di);
    return $controller;
});
//formcontroller
$di->set('LoginformController', function () use ($di) {
    $controller = new \Anax\HTMLForm\UserformController();
    $controller->setDI($di);
    return $controller;
});

//fcommentormcontroller
$di->set('CommentformController', function () use ($di) {
    $controller = new \Anax\HTMLForm\CommentformController();
    $controller->setDI($di);
    return $controller;
});

$app = new \Anax\Kernel\CAnax($di);

$app->theme->configure(ANAX_APP_PATH . 'config/theme-grid.php');
$app->navbar->configure(ANAX_APP_PATH . 'config/navbar_me.php');

$app->router->add('', function () use ($app) {



    $app->dispatcher->forward([
        'controller' =>'question',
        'action'    =>'front'
    ]);

    $app->dispatcher->forward([
        'controller' =>'tag',
        'action'    =>'front'
    ]);

    $app->dispatcher->forward([
        'controller' =>'users',
        'action'    =>'front'
    ]);

});


$app->router->add('tags', function () use ($app) {

    $app->dispatcher->forward([
       'controller' => 'tags',
       'action'     => 'list'
    ]);
});


$app->router->add('about', function () use ($app) {

    $app->theme->setTitle("Källkod");

    $content = $app->fileContent->get('about.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');



    $app->views->add('me/page', [
        'content' => $content,

    ], 'main');


});

$app->router->add('report', function () use ($app) {

    $app->theme->setTitle("Report");

    $content = $app->fileContent->get('report.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');



    $app->views->add('me/page', [
        'content' => $content,

    ], 'main');



});





$app->router->add('source', function () use ($app) {

    $app->theme->addStylesheet('css/source.css');
    $app->theme->setTitle("Källkod");

    $source = new \Mos\Source\CSource([
        'secure_dir' => '..',
        'base_dir' => '..',
        'add_ignore' => ['.htaccess'],
    ]);

    $app->views->add('me/source', [
        'content' => $source->View(),
    ]);


});

$app->router->handle();
$app->theme->render();
