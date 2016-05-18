
    <?php
    $currentUser = $this->di->user->getCurrentUser();
    ?>

<?php foreach ($taggedQuestions as $question) :

    //$user = $this->di->user->getByEmail($question->email);
?>
        <?='<br>'?>
        <h2><a href="<?=$this->url->create('question/id/' . $question->id) ?>"><?=$question->header?></a></h2>
        <p><b>Question: </b> <?=$question->question?></p>
        <p><b>Tags: </b><?=$question->tags?></p>





<?php endforeach;?>
