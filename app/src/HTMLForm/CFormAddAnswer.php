<?php

namespace Anax\HTMLForm;

/**
 * Class for adding users.
 *
 */
class CFormAddAnswer extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

    /**
     * Constructor
     *
     */
    public function __construct($user, $id)
    {

            parent::__construct([], [


                'answer' => [
                    'type'        => 'textarea',
                    'label'       => 'Answer:',
                    'required'    => true,
                    'validation'  => ['not_empty'],
                ],

                'email' => [
                    'type'        => 'hidden',
                    'required'    => true,
                    'validation'  => ['not_empty'],
                    'value'       => $user->email,
                ],

                'idQuestion' => [
                    'type'      =>'hidden',
                    'required' => true,
                    'validation' =>['not_empty'],
                    'value'     => $id,
                ],

                'userId' => [
                    'type'      =>'hidden',
                    'required' => true,
                    'validation' =>['not_empty'],
                    'value'     => $user->id,
                ],

                'userScore' => [
                    'type'      =>'hidden',
                    'required' => true,
                    'validation' =>['not_empty'],
                    'value'     => $user->score,
                ],

                'submit' => [
                    'type'        => 'submit',
                    'callback'    => [$this, 'callbackSubmit'],
                ],




        ]);



}

    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmit($user)
    {
        $answer = new \Anax\Answer\Answer();
        $answer->setDI($this->di);

        $user = new \Anax\Users\User();
        $user->setDI($this->di);

        $now = gmdate('Y-m-d H:i:s');

        $saved = $answer->save([
            'answer'      => $this->Value('answer'),
            'email'      => $this->Value('email'),
            'idQuestion'        => $this->Value('idQuestion'),
            'score'     => 0,
            'created' => $now


        ]);

        $user->save([
            'id'    => $this->Value('userId'),
            'score' => $this->Value('userScore')+6
        ]);

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
       // $form->AddOutput("<p><i>Form was submitted and the callback method returned true.</i></p>");
        $this->redirectTo('question/id/'.$this->Value('idQuestion'));
    }

    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail($form)
    {
        $form->AddOutput("<p><i>Form was submitted and the Check() method returned false.</i></p>");
       // $this->redirectTo('users/add');
    }
}
