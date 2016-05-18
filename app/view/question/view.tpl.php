<?php

$questions = $question->getProperties();
$currentUser=$this->di->user->getCurrentUser();
$email=$questions['email'];
$idToAsker = $this->di->user->findEmail($email);


?>
<?php
/*
$output = null;
if($currentUser['name']!='')
{
$output .= "<a href=" . $this->url->create('question/create/') . ">Ask Question</a> ";
} else{
$output .="If you want to ask a question <a href=" . $this->url->create('users/login/') . ">login</a>";
$output .=" or  <a href=" . $this->url->create('users/add/') . ">Sign up</a>";
}
*/
?>



    <div class='front-Q-title'>
  <h1>Rubrik: <?=$questions['header']?></h1>
  </div>

  <?php if($currentUser['email'] == $questions['email']): ?>
    <div class ='edit'>
  <a href="<?=$this->url->create('question/edit/' . $questions['id']) ?>">edit</a>
  <br>
  </div>
  <?php endif;?>
  <h3><b>Question Asked by </b><a href="<?=$this->url->create('users/id/' . $idToAsker->id) ?>"><?=$idToAsker->name?></a></h3>
   <div class='question-content'>
    <h4><?=$questions['question']?></h4>
        <h5>Tags:<?=$questions['tags']?></h5>
    </div>

    <!--<?=$output?>-->
