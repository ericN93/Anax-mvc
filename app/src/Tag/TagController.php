<?php

namespace Anax\Tag;

class TagController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;
    public function setupAction(){

        $this->db->dropTableIfExists('tag')->execute();

        $this->db->createTable(
            'tag',
            [
                'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
                'tag' => ['text', 'not null'],
                'score' => ['integer'],
                'idQ' => ['integer'],

            ]
            )->execute();

        $this->db->insert(
            'tag',
            ['tag', 'score', 'idQ']
        );

        //$now = gmdate('Y-m-d H:i:s');

        $this->db->execute([
            'movie',
            2,
            1,
        ]);

        $this->db->execute([
            'serie',
            1,
            2,
        ]);

        $url = $this->url->create('');
        $this->response->redirect($url);

    }

    public function addAction($tags = null, $idQ=null){
        $allTags = explode(",",$tags);
        $dbTags = $this->tag->findAll();


        $nrOfTags = count($allTags);


        foreach ($allTags as $tag) {

            $create = false;
            foreach($dbTags as $key => $dbtag)
            {
                if($tag != $dbtag->tag){
                    $create = true;
                }else{
                    $create = false;
                    $this->tag->save([
                        'score' => $dbtag->score+1,
                        'id'    => $dbtag->id,
                    ]);
                    break;
                }
            }
            if($create != false){
                $this->tag->create([
                    'tag' => $tag,
                    'score' => 1,
                    'idQ' => $idQ,
                ]);
            }
        }

        $url = $this->url->create('');
        $this->response->redirect($url);
    }

    public function listAction(){
        $tag = new \Anax\Tag\Tag();
        $tag->setDI($this->di);

        $this->theme->setTitle("Tags");

        $all = $this->tag->findAll();
        $this->views->add('tags/list', [
            'tags' => $all,
        ]);
    }


    public function tagAction($tag = null)
    {
        $guestion = $this->di->question->getUserQuestions();
        $array = null;
        $taggedQuestions =  [];
        foreach ($guestion as $key ) {
            $array = explode(",",$key->tags);
            foreach ($array as $qtag) {
                if($qtag == $tag){
                    array_push($taggedQuestions,$key);
                }
            }
        }

        $this->theme->setTitle('Tagged Questions');
        $this->views->add('question/taglist', [
            'taggedQuestions' => $taggedQuestions,
            'tag'             => $tag,
        ]);

    }

    public function frontAction()
    {

        $popTags= $this->tag->getPopTags();

        $this->views->add('front-page/tags', [
            'popTags' => $popTags,
        ]);

    }

    public function deleteTagsAction($question = null){
        $tags = explode(",",$question->tags);
        $dbTags = $this->tag->findAll();

        foreach($tags as $tag){
            foreach($dbTags as $dbTag){
                if($tag == $dbTag->tag){

                    if($dbTag->score > 1){
                        $this->tag->save([
                            'id' => $dbTag->id,
                            'score' => $dbTag->score - 1,
                        ]);
                    }else{
                        $this->tag->deleteTag($dbTag->id);
                    }


                }
            }
        }

    }
/*
            var_dump($temp['score']);
            if($temp['score']==1)
            {
                $tag->remove($temp['id']);
            }else{

                $this->tag->save([
                    'id'           => $temp['id'],
                    'score'     => $temp['score']-1
                ]);

            }
            */

}
