<?php
$return='';
$allert='';
global $database;

if (isset($_POST['login'],$_POST['password'])) {
    $_POST['password']=md5($_POST['password']);
    $user=$database->findOne("select id,login,role from users where status='active' and login='{$_REQUEST['login']}' and password='{$_POST['password']}'");
    if (!isset($user->id)) {
        $allert='<div class="alert alert-danger">Не верный логин или пароль</div>';
    }else{
        $_SESSION['user']=$user;
        redirect('/profile');
        
    }




}{

$return=inputElement('login','Логин');
$return.=inputElement('password','Пароль','','password');


}?>

<div class="row">
    <div class="col-md-4">
        <form action="" method='post'>
            <h2>Вход</h2>
            <?=$allert?>
            <?=$return?>
            <input type="submit" value="Вход" class="btn btn-danger">
        </form>
    </div>

</div>