<?php
namespace Anax\Users;
/**
 * Model for Users.
 *
 */
class User extends \Anax\MVC\CDatabaseModel
{
    public function setupTable($tableName, $table){

         $this->db->dropTableIfExists($tableName)->execute();
         $this->db->createTable($this->getSource(),$table)->execute();

         $this->db->insert(
             'user',
             ['acronym', 'email', 'name', 'password', 'created', 'score']
         );

         $now = gmdate('Y-m-d H:i:s');

         $this->db->execute([
             'Erna13',
             'eric@dbwebb.se',
             'Eric',
             password_hash('eric1337', PASSWORD_DEFAULT),
             $now,
             '22'
         ]);

         $this->db->execute([
             'doe',
             'doe@dbwebb.se',
             'John/Jane Doe',
             password_hash('doe', PASSWORD_DEFAULT),
             $now,
             '1'
         ]);

    }

    public function login($acronym, $password)
    {
        $currentUser = $this->getByAcronym($acronym);
        if(isset($currentUser->acronym)){
            if(password_verify($password,$currentUser->password))
            {
                $this->session->set(
                'user',
                [
                    'id' => $currentUser->id,
                    'acronym' =>$currentUser->acronym,
                    'email' =>$currentUser->email,
                    'name' =>$currentUser->name,
                    'created' =>$currentUser->created,
                    'type' =>$currentUser->type,
                    'score' =>$currentUser->score,
                ]
            );
            return true;
        }else{
            session_unset();
            return false;
        }

        }
    }

    public function getByAcronym($acronym=null){
        $user=$this->findAcronym($acronym);
        return $user;
    }
    public function getByEmail($email=null){

        $user=$this->findEmail($email);
        return $user;
    }

    public function loggedIn(){
        $currentUser = $this->session->get('user');
        if(isset($currentUser))
        {
            return $currentUser;
        }else {
            return false;
        }

    }
    public function loggedOut(){
        $currentUser= $this->session->get('user');
        session_unset($currentUser);
    }
    public function getCurrentUser()
    {
        $currentUser=$this->loggedIn();

        return $currentUser;
    }

    public function getActiveUsers()
    {
        $this->users = new \Anax\Users\User();
        $this->users->setDI($this->di);

        $this->db->select()
                ->from($this->getSource())
                ->orderBy("score desc")
                ->limit(3);
       $this->db->execute();
       return $this->db->fetchAll();

    }

}
