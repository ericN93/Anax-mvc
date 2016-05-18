<?php
 namespace Anax\Comment;

class CommentController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

    public function setupAction()
    {
        //$app->db->setVerbose();

        $this->db->dropTableIfExists('comment')->execute();

        $this->db->createTable(
            'comment',
            [
                'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
                'idQA' =>['integer'],
                'type' => ['varchar(15)'],
                'comment' => ['text', 'not null'],
                'email' => ['varchar(80)'],
                'score' => ['integer'],
            ]
            )->execute();

        $this->db->insert(
            'comment',
            ['idQA', 'comment', 'email', 'type', 'score']
        );



        $this->db->execute([
            '1',
            'Lorem ipsum dolor sit amet, consectetur
             adipiscing elit. Donec odio nibh, tempus
              facilisis eleifend elementum, hendrerit
              eu metus. Suspendisse quis felis lobortis,
               tempor',
            'eric@dbwebb.se',
            'question',
            0,
        ]);

        $this->db->execute([
            '2',
            'Lorem ipsum dolor sit amet, consectetur
             adipiscing elit. Donec odio nibh, tempus
              facilisis eleifend elementum, hendrerit
              eu metus. Suspendisse quis felis lobortis,
               tempor',
            'eric@dbwebb.se',
            'question',
            0,
        ]);

        $this->db->execute([
            '2',
            'Lorem ipsum dolor sit amet, consectetur
             adipiscing elit. Donec odio nibh, tempus
              facilisis eleifend elementum, hendrerit
              eu metus. Suspendisse quis felis lobortis,
               tempor',
            'eric@dbwebb.se',
            'answer',
            0,
        ]);


        $url = $this->url->create('');
        $this->response->redirect($url);

    }

    public function viewAction()
    {
        $this->comment = new \Anax\Comment\Comment();
        $this->comment->setDI($this->di);

        $comments = $this->comment->findAll();

        $this->views->add('comment/list-all', [
            'comment' => $comments,


        ]);

    }


    public function resetAction()
    {
        $this->setupAction();
        $this->response->redirect($_SERVER['HTTP_REFERER']);
    }
    public function addAction()
    {
        $this->dispatcher->forward([
            'controller' => 'commentform',
            'action'     => '',
        ]);


    }
        public function editAction($id)
        {
            $this->theme->setTitle("Edit comment");
            $this->comment = new \Anax\Comment\Comment();
            $this->comment->setDI($this->di);

            $comment = $this->comment->find($id);
            if($comment){
                $this->dispatcher->forward([
                    'controller' => 'commentform',
                    'action'     => 'update',
                    'params'     => [$comment],
                ]);

            }else{
                $url = $this->url->create('');
                $this->response->redirect($url);
            }


        }

        /*public function saveCommentAction($id)
        {
            $comments = new \erna13\Comment\CommentsInSession();
            $comments->setDI($this->di);
            $key = $this->request->getPost('pagekey');

            $newComment = [
                'content' => $this->request->getPost('content'),
                'name' => $this->request->getPost('name'),
                'web' => $this->request->getPost('web'),
                'mail' => $this->request->getPost('mail'),
                'ip' => $this->request->getServer('REMOTE_ADDR'),
                ];
                $comments->update($id, $key, $newComment);

                $this->response->redirect($this->request->getPost('redirect'));
        }
        */

        public function removeAllAction()
        {

            $this->setupAction();
        }
        public function removeOneAction($id)
        {
            $this->comment = new \Anax\Comment\Comment();
            $this->comment->setDI($this->di);

            if (!isset($id)) {
                die("Missing id");
            }

            $this->comment->delete($id);
        }

        public function updateemailAction($newEmail, $oldEmail)
        {
            $this->comment = new \Anax\Comment\Comment();
            $this->comment->setDI($this->di);

            $allComments= $this->comment->findAll();

            foreach ($allComments as $comment) {
                //echo $oldEmail;
                if($comment->email==$oldEmail)
                {
                    $this->comment->save([
                        'id'           => $comment->id,
                        'email'        => $newEmail,
                    ]);

                }
            }

        }


        public function voteAction($id=null,$idQA, $vote)
        {
            $this->comment = new \Anax\Comment\Comment();
            $this->comment->setDI($this->di);

            $theComment=$this->comment->find($id);

            if($vote=='up')
            {
                $this->comment->save([
                    'id'    => $theComment->id,
                    'score' => $theComment->score+1,
                ]);
            }
            else if($vote=='down')
            {
                $this->comment->save([
                'id'    => $theComment->id,
                'score' => $theComment->score-1,
            ]);
            }

            if($theComment->type=='question')
            {
                $url = $this->url->create('question/id/'. $idQA);
                $this->response->redirect($url);
            } else {
                $url = $this->url->create('answer/view/'. $idQA);
                $this->response->redirect($url);
            }

        }

}
