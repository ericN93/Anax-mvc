<div class='front-Q-title'>
<h1>Populuer Tags</h1>
</div>
<?php
    foreach ($popTags as $tags) :
?>

<div class='front-T-question'>
  <h5><a href="<?=$this->url->create('tag/tag/' . $tags->tag) ?>"><?=$tags->tag?></a></h5>
</div>

<?php endforeach; ?>
