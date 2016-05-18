<?php
namespace Phpmvc\CUser;

/**
 * A controller for users and admin related events.
 *
 */
class UserController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

    private $user = 'admin';
    private $pass = 'admin';

    /*public function loginAction()
    {
        $this->users = new \Phpmvc\CUser\UserSession();
        $this->users->setDI($this->di);

        $all = $this->users->findAll();

        $this->theme->setTitle("List all users");
        $this->views->add('users/list-all', [
            'users' => $all,
            'title' => "View all users",
        ]);

    }*/

    public function addAction()
    {
        $isPosted = $this->request->getPost('doCreate');

        if (!$isPosted) {
            $this->response->redirect($this->request->getPost('redirect'));
        }
        if($this->user == $this->request->getPost('username') && $this->pass==$this->request->getPost('password'))
        {
        $newUser = [
            'username'   => $this->request->getPost('username'),
            'password'   => $this->request->getPost('password'),
            'timestamp' => time(),
            'ip'        => $this->request->getServer('REMOTE_ADDR'),
        ];
        $users = new \Phpmvc\CUser\UserSession();
        $users->setDI($this->di);
        $users->add($newUser);
        $this->response->redirect($this->request->getPost('redirect'));
    } else {
          $url = $this->url->create('');
          $this->response->redirect($url);
    }
    }

    public function getCurrentUser()
    {
        $users = new \Phpmvc\CUser\UserSession();
        $users->setDI($this->di);
        $User=$users->currentuser();
        return $User;
    }

    public function logoutAction()
    {


        $users = new \Phpmvc\CUser\UserSession();
        $users->setDI($this->di);
        $users->logout();
        $url = $this->url->create('');
        $this->response->redirect($url);
        //echo 'in usercontroller';
    }

}
