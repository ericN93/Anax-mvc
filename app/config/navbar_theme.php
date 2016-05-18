<?php
/**
 * Config-file for navigation bar.
 *
 */
return [

    // Use for styling the menu
    'class' => 'navbar',

    // Here comes the menu strcture
    'items' => [

        // This is a menu item
        'home'  => [
            'text'  => 'Theme',
            'url'   => $this->di->get('url')->create(''),
            'title' => 'Home route of current frontcontroller'
        ],


        // This is a menu item
        'regioner'  => [
            'text'  => 'Regioner',
            'url'   => $this->di->get('url')->create('regioner'),
            'title' => 'second route of current frontcontroller'
        ],

        // This is a menu item
        'rutnet'  => [
            'text'  => 'Rutnät',
            'url'   => $this->di->get('url')->create('rutnet'),
            'title' => 'third route of current frontcontroller'
        ],

        // This is a menu item
        'typography'  => [
            'text'  => 'Typography',
            'url'   => $this->di->get('url')->create('typography'),
            'title' => 'fourth route of current frontcontroller'
        ],

        // This is a menu item
        'font-awesome'  => [
            'text'  => 'Font-awsome',
            'url'   => $this->di->get('url')->create('font-awesome'),
            'title' => 'fifth route of current frontcontroller'
        ],



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
