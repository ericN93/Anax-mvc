<?php

namespace Anax\HTMLForm;

/**
 * Class for adding users.
 *
 */
class CFormAddUser extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

    /**
     * Constructor
     *
     */
    public function __construct($user=null, $id=null)
    {
        if(!isset($id))
        {

        parent::__construct([], [
            'acronym' => [
                'type'        => 'text',
                'label'       => 'Användarnamn:',
                'required'    => true,
                'validation'  => ['not_empty'],

            ],
            'email' => [
                'type'        => 'text',
                'label'       => 'E-post:',
                'required'    => true,
                'validation'  => ['not_empty', 'email_adress'],
            ],
            'name' => [
                'type'        => 'text',
                'label'       => 'Namn:',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'password' => [
                'type'        => 'password',
                'label'       => 'Lösenord:',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],

            'score'  => [
                'type'  => 'hidden',
                'required'    => true,
                'validation'  => ['not_empty'],
                'value'     => 0,
            ],

            'submit' => [
                'type'        => 'submit',
                'callback'    => [$this, 'callbackSubmit'],
                        ],
            'reset' => [
                'type'        => 'reset',
                'value'       => 'Återställ',
            ],
        ]);
    }else {

        parent::__construct([], [
            'id' => [
                'type' =>'hidden',
                'value' => $user->id,
            ],
            'acronym' => [
                'type'        => 'text',
                'label'       => 'Användarnamn:',
                'required'    => true,
                'validation'  => ['not_empty'],
                'value'       => $user->acronym,
            ],
            'email' => [
                'type'        => 'text',
                'label'       => 'E-post:',
                'required'    => true,
                'validation'  => ['not_empty', 'email_adress'],
                'value'       => $user->email,
            ],

            'Oldemail' => [
                'type'        => 'hidden',
                'label'       => 'E-post:',
                'required'    => true,
                'validation'  => ['not_empty', 'email_adress'],
                'value'       => $user->email,
            ],
            'name' => [
                'type'        => 'text',
                'label'       => 'Namn:',
                'required'    => true,
                'validation'  => ['not_empty'],
                'value'       => $user->name,
            ],
            'password' => [
                'type'        => 'password',
                'label'       => 'Lösenord:',
                'required'    => false,
                //'value'       => "",
            ],

            'currentScore' => [
                'type'        => 'hidden',
                'required'    => false,
                'value'       => $user->score,

            ],

            'submit' => [
                'type'        => 'submit',
                'callback'    => [$this, 'callbackSubmitUpdate'],
                        ],
            'reset' => [
                'type'        => 'reset',
                'value'       => 'Återställ',
            ],
        ]);


    }
    }

    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmit()
    {
        $user = new \Anax\Users\User();
        $user->setDI($this->di);

        $now = gmdate('Y-m-d H:i:s');
        $active = empty($_POST['active']) ? null : $now;


        $email=$this->Value('email');

        $acronym=$this->Value('acronym');


        if(!$user->getByEmail($email))
        {
            if(!$user->findAcronym($acronym))
            {
                
            $saved = $user->save([
                'acronym'      => $this->Value('acronym'),
                'email'        => $this->Value('email'),
                'name'         => $this->Value('name'),
                'password'     => password_hash($this->Value('password'), PASSWORD_DEFAULT),
                'created'      => $now,
                'score'        =>$this->Value('score'),

            ]);


            $this->redirectTo('users/login');
            return $saved ? true : false;
        }
        }else{

        }
    }


    public function callbackSubmitUpdate()
    {
        $user = new \Anax\Users\User();
        $user->setDI($this->di);

        $now = gmdate('Y-m-d H:i:s');
        $active = empty($_POST['active']) ? null : $now;

        if($this->Value('password') == '')
        {
            $saved = $user->save([
                'acronym'      => $this->Value('acronym'),
                'email'        => $this->Value('email'),
                'name'         => $this->Value('name'),
                'created'      => $now,
                'id'           => $this->Value('id'),

            ]);


        }else {

            $saved = $user->save([
                'acronym'      => $this->Value('acronym'),
                'email'        => $this->Value('email'),
                'name'         => $this->Value('name'),
                'password'     => password_hash($this->Value('password'), PASSWORD_DEFAULT),
                'created'      => $now,
                'id'           => $this->Value('id'),
            ]);

        }

        $currentUser1= $this->di->user->getCurrentUser();
        session_unset($currentUser1);


        $this->di->session->set(
        'user',
        [
            'id' => $this->Value('id'),
            'acronym' =>$this->Value('acronym'),
            'email' =>$this->Value('email'),
            'name' =>$this->Value('name'),
            'score' =>$this->Value('currentScore'),
        ]
        );

        $this->di->dispatcher->forward([
            'controller' => 'question',
            'action'     => 'updateemail',
            'params'     => [$this->Value('email'), $this->Value('Oldemail')],
        ]);

        $this->di->dispatcher->forward([
            'controller' => 'answer',
            'action'     => 'updateemail',
            'params'     => [$this->Value('email'), $this->Value('Oldemail')],
        ]);

        $this->di->dispatcher->forward([
            'controller' => 'comment',
            'action'     => 'updateemail',
            'params'     => [$this->Value('email'), $this->Value('Oldemail')],
        ]);



        $this->redirectTo('users/id/'. $this->Value('id'));
        return $saved ? true : false;
    }


    /**
     * Customize the check() method.
     *
     * @param callable $callIfSuccess handler to call if function returns true.
     * @param callable $callIfFail    handler to call if function returns true.
     */
    public function check($callIfSuccess = null, $callIfFail = null)
    {
        return parent::check([$this, 'callbackSuccess'], [$this, 'callbackFail']);
    }

    /**
     * Callback What to do if the form was submitted?
     *
     */
    public function callbackSuccess($form)
    {
       //$form->AddOutput("<p><i>Form was submitted and the callback method returned true.</i></p>");
        //  $this->redirectTo('');

    }

    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail($form)
    {
    //$form->AddOutput("<p><i>hej.</i></p>");
       //$this->redirectTo('users/login');
    }
}
