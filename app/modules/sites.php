<?php
$return='';
$allert='';
global $database;
$dbResult=[];
$action=$_REQUEST['action']?? 'list';

$user=$_SESSION['user']??NULL;
$is_client=isset($_REQUEST['list'])&&$_REQUEST['list']=='my'? true:false;


if($action=='add'|$action=='edit'){
    
    $id =(int)isset($_REQUEST['id'])? $_REQUEST['id']:0;

    if (isset($_POST['url'],$_POST['description'])) 
    {
        
        switch ($action) 
        {
            case 'add':
                    $dbResult=$database->add("insert into sites(url,description,user_id) values('{$_POST['url']}','{$_POST['description']}',{$user->id})");      
                break;
            case 'edit':
                    $dbResult=$database->edit("update sites set url='{$_POST['url']}', description='{$_POST['description']}' where id={$id}");      
                break;
        }
    }
    $return=inputElement('url','Ссылка на сайт','','url');
    $return.=inputElement('description','Описания сайта','','textarea');
    
    if($id!=0){

        if ($is_client) {
            $dbedit=$database->findOne(
                'select * from sites where user_id='.$user->id.' and id='.$id 
            );
        }else{
            $dbedit=$database->findOne(
                'select * from sites where  id='.$id 
            );
        }
        if (isset($dbedit->id)) {
         
        $return=inputElement('url','Ссылка на сайт',$dbedit->url,'url');
        $return.=inputElement('description','Описания сайта',$dbedit->url,'textarea');
        }
        
    }
    if (isset($dbResult['msg'])) {
        $allert="<div class='alert alert-danger'>".$dbResult['msg']."</div>";
    }
    
    
    
echo '    <div class="row">
<div class="col-md-4">
    <form action="" method="post">
        <h2>Сайты</h2>
        '.$allert.'
        '.$return.'
        
        <input type="submit" value="Сохранить" class="btn btn-danger">
        <a href="/sites" class="btn btn-primary">Назад</a>
    </form>
</div>
</div>';

}
if ($action=='list') {
    
    $items=$database->findMore('select * from sites');
    if ($is_client) {
        $items=$database->findMore('select * from sites where user_id='.$user->id);
    }
    
    $table='<a href="/sites?action=add" class="btn btn-danger">Новый</a>';
    $table.='<table class="table table-dark">';
    $table.='<tr>';
    $table.='<th>';
    $table.='№';
    $table.='</th>';
    $table.='<th>';
    $table.='Ссылка';
    $table.='</th>';

    $table.='<th>';
    $table.='Код для интеграции';
    $table.='</th>';
    $table.='<th>';
    $table.='Статус';
    $table.='</th>';
    $table.='</tr>';
    foreach ($items as $item) {
        $table.='<tr>';
        $table.='<td>';
        $table.='<a href="/sites?action=edit&id='.$item['id'].'">'.$item['id'].'</a>';
        $table.='</td>';
        $table.='<td>';
        $table.=$item['url'];
        $table.='</td>';
        $table.='<td>';
        $table.="<textarea class='form-control'><script  site_id='{$item['id']}'  detect-host='http://{$_SERVER['HTTP_HOST']}/scan' src='http://{$_SERVER['HTTP_HOST']}/public/js/connect_prod.js' code='".md5(sha1($item['id'].$item['user_id']))."'></script></textarea>";
        $table.='</td>';
        

        $table.='<td>';
        $table.=$item['status']=='active'?'Активный':'Не активный';
        $table.='</td>';
        $table.='</tr>';
    }
echo '<div class="row pt-5">'.$table.'</div>';
}

