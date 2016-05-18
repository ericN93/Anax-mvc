<?php

namespace Anax\HTMLForm;

/**
* Class for adding users.
*
*/
class CFormUpdateComment extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
    \Anax\MVC\TRedirectHelpers;

    /**
    * Constructor
    *
    */
    public function __construct($comment)
    {
        //dump($comment);

            parent::__construct([], [

            'id' => [
                'type' =>'hidden',
                'value' => $comment->id,
            ],

            'idQA' => [
                'type' => 'hidden',
                'value' => $comment->idQA,

            ],

            'type' => [
                'type' => 'hidden',
                'value' => $comment->type,
            ],

            'comment' => [
                'type'        => 'textarea',
                'label'       => 'Comment:',
                'required'    => true,
                'validation'  => ['not_empty'],
                'value'       => $comment->comment,
            ],

            'submit' => [
                'type'        => 'submit',
                'callback'    => [$this, 'callbackSubmitUpdate'],
            ],
            'remove-one' => [
                'type'        => 'submit',
                'callback'    => [$this, 'callbackSubmitRemove'],
            ],

        ]);



    }


    /**
    * Callback for submit-button.
    *
    */

    public function callbackSubmitUpdate()
    {
        $comment = new \Anax\Comment\Comment();
        $comment->setDI($this->di);

        $now = gmdate('Y-m-d H:i:s');
        $active = empty($_POST['active']) ? null : $now;

        $saved = $comment->save([
            'id'    =>$this->Value('id'),
            'comment'        => $this->Value('comment'),

        ]);

        return $saved ? true : false;
    }

    public function callbackSubmitRemove()
    {

        $this->di->dispatcher->forward([
            'controller' => 'comment',
            'action'     => 'remove-one',
            'params'     => [$this->Value('id')],
        ]);

        if($this->Value('type')=='question')
        {
            $this->redirectTo('question/id/'.$this->Value('idQA'));
        } else{
            $this->redirectTo('answer/view/'.$this->Value('idQA'));
        }


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
        if($form->Value('type')=='question')
        {
            $this->redirectTo('question/id/'.$this->Value('idQA'));
        } else{
            $this->redirectTo('answer/view/'.$this->Value('idQA'));
        }
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
