<?php
namespace Anax\Users;

/**
 * A controller for users and admin related events.
 *
 */
class UsersController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;


/**
 * List all users.
 *
 * @return void
 */

 public function setupAction(){
        //$this->db->setVerbose();
        $tableName = 'user';
        $table = [
            'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'acronym' => ['varchar(20)', 'not null'],
            'email' => ['varchar(80)'],
            'name' => ['varchar(80)'],
            'password' => ['varchar(255)'],
            'created' => ['datetime'],
            'type'  => ['varchar(80)'],
            'score' => ['integer'],
        ];

        $res = $this->users->setupTable($tableName,$table);

        $url = $this->url->create('');
        $this->response->redirect($url);



    }
public function listAction()
{
    $this->users = new \Anax\Users\User();
    $this->users->setDI($this->di);

    $all = $this->users->findAll();

    $this->theme->setTitle("List all users");
    $this->views->add('users/list-all', [
        'users' => $all,
        'title' => "View all users",
    ]);
}

public function inactiveAction()
{
    $allInactive = $this->users->query()
        ->where('active IS NULL')
        ->andWhere('deleted is NULL')
        ->execute();

    $this->theme->setTitle("Users that are Inactive");
    $this->views->add('users/list-all', [
        'users' => $allInactive,
        'title' => "Users that are Inactive",
    ]);

}

public function trashcanAction()
{
    $allInactive = $this->users->query()
        ->where('deleted IS NOT NULL')
        ->execute();

    $this->theme->setTitle("Users that are in trashcan");
    $this->views->add('users/list-all', [
        'users' => $allInactive,
        'title' => "Users that are in trashcan",
    ]);

}

public function updateAction($id=null, $update=false)
{
    $this->users = new \Anax\Users\User();
    $this->users->setDI($this->di);

    $user = $this->users->find($id);
    //$user = $user->getProperties();
        $this->dispatcher->forward([
        'controller' => 'userform',
        'action'     => '',
        'params'     => [$user, $id],
    ]);



}

public function resetAction()
{
    $this->setupAction();
    $this->response->redirect($_SERVER['HTTP_REFERER']);
}




/**
 * List user with id.
 *
 * @param int $id of user to display
 *
 * @return void
 */
public function idAction($id = null)
{
    $this->users = new \Anax\Users\User();
    $this->users->setDI($this->di);


    $user = $this->users->find($id);

    $this->theme->setTitle("View user with id");
    $this->views->add('users/view', [
        'user' => $user,
    ]);
}



/**
 * Initialize the controller.
 *
 * @return void
 */

public function initialize()
{
    $this->users = new \Anax\Users\User();
    $this->users->setDI($this->di);
}

/**
 * Add new user.
 *
 * @param string $acronym of user to add.
 *
 * @return void
 */
public function addAction( $acronym = null)
{

            $this->dispatcher->forward([
                'controller' => 'userform',
                'action'     => '',
            ]);
}


/**
 * Delete user.
 *
 * @param integer $id of user to delete.
 *
 * @return void
 */
public function deleteAction($id = null)
{
    if (!isset($id)) {
        die("Missing id");
    }

    $res = $this->users->delete($id);

    $url = $this->url->create('users/list');
    $this->response->redirect($url);
}

/**
 * Delete (soft) user.
 *
 * @param integer $id of user to delete.
 *
 * @return void
 */
public function softDeleteAction($id = null)
{
    if (!isset($id)) {
        die("Missing id");
    }

    $now = gmdate('Y-m-d H:i:s');

    $user = $this->users->find($id);

    $user->deleted = $now;
    $user->save();

    $url = $this->url->create('users/id/' . $id);
    $this->response->redirect($url);
}


public function undoSoftDeleteAction($id = null)
{
    if (!isset($id)) {
        die("Missing id");
    }

    $now = gmdate('Y-m-d H:i:s');

    $user = $this->users->find($id);

    $user->deleted = null;
    $user->save();

    $url = $this->url->create('users/id/' . $id);
    $this->response->redirect($url);
}

/**
 * List all active and not deleted users.
 *
 * @return void
 */
public function activeAction()
{
    $all = $this->users->query()
        ->where('active IS NOT NULL')
        ->andWhere('deleted is NULL')
        ->execute();

    $this->theme->setTitle("Users that are active");
    $this->views->add('users/list-all', [
        'users' => $all,
        'title' => "Users that are active",
    ]);
}
public function deactivateAction($id = null)
{
    if (!isset($id)) {
        die("Missing id");
    }
    $now = gmdate('Y-m-d H:i:s');
    $user = $this->users->find($id);
    $user->active = null;
    $user->save();
    $url = $this->url->create('users/list');
    $this->response->redirect($url);
}
public function undodeactivateAction($id = null)
{
    if (!isset($id)) {
        die("Missing id");
    }
    $now = gmdate('Y-m-d H:i:s');
    $user = $this->users->find($id);
    $user->active = $now;
    $user->save();
    $url = $this->url->create('users/list');
    $this->response->redirect($url);
}
public function loginAction($succes=false, $acronym=null, $passWord=null) {

    $currentUser=$this->users->loggedIn();

    if($currentUser!=false){
        $url = $this->url->create('users/id/' . $currentUser['id']);
        $this->response->redirect($url);

    }else{

        if(!$succes) {
            $this->dispatcher->forward([
                'controller'=> 'userform',
                'action' => 'login',

            ]);
        }else{
            if($this->users->login($acronym,$passWord)){
                $user=$this->users->getByAcronym($acronym);
                $url=$this->url->create('users/id/'. $user->id);
                $this->response->redirect($url);
            }
        }
    }
}

public function logoutAction()
{
    $currentUser=$this->users->getCurrentUser();

    if($currentUser != false){
        $this->users->loggedOut();
        $url=$this->url->create('');
        $this->response->redirect($url);
    } else {
        $url=$this->url->create('');
        $this->response->redirect($url);
    }
}

public function frontAction(){

    $this->users = new \Anax\Users\User();
    $this->users->setDI($this->di);

    $activeUsers=$this->users->getActiveUsers();


    $this->views->add('front-page/users', [
        'activeUsers' => $activeUsers,
    ]);

}


}
