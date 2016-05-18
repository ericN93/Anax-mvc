<?php
namespace Anax\Question;
/**
 * Model for Users.
 *
 */
class Question extends \Anax\MVC\CDatabaseModel
{
    public function getUserQuestions()
    {
        return $this->di->question->findAll();
    }

    public function getNewestQuestions()
    {
        $this->questions = new \Anax\Question\Question();
        $this->questions->setDI($this->di);

        $this->db->select()
                ->from($this->getSource())
                ->orderBy("created desc")
                ->limit(3);
       $this->db->execute();
       return $this->db->fetchAll();

    }

}
