<?php
namespace Anax\Answer;
/**
 * Model for Users.
 *
 */
class Answer extends \Anax\MVC\CDatabaseModel
{

        public function orderBy($order)
    {
        if($order == "Oldest"){
            $this->db->select()
                     ->from($this->getSource())
                     ->orderBy("created asc");
            $this->db->execute();
            return $this->db->fetchAll();
        }else if($order == "Newest"){
            $this->db->select()
                     ->from($this->getSource())
                     ->orderBy("created desc");
            $this->db->execute();
            return $this->db->fetchAll();
        }else{
            $this->db->select()
                     ->from($this->getSource())
                     ->orderBy("score desc");
            $this->db->execute();
            return $this->db->fetchAll();
        }

    }

}
