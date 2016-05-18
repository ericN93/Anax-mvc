<h1><?=$title?></h1>

<?php
$currentUser=$this->di->user->getCurrentUser();
    foreach ($questions as $question) :
    $question = $question->getProperties();

    $content = $this->textFilter->doFilter($question['question'],'shortcode, markdown, bbcode');
    $header = $this->textFilter->doFilter($question['header'],'shortcode, markdown, bbcode');

    $all=$this->di->answer->findAll();
    $temp=0;
    foreach ($all as $answer1) {
        if($answer1->idQuestion==$question['id'])
        {
            $temp=$temp+1;
        }
    }

     //$gravatar = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($user['email']))) . '.jpg?s=80';
?>



    <div class='front-Q-title'>
  <h2><a href="<?=$this->url->create('question/id/' . $question['id']) ?>"><?=$header?></a></h2>
   </div>
   <a href="<?=$this->url->create('question/vote/' . $question['id']  .'/'.'up' )?>">  <i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
   <?=$question['score']?>
   <a href="<?=$this->url->create('question/vote/' . $question['id']  .'/'.'down')?>">  <i class="fa fa-thumbs-down" aria-hidden="true"></i></a></h3>
<div class='temp'>
 <p><b>Question: </b> <?=$content?></p>


 <p><b>Tags: </b><?=$question['tags']?></p>


 <p>Number of answers:<?=$temp?></p>
</div>

<?php endforeach; ?>

<?php

    $output = null;
    if($currentUser['name']!='')
    {
    $output .= "<a href=" . $this->url->create('question/create/') . ">Ask Question</a> ";
} else{
    $output .="If you want to ask a question <a href=" . $this->url->create('users/login/') . ">login</a>";
    $output .=" or  <a href=" . $this->url->create('users/add/') . ">Sign up</a>";
}
?>

    <?=$output?>
