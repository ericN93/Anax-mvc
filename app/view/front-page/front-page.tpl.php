
<div class='front-Q-title'>
<h1> Newest Questions</h1>
</div>
<?php
    foreach ($newestQ as $question) :
?>

<div class='front-Q-question'>
    <div class ='front-Q-question-title'>
        <h3><a href="<?=$this->url->create('question/id/' . $question->id) ?>"><?=$question->header?></a></h3>
    </div>
    <div class='temp'>
 <p><b>Question: </b> <?=$question->question?></p>
 </div>
</div>

<?php endforeach; ?>
