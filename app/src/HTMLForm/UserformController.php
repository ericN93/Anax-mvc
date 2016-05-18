<?php

namespace Anax\HTMLForm;

/**
 * Anax base class for wrapping sessions.
 *
 */
class UserformController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;


    /**
     * Index action using external form.
     *
     */

     public function loginAction()
     {
         $this->di->session();

         $form = new \Anax\HTMLForm\CFormLoginUser();
         $form->setDI($this->di);
         $form->check();

         $this->di->theme->setTitle("Login");
         $this->di->views->add('me/page', [
             'title' => "Login",
             'content' => $form->getHTML()
         ]);

     }

    public function indexAction($user=null, $id=null)
    {
        $this->di->session();

        $form = new \Anax\HTMLForm\CFormAddUser($user, $id);
        $form->setDI($this->di);
        $form->check();

        $this->di->theme->setTitle("Add a user");
        $this->di->views->add('me/page', [
            'title' => "Add a user",
            'content' => $form->getHTML()
        ]);

    }


}
