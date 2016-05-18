<?php
$answer = $answers->getProperties();
$email=$answer['email'];
$gravatar = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($answer['email']))) . '.jpg?s=80';
$currentUser=$this->di->user->getCurrentUser();
$idToAsker = $this->di->user->findEmail($email);

?>




    <div class='answer'>
        <img class="gavatar" src="<?=$gravatar?>" title="" style="border-radius:50px" >
    <h1><b>Answered By:</b><a href="<?=$this->url->create('users/id/' . $idToAsker->id) ?>"><?=$idToAsker->name?></a></h1>

    <?php if($currentUser['email'] == $answer['email']):?>
        <div class='edit'>
            <a href="<?=$this->url->create('answer/edit/' . $answer['id']) ?>">edit</a>
            <h5><a href="<?=$this->url->create('question/id/' . $answer['idQuestion']) ?>">Back To Question</a></h5>
            </div>
    <?php endif;?>

  <p> <?=$answer['answer']?></p>
  </div>
