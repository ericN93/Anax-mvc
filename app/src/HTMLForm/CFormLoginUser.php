<?php

namespace Anax\HTMLForm;

/**
 * Class for adding users.
 *
 */
class CFormLoginUser extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        
        parent::__construct([], [
            'acronym' => [
                'type'        => 'text',
                'label'       => 'Acronym:',
                'required'    => true,
                'validation'  => ['not_empty'],

            ],
            'password' => [
                'type'        => 'password',
                'label'       => 'Password:',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'submit' => [
                'type'        => 'submit',
                'label'       => 'Login',
                'callback'    => [$this, 'callbackSubmit'],
                        ],
            /*'reset' => [
                'type'        => 'reset',
                'value'       => 'Återställ',
            ],*/
        ]);

    }

    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmit()
    {
        $this->di->dispatcher->forward([
            'controller' => 'users',
            'action'     => 'login',
            'params' => [true, $this->Value('acronym'),$this->Value('password')],
        ]);
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
       // $form->AddOutput("<p><i>Form was submitted and the callback method returned true.</i></p>");
        $this->redirectTo('users/add');
    }

    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail($form)
    {
        $form->AddOutput("<p><i>Form was submitted and the Check() method returned false.</i></p>");
       $this->redirectTo('users/list');
    }
}
