<?php

namespace Anax\HTMLForm;


class CFormAddQuestion extends \Mos\HTMLForm\CForm
{

    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;


        public function __construct($user, $counter)
        {

            if(!$user)
            {
                    $this->redirectTo('');
            } else {
                parent::__construct([],[

                    'header' => [
                        'type' => 'text',
                        'label'=> 'Header',
                        'required'    => true,
                        'validation'  => ['not_empty'],
                    ],

                    'question' => [
                        'type' => 'textarea',
                        'label'=> 'Question',
                        'required'    => true,
                        'validation'  => ['not_empty'],
                    ],

                    'email' => [
                        'type' => 'hidden',
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

                    'tag' => [
                        'type' => 'text',
                        'required'    => true,
                        'validation'  => ['not_empty'],

                    ],

                    'idQ' => [
                        'type' => 'hidden',
                        'required'    => true,
                        'validation'  => ['not_empty'],
                        'value' =>$counter,

                    ],

                    'submit' => [
                        'type'        => 'submit',
                        'callback'    => [$this, 'callbackSubmit'],
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
            $question = new \Anax\Question\Question();
            $question->setDI($this->di);

            $user = new \Anax\Users\User();
            $user->setDI($this->di);

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
                        'header'       =>$this->Value('header'),
                        'question'      => $this->Value('question'),
                        'email'        => $this->Value('email'),
                        'tags'           =>$this->Value('tag'),
                        'created'       =>$now,
                    ]);

                    $user->save([
                        'id'    => $this->Value('userId'),
                        'score' => $this->Value('userScore')+4,
                    ]);

                    $this->di->dispatcher->forward([
                    'controller' => 'tag',
                    'action'     => 'add',
                    'params'     => [$this->Value('tag'), $this->Value('idQ')],
                    ]);


                } else {
                    $this->redirectTo('question/list');
                }



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
