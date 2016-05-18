<h1>Answers</h1>
<div class='answer-search'>
<?php
$sortNewest = "<a href=" . $this->url->create('question/id/' . $id . '/' . 'Newest') . ">Newest</a> ";
$sortOldest = "<a href=" . $this->url->create('question/id/' . $id . '/' . 'Oldest') . ">Oldest</a> ";
$sortScore = "<a href=" . $this->url->create('question/id/' . $id . '/' . 'Score') . ">Score</a> ";
echo $sortScore;
echo $sortNewest;
echo $sortOldest;
$currentUser=$this->di->user->getCurrentUser();
?>
</div>
<?php
    foreach ($answers as $answer) :
    $email=$answer->email;
    $content = $this->textFilter->doFilter($answer->answer,'shortcode, markdown, bbcode');
    $idToUser = $this->di->user->findEmail($email);


    $temp='0';
    $all=$this->di->comment->findAll();
    foreach ($all as $comment) {
        if($comment->type=='answer' && $comment->idQA==$answer->id)
        {
            $temp=$temp+1;
        }
    }


     //$gravatar = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($user['email']))) . '.jpg?s=80';
?>



<?php if($id == $answer->idQuestion):?>

<div class='answer'>
    <?php if($currentUser['email']==$emailQ):?>
          <?php if($answer->accepted!='yes'):?>
              <span style ="float:right">
                  <p><a href="<?=$this->url->create('answer/mark/' . $answer->id .'/'. $answer->idQuestion)  ?>">Accept answer</a></p>
              </span>
          <?php else:?>
              <span style ="float: right">
                  <p><a href="<?=$this->url->create('answer/unmark/' . $answer->id .'/'. $answer->idQuestion)  ?>">UnAccept answer</a></p>
              </span>
          <?php endif;?>
    <?php endif;?>
    
    <h3><b>Answer by:</b> <a href="<?=$this->url->create('users/id/' . $idToUser->id) ?>"><?=$idToUser->name?></a></h3>

      <?php if($answer->accepted=='yes'):?>
          <span style="color: green">
      <i class="fa fa-check" aria-hidden="true"></i>
  </span>
      <?php endif;?>

  <a href="<?=$this->url->create('answer/vote/' . $answer->id . '/' . $answer->idQuestion .'/'.'up' )?>">  <i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
  <?=$answer->score?>
  <a href="<?=$this->url->create('answer/vote/' . $answer->id . '/' . $answer->idQuestion.'/'.'down')?>">  <i class="fa fa-thumbs-down" aria-hidden="true"></i></a></h3>
      <?=$content?><a href="<?=$this->url->create('answer/view/' . $answer->id) ?>">View All Comments: <?=$temp?> </a>

</div>
<?php endif;?>
<?php endforeach; ?>
