<?php
require __DIR__.'/config_with_app.php';
$di  = new \Anax\DI\CDIFactoryDefault();

$di->set('CommentController', function() use ($di) {
    $controller = new \erna13\Comment\CommentController();
    $controller->setDI($di);
    return $controller;
});
$app = new \Anax\Kernel\CAnax($di);

$app->theme->configure(ANAX_APP_PATH . 'config/theme-grid.php');
$app->navbar->configure(ANAX_APP_PATH . 'config/navbar_theme.php');


$app->router->add('', function () use ($app) {


    $content = $app->fileContent->get('home_me.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');

    $byline  = $app->fileContent->get('byline.md');
    $byline = $app->textFilter->doFilter($byline, 'shortcode, markdown');

    $app->views->add('me/page', [
        'content' => $content,
        'byline' => $byline,
    ]);


});

$app->router->add('regioner', function() use ($app) {

    $app->theme->addStylesheet('css/anax-grid/regions_demo.css');
    $app->theme->setTitle("Regioner");

    $app->views->addString('flash', 'flash')
               ->addString('featured-1', 'featured-1')
               ->addString('featured-2', 'featured-2')
               ->addString('featured-3', 'featured-3')
               ->addString('main', 'main')
               ->addString('sidebar', 'sidebar')
               ->addString('triptych-1', 'triptych-1')
               ->addString('triptych-2', 'triptych-2')
               ->addString('triptych-3', 'triptych-3')
               ->addString('footer-col-1', 'footer-col-1')
               ->addString('footer-col-2', 'footer-col-2')
               ->addString('footer-col-3', 'footer-col-3')
               ->addString('footer-col-4', 'footer-col-4');

});

$app->router->add('rutnet', function () use ($app) {


    $app->theme->setTitle("rutnät");

    $app->views->add('welcome/hello_world');

});

$app->router->add('typography', function () use ($app) {
        $app->theme->setTitle("Typo");
        $app->views->add('theme/typography', [], 'main')
               ->add('theme/typography', [], 'sidebar');

});

$app->router->add('font-awesome', function () use ($app) {
        $app->theme->setTitle("font");
        $app->views->add('theme/font-main', [],'main')
            ->add('theme/font-sidebar', [], 'sidebar')
            ->add('theme/font-flash', [], 'flash');


});



$app->router->handle();
$app->theme->render();
