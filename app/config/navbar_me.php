<?php
/**
 * Config-file for navigation bar.
 *
 */
 $user=$this->di->user->loggedIn();
 if(!$user)
 {
     $text='Login';
     $temp=
         [
             'text'  => 'Registrera',
             'url'   => $this->di->get('url')->create('users/add'),
             'title' => 'second route of current frontcontroller'
         ];

 }else{
     $text='Account';
     $temp=
        [
         'text'  => 'Log out',
         'url'   => $this->di->get('url')->create('users/logout'),
         'title' => 'second route of current frontcontroller'
     ];

 }
return [

    // Use for styling the menu
    'class' => 'navbar',

    // Here comes the menu strcture
    'items' => [

        // This is a menu item
        'home'  => [
            'text'  => 'Home',
            'url'   => $this->di->get('url')->create(''),
            'title' => 'Home route of current frontcontroller'
        ],


        // This is a menu item
        'about' => [
            'text'  =>'About',
            'url'   => $this->di->get('url')->create('about'),
            'title' => 'Internal route within this frontcontroller(Om)'
        ],


        'question' => [
            'text' => 'Questions',
            'url' => $this->di->get('url')->create('question/list'),
            'title' => 'Internal route within this frontcontroller(Questions)'

        ],

        'tag' => [
            'text' => 'Tags',
            'url' => $this->di->get('url')->create('tag/list'),
            'title' => 'Internal route within this frontcontroller(Questions)'

        ],


        'login'=>  [
            'text'  => $text,
            'url'   => $this->di->get('url')->create('users/login'),
            'title' => 'Home route of current frontcontroller',
            // Here we add the submenu, with some menu items, as part of a existing menu item
            'submenu' => [

                'items' => [

                $temp,


                ],
            ],
        ],
/*
        'resetQuestion' => [
            'text'  =>'reset questions',
            'url'   => $this->di->get('url')->create('question/setup'),
            'title' => 'Internal route within this frontcontroller(Om)'
        ],

        'resetAnswers' => [
            'text'  =>'reset answers',
            'url'   => $this->di->get('url')->create('answer/setup'),
            'title' => 'Internal route within this frontcontroller(Om)'
        ],

        'resetTags' => [
            'text'  =>'reset tags',
            'url'   => $this->di->get('url')->create('tag/setup'),
            'title' => 'Internal route within this frontcontroller(Om)'
        ],

        'resetComments' => [
            'text'  =>'reset comments',
            'url'   => $this->di->get('url')->create('comment/setup'),
            'title' => 'Internal route within this frontcontroller(Om)'
        ],
        'resetUsers' => [
            'text'  =>'reset User',
            'url'   => $this->di->get('url')->create('users/setup'),
            'title' => 'Internal route within this frontcontroller(Om)'
        ],







        'source' => [
            'text'  =>'Source',
            'url'   => $this->di->get('url')->create('source'),
            'title' => 'Internal route within this frontcontroller(Source)'
        ],
    */


    ],



    /**
     * Callback tracing the current selected menu item base on scriptname
     *
     */
    'callback' => function ($url) {
        if ($url == $this->di->get('request')->getCurrentUrl(false)) {
            return true;
        }
    },



    /**
     * Callback to check if current page is a decendant of the menuitem, this check applies for those
     * menuitems that has the setting 'mark-if-parent' set to true.
     *
     */
    'is_parent' => function ($parent) {
        $route = $this->di->get('request')->getRoute();
        return !substr_compare($parent, $route, 0, strlen($parent));
    },



   /**
     * Callback to create the url, if needed, else comment out.
     *
     */
   /*
    'create_url' => function ($url) {
        return $this->di->get('url')->create($url);
    },
    */
];
?>
