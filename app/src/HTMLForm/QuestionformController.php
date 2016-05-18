<?php

namespace Anax\HTMLForm;

/**
 * Anax base class for wrapping sessions.
 *
 */
class QuestionformController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;



    /**
     * Index action using external form.
     *
     */
    public function indexAction()
    {
        $this->di->session();

        $currentUser = $this->di->user->getCurrentUser();
        $userByEmail = $this->di->user->getByEmail($currentUser['email']);
        $allQuestion=$this->di->question->findAll();

        $counter=count($allQuestion);

        $form = new \Anax\HTMLForm\CFormAddQuestion($userByEmail,$counter);
        $form->setDI($this->di);
        $form->check();

        $this->di->theme->setTitle("Ask question");
        $this->di->views->add('me/page', [
            'title' => "Ask Your Question",
            'content' => $form->getHTML()
        ]);
    }

    public function updateAction($question=[])
    {
        $currentUser = $this->di->user->getCurrentUser();
        $form = new \Anax\HTMLForm\CFormUpdateQuestion($question, $currentUser);
        $form->setDI($this->di);
        $form->check();

        $this->di->theme->setTitle("Update a question");
        $this->di->views->add('me/page', [
            'title' => "Update a question",
            'content' => $form->getHTML()
        ]);
    }

}
