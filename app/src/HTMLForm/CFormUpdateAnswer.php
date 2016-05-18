<?php

namespace Anax\HTMLForm;

/**
 * Class for adding users.
 *
 */
class CFormUpdateAnswer extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

    /**
     * Constructor
     *
     */
    public function __construct($answer)
    {
            parent::__construct([], [

                'id' => [
                    'type' => 'hidden',
                    'required'    => true,
                    'validation'  => ['not_empty'],
                    'value'     => $answer->id,
                ],

                'answer' => [
                    'type'        => 'textarea',
                    'label'       => 'Answer:',
                    'required'    => true,
                    'validation'  => ['not_empty'],
                    'value'     => $answer->answer,
                ],



                'submit' => [
                    'type'        => 'submit',
                    'callback'    => [$this, 'callbackSubmit'],
                ],

                'remove' => [
                    'type'        => 'submit',
                    'callback'    => [$this, 'callbackSubmitRemove'],
                ],



        ]);



}

    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmit()
    {
        $answer = new \Anax\Answer\Answer();
        $answer->setDI($this->di);

        $now = gmdate('Y-m-d H:i:s');

        $saved = $answer->save([
            'id'        => $this->Value('id'),
            'answer'      => $this->Value('answer'),
            'created' => $now,


        ]);

       return $saved ? true : false;
   }

   public function callbackSubmitRemove()
   {

       $answer = new \Anax\Answer\Answer();
       $answer->setDI($this->di);

       $comment = new \Anax\Comment\Comment();
       $comment->setDI($this->di);


       $answers=$answer->findAll();
       $comments=$comment->findAll();

       foreach ($answers as $answer2) {
           foreach ($comments as $comment2) {

                   //svar kommentarer

                   if($comment2->idQA==$answer2->id && $comment2->type == 'answer' && $answer2->id == $this->Value('id') )
                   {

                       $comment->delete($comment2->id);
                   }

               }


           }
           $answer->delete($this->Value('id'));

       $this->redirectTo('question/list');

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
        $this->redirectTo('answer/view/'.$this->Value('id'));
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
