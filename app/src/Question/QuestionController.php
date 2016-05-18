<?php
namespace Anax\Question;

/**
 * A controller for users and admin related events.
 *
 */
class QuestionController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

    public function setupAction(){

        $this->db->dropTableIfExists('question')->execute();

        $this->db->createTable(
            'question',
            [
                'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
                'header' => ['varchar(255)', 'not null'],
                'question' => ['text', 'not null'],
                'email' => ['varchar(80)', 'not null'],
                'tags'   =>['text'],
                'created' =>['datetime'],
                'score' =>['integer'],
            ]
            )->execute();

        $this->db->insert(
            'question',
            ['header', 'question', 'email', 'tags', 'created', 'score']
        );

        $now = gmdate('Y-m-d H:i:s');

        $this->db->execute([
            'header1',
            'Lorem ipsum dolor sit amet, consectetur
             adipiscing elit. Donec odio nibh, tempus
              facilisis eleifend elementum, hendrerit
              eu metus. Suspendisse quis felis lobortis,
               tempor tellus in, dapibus elit. Aliquam felis
                urna, congue in tempus quis, lobortis efficitur
                 nisi. Nunc ornare magna at purus fringilla, vitae
                  iaculis tellus gravida. Proin ornare lectus vel
                  enim iaculis, sed sagittis justo ultricies.
                  Proin id metus at enim rhoncus cursus in ac urna.
                  Donec eu euismod ante. Sed eu leo consequat,
                  fringilla est ut, ultrices mi. Etiam in mollis nibh,
                  et euismod mauris. Aliquam erat volutpat. Ut quis dui dictum,
                  aliquet tellus ac, accumsan dolor. Proin pretium dictum magna
                  vel suscipit. Integer eget mi erat. In vehicula molestie finibus.
                   Nunc dapibus, est vel ornare hendrerit, nisi velit finibus urna,
                    nec viverra enim arcu eu enim. Nunc sit amet leo non sem lobortis vestibulum.',
            'eric@dbwebb.se',
            'movie',
            $now,
            0,
        ]);

        $this->db->execute([
            'header2',
            'Lorem ipsum dolor sit amet, consectetur
             adipiscing elit. Donec odio nibh, tempus
              facilisis eleifend elementum, hendrerit
              eu metus. Suspendisse quis felis lobortis,
               tempor tellus in, dapibus elit. Aliquam felis
                urna, congue in tempus quis, lobortis efficitur
                 nisi. Nunc ornare magna at purus fringilla, vitae
                  iaculis tellus gravida. Proin ornare lectus vel
                  enim iaculis, sed sagittis justo ultricies.
                  Proin id metus at enim rhoncus cursus in ac urna.
                  Donec eu euismod ante. Sed eu leo consequat,
                  fringilla est ut, ultrices mi. Etiam in mollis nibh,
                  et euismod mauris. Aliquam erat volutpat. Ut quis dui dictum,
                  aliquet tellus ac, accumsan dolor. Proin pretium dictum magna
                  vel suscipit. Integer eget mi erat. In vehicula molestie finibus.
                   Nunc dapibus, est vel ornare hendrerit, nisi velit finibus urna,
                    nec viverra enim arcu eu enim. Nunc sit amet leo non sem lobortis vestibulum.',
            'eric@dbwebb.se',
            'serie',
            $now,
            0,
        ]);

        $this->db->execute([
            'header3',
            'Lorem ipsum dolor sit amet, consectetur
             adipiscing elit. Donec odio nibh, tempus
              facilisis eleifend elementum, hendrerit
              eu metus. Suspendisse quis felis lobortis,
               tempor tellus in, dapibus elit. Aliquam felis
                urna, congue in tempus quis, lobortis efficitur
                 nisi. Nunc ornare magna at purus fringilla, vitae
                  iaculis tellus gravida. Proin ornare lectus vel
                  enim iaculis, sed sagittis justo ultricies.
                  Proin id metus at enim rhoncus cursus in ac urna.
                  Donec eu euismod ante. Sed eu leo consequat,
                  fringilla est ut, ultrices mi. Etiam in mollis nibh,
                  et euismod mauris. Aliquam erat volutpat. Ut quis dui dictum,
                  aliquet tellus ac, accumsan dolor. Proin pretium dictum magna
                  vel suscipit. Integer eget mi erat. In vehicula molestie finibus.
                   Nunc dapibus, est vel ornare hendrerit, nisi velit finibus urna,
                    nec viverra enim arcu eu enim. Nunc sit amet leo non sem lobortis vestibulum.',
            'eric@dbwebb.se',
            'movie',
            $now,
            0,
        ]);


        $url = $this->url->create('');
        $this->response->redirect($url);

    }

   public function createAction()
   {
       $this->dispatcher->forward([
           'controller' => 'questionform',
           'action'     => '',
           'params'
       ]);
   }

   public function deleteAction($id=null)
   {
       if (!isset($id)) {
           die("Missing id");
       }

       $res = $this->questions->delete($id);

       $url = $this->url->create('question/list');
       $this->response->redirect($url);

   }

   public function updateAction($id=null)
   {

   }


   public function idAction($id=null, $order=null)
   {

       $this->questions = new \Anax\Question\Question();
       $this->questions->setDI($this->di);

       $this->answers = new \Anax\Answer\Answer();
       $this->answers->setDI($this->di);

       $this->comments = new \Anax\Comment\Comment();
       $this->comments->setDI($this->di);

       $question = $this->questions->find($id);
       $comments = $this->comment->findAll();
       $answers = $this->answers->orderby($order);

       $email=$question->email;

       if($question)
       {
       $type = 'question';
       $this->theme->setTitle("View");

       $this->views->add('question/view', [
           'question' => $question,
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

       $this->views->add('answer/list-all', [
           'answers' => $answers,
           'id'     => $id,
           'emailQ' =>$email,

       ]);

       $this->dispatcher->forward([
           'controller' => 'answerform',
           'action'     => '',
           'params'     => [$id],

       ]);
   } else {
       $url = $this->url->create('question/list');
       $this->response->redirect($url);

   }




   }


   public function listAction()
   {
       $this->questions = new \Anax\Question\Question();
       $this->questions->setDI($this->di);

       $all = $this->questions->findAll();

       $this->theme->setTitle("List all Question");
       $this->views->add('question/list-all', [
           'questions' => $all,
           'title' => "View all Question",
       ]);
   }

   public function frontAction(){

       $this->questions = new \Anax\Question\Question();
       $this->questions->setDI($this->di);

       $newestQuestions=$this->questions->getNewestQuestions();

       $this->theme->setTitle("Home");


       $this->views->add('front-page/front-page', [
           'newestQ' => $newestQuestions,
       ]);

   }

   public function orderby($order)
   {
       if($order=null)
       {
           return $this->answer->findAll();
       }else{
           return $thtis->answer->orderby($order);
       }
   }

   public function editAction($id)
   {
       $this->theme->setTitle("Edit comment");
       $this->question = new \Anax\Question\Question();
       $this->question->setDI($this->di);

       $question = $this->question->find($id);
       if($question){
           $this->dispatcher->forward([
               'controller' => 'questionform',
               'action'     => 'update',
               'params'     => [$question],
           ]);

       }else{
           $url = $this->url->create('');
           $this->response->redirect($url);
       }


   }

   public function updateemailAction($newEmail, $oldEmail)
   {
       $this->question = new \Anax\Question\Question();
       $this->question->setDI($this->di);

       $allQuestions= $this->question->findAll();

       foreach ($allQuestions as $question) {
           //echo $oldEmail;
           if($question->email==$oldEmail)
           {
               $this->question->save([
                   'id'           => $question->id,
                   'email'        => $newEmail,
               ]);

           }
       }

   }

   public function voteAction($id=null, $vote)
   {
       $this->question = new \Anax\Question\Question();
       $this->question->setDI($this->di);

       $theQuestion=$this->question->find($id);

       if($vote=='up')
       {
           $this->question->save([
               'id'    => $theQuestion->id,
               'score' => $theQuestion->score+1,
           ]);
       }
       else if($vote=='down')
       {
           $this->question->save([
           'id'    => $theQuestion->id,
           'score' => $theQuestion->score-1,
       ]);
       }


           $url = $this->url->create('question/list');
           $this->response->redirect($url);
   }

}
