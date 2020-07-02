<?php
$return='';
$allert='';
global $database;
$dbResult=[];
$action=$_REQUEST['action']?? 'list';


if($action=='add'|$action=='edit'){
    
    $id =(int)isset($_REQUEST['id'])? $_REQUEST['id']:0;

    if (isset($_POST['name'])) {
        switch ($action) {
            case 'add':
                    $dbResult=$database->add("insert into tags(name) values('{$_POST['name']}')");      
                break;
            case 'edit':
                    $dbResult=$database->edit("replace into tags(id,name) values($id,'{$_POST['name']}')");      
                break;
        }
    }
    $return=inputElement('name','Названия');
    if($id!=0){

        $dbedit=$database->findOne(
            'select * from tags where id='.$id
        );
        $return=inputElement('name','Названия',$dbedit->name);
    }
    if (isset($dbResult['msg'])) {
        $allert="<div class='alert alert-danger'>".$dbResult['msg']."</div>";
    }
    
    
    
echo '    <div class="row">
<div class="col-md-4">
    <form action="" method="post">
        <h2>Теги</h2>
        '.$allert.'
        '.$return.'
        
        <input type="submit" value="Сохранить" class="btn btn-danger">
        <a href="/tags" class="btn btn-primary">Назад</a>
    </form>
</div>

</div>';

}
if ($action=='list') {
    

    $items=$database->findMore('select * from tags');
    $table='<a href="/tags?action=add" class="btn btn-danger">Новый</a>';
    $table.='<table class="table table-dark">';
    $table.='<tr>';
    $table.='<th>';
    $table.='№';
    $table.='</th>';
    $table.='<th>';
    $table.='Названия';
    $table.='</th>';
    $table.='</tr>';
    foreach ($items as $item) {
        $table.='<tr>';
        $table.='<td>';
        $table.='<a href="/tags?action=edit&id='.$item['id'].'">'.$item['id'].'</a>';
        $table.='</td>';
        $table.='<td>';
        $table.=$item['name'];
        $table.='</td>';
        $table.='</tr>';
    }
echo '<div class="row pt-5">'.$table.'</div>';
}

