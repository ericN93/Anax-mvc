<?php

namespace Anax\HTMLForm;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CommentformController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;


    /**
     * Index action using external form.
     *
     */
    public function indexAction($idQuestion, $type)
    {
        //$this->di->session();
        $currentUser = $this->di->user->getCurrentUser();
        $userByEmail = $this->di->user->getByEmail($currentUser['email']);
        if($currentUser!=null)
        {
            $form = new \Anax\HTMLForm\CFormAddComment($userByEmail, $idQuestion, $type);
            $form->setDI($this->di);
            $form->check();

            $this->di->views->add('me/page', [
                'title' => "Add a comment",
                'content' => $form->getHTML()
            ]);
        }

    }

    public function updateAction($comment=[])
    {
        
        $form = new \Anax\HTMLForm\CFormUpdateComment($comment);
        $form->setDI($this->di);
        $form->check();

        $this->di->theme->setTitle("Update a Comment");
        $this->di->views->add('me/page', [
            'title' => "Update a comment",
            'content' => $form->getHTML()
        ]);
    }
}
