<?php

namespace Anax\HTMLForm;

/**
 * Class for adding users.
 *
 */
class CFormAddComment extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

    /**
     * Constructor
     *
     */
    public function __construct($user=null, $idQuestion=null, $type)
    {


            parent::__construct([], [



                'idQuestion' => [
                    'type'        => 'hidden',
                    'required'    => true,
                    'validation'  => ['not_empty'],
                    'value'       => $idQuestion,


                ],
                'comment' => [
                    'type'        => 'textarea',
                    'label'       => 'Comment:',
                    'required'    => true,
                    'validation'  => ['not_empty'],
                ],

                'type' => [
                    'type'        => 'hidden',
                    'required'    => true,
                    'validation'  => ['not_empty'],
                    'value'       => $type,
                ],

                'email' => [
                    'type'        => 'hidden',
                    'label'       => 'Email:',
                    'required'    => true,
                    'validation'  => ['not_empty', 'email_adress'],
                    'value'       => $user->email,
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
    public function callbackSubmit()
    {
        $comment = new \Anax\Comment\Comment();
        $comment->setDI($this->di);

        $user = new \Anax\Users\User();
        $user->setDI($this->di);



        $saved = $comment->save([
            'type'      => $this->Value('type'),
            'idQA'      => $this->Value('idQuestion'),
            'comment'        => $this->Value('comment'),
            'email'     => $this->Value('email'),
        ]);

        $user->save([
            'id'    => $this->Value('userId'),
            'score' => $this->Value('userScore')+1,
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
       if($this->Value('type')=='question')
       {
        $this->redirectTo('question/id/'.$this->Value('idQuestion'));
    }else {
        $this->redirectTo('answer/view/'.$this->Value('idQuestion'));
    }
    }

    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail($form)
    {
        $form->AddOutput("<p><i>You have to be logged in to comment! Dont have one? Sign up!.</i></p>");
       // $this->redirectTo('users/add');
    }
}
