<?php

namespace Anax\HTMLForm;


class CFormUpdateQuestion extends \Mos\HTMLForm\CForm
{

    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;


        public function __construct($question=null, $user)
        {

                parent::__construct([],[


                    'id'    =>[
                        'type' =>'hidden',
                        'required'    => true,
                        'validation'  => ['not_empty'],
                        'value'     =>$question->id,
                    ],

                    'header' => [
                        'type' => 'text',
                        'label'=> 'Header',
                        'required'    => true,
                        'validation'  => ['not_empty'],
                        'value'     =>$question->header,
                    ],

                    'question' => [
                        'type' => 'textarea',
                        'label'=> 'Question',
                        'required'    => true,
                        'validation'  => ['not_empty'],
                        'value'     =>$question->question,
                    ],

                    'tag' => [
                        'type' => 'text',
                        'required'    => true,
                        'validation'  => ['not_empty'],
                        'value'     =>$question->tags,

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
            $question = new \Anax\Question\Question();
            $question->setDI($this->di);

            $now = gmdate('Y-m-d H:i:s');

            $nrOfSameTags = false;

                $allTags = explode(",",$this->Value('tag'));
                $allTags = array_count_values($allTags);
                foreach ($allTags as $key) {
                    if($key >= 2){
                        $nrOfSameTags = true;
                    }
                }
                if($nrOfSameTags == false)
                {
                    $saved = $question->save([
                        'id'           =>$this->Value('id'),
                        'header'       =>$this->Value('header'),
                        'question'      => $this->Value('question'),
                        'tags'           =>$this->Value('tag'),
                        'created'       =>$now,
                    ]);

                    $this->di->dispatcher->forward([
                    'controller' => 'tag',
                    'action'     => 'add',
                    'params'     => [$this->Value('tag')],
                    ]);

                } else {
                    //$this->redirectTo('question/list');
                }



           //return $saved ? true : false;
        }

        public function callbackSubmitRemove()
        {
            $question = new \Anax\Question\Question();
            $question->setDI($this->di);

            $answer = new \Anax\Answer\Answer();
            $answer->setDI($this->di);

            $comment = new \Anax\Comment\Comment();
            $comment->setDI($this->di);

            $tags = new \Anax\Tag\Tag();
            $tags->setDI($this->di);

            $questions=$question->findAll();
            $theQuestion=$question->find($this->Value('id'));
            //echo ($theQuestion->id);

            $answers=$answer->findAll();
            $comments=$comment->findAll();

            foreach ($questions as $question2) {
                foreach ($answers as $answer2) {
                    foreach ($comments as $comment2) {

                        //frågor kommentarer

                        if($comment2->idQA == $question2->id && $comment2->type == 'question' && $question2->id == $this->Value('id') )
                        {
                            //echo "inne question <br>";
                            $comment->delete($comment2->id);
                        }
                        //svar kommentarer
                        if($comment2->idQA==$answer2->id && $comment2->type == 'answer' && $question2->id == $this->Value('id') )
                        {
                            $comment->delete($comment2->id);
                        }

                    }


                    if($answer2->idQuestion==$question2->id && $question2->id == $this->Value('id'))
                    {
                        $answer->delete($answer2->id);
                    }


                }

        }

        $question->delete($this->Value('id'));

        $this->di->dispatcher->forward([
        'controller' => 'tag',
        'action'     => 'delete-tags',
        'params'     => [$theQuestion],
        ]);




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
            $this->redirectTo('question/list');
        }

        /**
         * Callback What to do when form could not be processed?
         *
         */
        public function callbackFail($form)
        {
            $form->AddOutput("<p><i>Form was submitted and the Check() method returned false.</i></p>");
        }
}
