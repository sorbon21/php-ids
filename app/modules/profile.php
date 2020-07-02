<?php
$return='';
$allert='';
global $database;
$user=$_SESSION['user'];
$allert='<div class="alert alert-success">Добро пожаловать в систему '.$user->login.'</div>';
?>
<div class="row">
    <div class="col-md-12">
            <?=$allert?>
            
            
    </div>

</div>