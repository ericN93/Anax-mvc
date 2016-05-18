
<?php

    $user = $user->getProperties();
    //$currentUser=$this->users->loggedIn();
    $gravatar = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($user['email']))) . '.jpg?s=80';

?>

<div>
  <img class="gavatar" src="<?=$gravatar?>" title="" style="border-radius:50px" ></a></h2>
  <p><b>Användarnamn:</b> <?=$user['acronym']?></p>
  <p><b>E-post:</b> <?=$user['email']?></p>
  <p><b>Namn:</b> <?=$user['name']?></p>
  <p><b>Skapad:</b> <?=$user['created']?></p>
  <p><b>Poäng:</b> <?=$user['score']?>
</div>


<?php
$allQuestions=$this->di->question->findAll();
//var_dump($allQuestions);
$usersQuestions="Frågor ställda:";
foreach ($allQuestions as $question) {
    if($question->email==$user['email'])
    {
        //$question = $question->getProperties();
        $usersQuestions.= "<a href=" . $this->url->create('question/id/' . $question->id) . "> ". $question->header . "</a> | ";
    }
}
$usersQuestions.="<br>";

$allAnswers=$this->di->answer->findAll();
$usersAnswers="Svar:";
foreach ($allAnswers as $answer) {
    if($answer->email==$user['email'])
    {
        $answer = $answer->getProperties();
        $temp=substr ( $answer['answer'] , 0 , 10);

        "<a href=" . $this->url->create('question/id/' . $question->id) . "> ". $question->header . "</a> | ";
        $usersAnswers.= "<a href=" . $this->url->create('answer/view/' . $answer['id']) . "> ". $temp ."...</a> | ";

    }
}



$currentUser=$this->di->user->getCurrentUser();

    $output = null;
    if($currentUser['name'] == $user['name'] )
    {
    $output .= "<a href=" . $this->url->create('users/update/' . $user['id']) . ">Uppdatera</a> | ";
    }


?>
<?=$usersAnswers?>
<?='</br>'?>
<?=$usersQuestions?>
<?=$output?>
<hr>
