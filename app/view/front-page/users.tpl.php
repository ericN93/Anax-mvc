<div class='front-Q-title'>
<h1>Active Users</h1>
</div>
<?php
    foreach ($activeUsers as $user) :
    $email=$user->email;
    $idToAsker = $this->di->user->findEmail($email);
?>

<div id='ActiveUsers'>
  <b>Active: </b><a href="<?=$this->url->create('users/id/' . $idToAsker->id) ?>"><?=$idToAsker->name?>(<?=$idToAsker->score?>)</a>:
</div>

<?php endforeach; ?>
