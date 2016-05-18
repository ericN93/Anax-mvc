<div class='front-Q-title'>
<h1>All Tags</h1>
</div>
<?php foreach ($tags as $tag) :?>


    <div class='tags'>
        <b><i class="fa fa-thumb-tack" aria-hidden="true">-</i><a href="<?=$this->url->create('tag/tag/' . $tag->tag) ?>"><?=$tag->tag?>(<?=$tag->score?>)</a></b>
    </div>




<?php endforeach;?>
