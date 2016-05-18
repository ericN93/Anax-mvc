<?php
namespace Anax\Tag;
/**
 * Model for Users.
 *
 */
class Tag extends \Anax\MVC\CDatabaseModel
{

    public function getPopTags() {
        $this->tags = new \Anax\Tag\Tag();
        $this->tags->setDI($this->di);

        $this->db->select()
                ->from($this->getSource())
                ->orderBy("score desc")
                ->limit(3);
                $this->db->execute();
   return $this->db->fetchAll();
   }

   public function findTag($tag)
   {

       $this->db->select()
                   ->from($this->getSource())
                   ->where("tag = ?");
               $this->db->execute([$tag]);
       return $this->db->fetchInto($this);
   }

   public function deleteTag($id){
        $this->db->delete( $this->getSource(),
                        'id = ?' ); 
        return $this->db->execute([$id]); }

}
