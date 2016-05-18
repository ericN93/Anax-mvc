<div class='comment'>
<h3>Comments</h3>

<?php
    foreach ($comments as $comment):

        $comment = $comment->getProperties();
        $email=$comment['email'];
        $idToUser = $this->di->user->findEmail($email);
        $currentUser=$this->di->user->getCurrentUser();
        $content = $this->textFilter->doFilter($comment['comment'],'shortcode, markdown, bbcode');
    ?>
    <?php if($type==$comment['type'] && $id==$comment['idQA'] ):?>
        <a href="<?=$this->url->create('comment/vote/' . $comment['id'] . "/". $comment['idQA'] .'/'.'up' )?>">  <i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
        <?=$comment['score']?>
        <a href="<?=$this->url->create('comment/vote/' . $comment['id'] . "/". $comment['idQA'] .'/'.'down')?>">  <i class="fa fa-thumbs-down" aria-hidden="true"></i></a></h3>
            <?php if($currentUser['email'] == $comment['email']):?>
            <div class='edit'>
                <a href="<?=$this->url->create('comment/edit/' . $comment['id']) ?>">edit</a>:
            </div>
            <?php endif;?>
            <div class='comment-content'>
                <p><a href="<?=$this->url->create('users/id/' . $idToUser->id) ?>"><?=$idToUser->name?></a>:</p>
            <p><?=$content?></p>
        </div>


<?php endif;?>

<?php endforeach; ?>
</div>
