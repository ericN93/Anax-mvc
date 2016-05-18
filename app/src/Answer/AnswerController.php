<?php

namespace erna13\Answer;

class AnswerController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;
    public function setupAction(){

        $this->db->dropTableIfExists('answer')->execute();

        $this->db->createTable(
            'answer',
            [
                'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
                'idQuestion' => ['integer', 'not null'],
                'answer' => ['text', 'not null'],
                'email' => ['varchar(80)', 'not null'],
                'score' => ['integer'],
                'accepted' => ['varchar(10)'],
                'created' => ['datetime'],
            ]
            )->execute();

        $this->db->insert(
            'answer',
            ['idQuestion', 'answer', 'email', 'score', 'accepted', 'created']
        );

        $now = gmdate('Y-m-d H:i:s');

        $this->db->execute([
            '1',
            'Lorem ipsum dolor sit amet, consectetur
             adipiscing elit. Donec odio nibh, tempus
              facilisis eleifend elementum, hendrerit
              eu metus. Suspendisse quis felis lobortis,
               tempor tellus in, dapibus elit. Aliquam felis
                urna, congue in tempus quis, lobortis efficitur
                 nisi. Nunc ornare magna at purus fringilla, vitae
                  iaculis tellus gravida. Proin ornare lectus vel',
            'eric@dbwebb.se',
            10,
            'no',
            $now,
        ]);

        $this->db->execute([
            '2',
            'Lorem ipsum dolor sit amet, consectetur
             adipiscing elit. Donec odio nibh, tempus
              facilisis eleifend elementum, hendrerit
              eu metus. Suspendisse quis felis lobortis,
               tempor tellus in, dapibus elit. Aliquam felis
                urna, congue in tempus quis, lobortis efficitur
                 nisi. Nunc ornare magna at purus fringilla, vitae
                  iaculis tellus gravida. Proin ornare lectus vel',
            'doe@dbwebb.se',
            5,
            'no',
            $now,

        ]);

        $url = $this->url->create('');
        $this->response->redirect($url);

    }


    public function viewAction($id=null)
    {
        $this->answers = new \Anax\Answer\Answer();
        $this->answers->setDI($this->di);

        $this->comments = new \Anax\Comment\Comment();
        $this->comments->setDI($this->di);

        $this->di->theme->setTitle("View answer");

        $answers = $this->answers->find($id);
        $comments = $this->comment->findAll();

        if($answers)
        {
        $type='answer';

        $this->views->add('answer/view', [
            'answers' => $answers,
            'id'     => $id,

        ]);

        $this->views->add('comment/list-all', [
            'comments' => $comments,
            'id'     => $id,
            'type'  => $type,

        ]);

        $this->dispatcher->forward([
            'controller' => 'commentform',
            'action'     => '',
            'params'     => [$id, $type],

        ]);
    }else {
        $url = $this->url->create('question/list');
        $this->response->redirect($url);
    }

    }


    public function voteAction($id=null, $idQuestion=null, $vote)
    {
        $this->answers = new \Anax\Answer\Answer();
        $this->answers->setDI($this->di);

        $theAnswer=$this->answers->find($id);

        if($vote=='up')
        {
            $this->answers->save([
                'id'    => $theAnswer->id,
                'score' => $theAnswer->score+1,
            ]);
        }
        else if($vote=='down')
        {
            $this->answers->save([
            'id'    => $theAnswer->id,
            'score' => $theAnswer->score-1,
        ]);
        }


            $url = $this->url->create('question/id/'. $idQuestion);
            $this->response->redirect($url);
    }

    public function sortupvoteAction($idQuestion=null){

        $this->answers = new \Anax\Answer\Answer();
        $this->answers->setDI($this->di);


        $sortedAnswer= $this->answers->sortCreated($idQuestion);

    }


    public function sortcreateAction($idQuestion) {

        $this->answers = new \Anax\Answer\Answer();
        $this->answers->setDI($this->di);

        $sortedAnswer= $this->answers->sortCreated($idQuestion);

        $this->views->add('answer/list-all', [
            'answers' => $sortedAnswer,
            'id'     => $idQuestion,

        ]);




    }

    public function editAction($id)
    {
        $this->theme->setTitle("Edit answer");
        $this->answer = new \Anax\Answer\Answer();
        $this->answer->setDI($this->di);

        $answer = $this->answer->find($id);
        if($answer){
            $this->dispatcher->forward([
                'controller' => 'answerform',
                'action'     => 'update',
                'params'     => [$answer],
            ]);

        }else{
            $url = $this->url->create('');
            $this->response->redirect($url);
        }


    }

    public function updateemailAction($newEmail, $oldEmail)
    {
        $this->answer = new \Anax\Answer\Answer();
        $this->answer->setDI($this->di);

        $allanswers= $this->answer->findAll();

        foreach ($allanswers as $answer) {

            if($answer->email==$oldEmail)
            {
                $this->answer->save([
                    'id'           => $answer->id,
                    'email'        => $newEmail,
                ]);

            }
        }

    }

    public function markAction($id=null, $idQuestion=null)
    {
        $this->answer = new \Anax\Answer\Answer();
        $this->answer->setDI($this->di);

        $this->answer->find($id);

        $this->answer->save([
            'id'           => $id,
            'accepted'        => 'yes',
        ]);

        $url = $this->url->create('question/id/'. $idQuestion);
        $this->response->redirect($url);

    }

    public function UnmarkAction($id=null, $idQuestion=null)
    {
        $this->answer = new \Anax\Answer\Answer();
        $this->answer->setDI($this->di);

        $this->answer->find($id);

        $this->answer->save([
            'id'           => $id,
            'accepted'        => 'no',
        ]);

        $url = $this->url->create('question/id/'. $idQuestion);
        $this->response->redirect($url);

    }





}
