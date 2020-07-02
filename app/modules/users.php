<?php
$return='';
$allert='';
global $database;
$dbResult=[];
$action=$_REQUEST['action']?? 'list';


if($action=='add'|$action=='edit'){
    
    $id =(int)isset($_REQUEST['id'])? $_REQUEST['id']:0;

    if (isset($_POST['login'],$_POST['password'],$_POST['fio'])) 
    {
        $_POST['password']=md5($_POST['password']);
        switch ($action) 
        {
            case 'add':
                    $dbResult=$database->add("insert into users(fio,login,password) values('{$_POST['fio']}','{$_POST['login']}','{$_POST['password']}')");      
                break;
            case 'edit':
                    $dbResult=$database->edit("update users set status='{$_REQUEST['status']}', fio='{$_POST['fio']}', login='{$_POST['login']}', password='{$_POST['password']}' where id={$id}");      
                break;
        }
    }
    $return=inputElement('fio','ФИО');
    $return.=inputElement('login','Логин');
    $return.=inputElement('password','Пароль','','password');
    if($id!=0){

        $dbedit=$database->findOne(
            'select * from users where id='.$id
        );
        $return=inputElement('fio','ФИО',$dbedit->fio);
        $return.=inputElement('login','Логин',$dbedit->login);
        $return.=inputElement('password','Пароль','','password');
        $return.=selectElement('status','Статус',[
            ['name'=>'Активный','value'=>'active'],
            ['name'=>'Не активный','value'=>'ban']


        ],$dbedit->STATUS);
 
    }
    if (isset($dbResult['msg'])) {
        $allert="<div class='alert alert-danger'>".$dbResult['msg']."</div>";
    }
    
    
    
echo '    <div class="row">
<div class="col-md-4">
    <form action="" method="post">
        <h2>Пользователи</h2>
        '.$allert.'
        '.$return.'
        
        <input type="submit" value="Сохранить" class="btn btn-danger">
        <a href="/users" class="btn btn-primary">Назад</a>
    </form>
</div>
</div>';

}
if ($action=='list') {
    

    $items=$database->findMore('select * from users');
    $table='<a href="/users?action=add" class="btn btn-danger">Новый</a>';
    $table.='<table class="table table-dark">';
    $table.='<tr>';
    $table.='<th>';
    $table.='№';
    $table.='</th>';
    $table.='<th>';
    $table.='ФИО';
    $table.='</th>';
    $table.='<th>';
    $table.='Логин';
    $table.='</th>';
    $table.='<th>';
    $table.='Роль';
    $table.='</th>';
    $table.='<th>';
    $table.='Статус';
    $table.='</th>';
    $table.='</tr>';
    foreach ($items as $item) {
        $table.='<tr>';
        $table.='<td>';
        $table.='<a href="/users?action=edit&id='.$item['id'].'">'.$item['id'].'</a>';
        $table.='</td>';
        $table.='<td>';
        $table.=$item['fio'];
        $table.='</td>';
        $table.='<td>';
        $table.=$item['login'];
        $table.='</td>';
        $table.='<td>';
        $table.=$item['role'];
        $table.='</td>';
        $table.='<td>';
        $table.=$item['STATUS']=='active'?'Активный':'Не активный';
        $table.='</td>';
        $table.='</tr>';
    }
echo '<div class="row pt-5">'.$table.'</div>';
}

if ($action=='logout') {
    session_destroy();
    redirect('/auth');
}