<?php

namespace Anax\HTMLForm;

/**
 * Anax base class for wrapping sessions.
 *
 */
class AnswerformController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;


    /**
     * Index action using external form.
     *
     */
    public function indexAction($questionId)
    {
        //$this->di->session();
        $currentUser = $this->di->user->getCurrentUser();
        $userByEmail = $this->di->user->getByEmail($currentUser['email']);

        if($currentUser!=null)
        {

            $form = new \Anax\HTMLForm\CFormAddAnswer($userByEmail, $questionId);
            $form->setDI($this->di);
            $form->check();

            $this->di->theme->setTitle("Add a answer");
            $this->di->views->add('me/page', [
                'title' => "Add A answer",
                'content' => $form->getHTML()
            ]);
        }

    }

    public function updateAction($answer=[])
    {
        
        $form = new \Anax\HTMLForm\CFormUpdateAnswer($answer);
        $form->setDI($this->di);
        $form->check();

        $this->di->theme->setTitle("Update a answer");
        $this->di->views->add('me/page', [
            'title' => "Update a answer",
            'content' => $form->getHTML()
        ]);
    }

}
