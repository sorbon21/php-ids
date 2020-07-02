<?php
$return='';
$allert='';
global $database;
$dbResult=[];
$action=$_REQUEST['action']?? 'list';//показывается список

$user=$_SESSION['user']??NULL;

if($action=='add'|$action=='edit')
{


    $id =(int)isset($_REQUEST['id'])? $_REQUEST['id']:0;

    if (isset($_POST['title'],$_POST['content'])) 
    {
        $_POST['content']=base64_encode($_POST['content']);
        $values= "'{$_POST['title']}','{$_POST['content']}',{$user->id}";
        switch ($action) {
            case 'add':
                    $dbResult=$database->add("insert into rules(title,content,user_id) values($values)");  
                    if ($dbResult['status']===true) {
                        $rule_id=$dbResult['id'];
                        
                        foreach ($_POST['tags'] as $key => $value) {
                            $database->add("insert into rules_tag(rule_id,tag_id) values({$rule_id},{$value})");  
                        }
                    }    
                break;
            case 'edit':
                    $dbResult=$database->edit(" update rules set status='{$_POST['status']}', title='{$_POST['title']}',content='{$_POST['content']}' where id=$id");      
                break;
        }
    }
    $return=inputElement('title','Названия');
    $return.=inputElement('content','Код сигнутры','1212','textarea');
    $return.=selectElement('tags[]','Теги',$database->findMore('select id as value, name from tags'),NULL,true);
    if($id!=0){

        $dbedit=$database->findOne(
            'select * from rules where id='.$id
        );
        
        $return=inputElement('title','Названия',$dbedit->title);
        $return.=inputElement('content','Код сигнатуры',base64_decode($dbedit->content),'textarea');
        $return.=selectElement('status','Статус',[
            ['name'=>'Активный','value'=>'active'],
            ['name'=>'Не активный','value'=>'ban']


        ],$dbedit->status);
       $taglist= $database->findMore('SELECT tags.name FROM rules_tag LEFT JOIN tags
        on rules_tag.tag_id=tags.id where rule_id='.$id);
        $tagsrc=[];
        foreach ($taglist as $key => $value) {
            $tagsrc[]=$value['name'];
        }
        $return.="<div class='alert alert-danger'>Теги: ".join(" , ",$tagsrc)."</div>";
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
        <a href="/rules" class="btn btn-primary">Назад</a>
    </form>
</div>

</div>';

}
if ($action=='list') {
    

    $items=$database->findMore('select * from rules');
    $table='<div class="row">
        <div class="col">
        <a href="/rules?action=add" class="btn btn-danger">Новый</a>
        </div>
        <div class="col">';
    $table.='<a href="/import" class="btn btn-danger">Импорт правил из файла</a></div></div>';
    $table.='<table class="table table-dark">';
    $table.='<tr>';
    $table.='<th>';
    $table.='№';
    $table.='</th>';
    $table.='<th>';
    $table.='Названия';
    $table.='</th>';

    $table.='<th>';
    $table.='Код сигнатуры';
    $table.='</th>';
    $table.='<th>';
    $table.='Теги';
    $table.='</th>';
    $table.='<th>';
    $table.='Статус';
    $table.='</th>';
    $table.='</tr>';
    foreach ($items as $item) {
        $table.='<tr>';
        $table.='<td>';
        $table.='<a href="/rules?action=edit&id='.$item['id'].'">'.$item['id'].'</a>';
        $table.='</td>';
        
        $table.='<td>';
        $table.=$item['title'];
        $table.='</td>';

        $table.='<td>';
        $table.="<textarea class='form-control'>".base64_decode($item['content'])."</textarea>";
        $table.='</td>';
        
       $taglist= $database->findMore('SELECT tags.name FROM rules_tag LEFT JOIN tags
        on rules_tag.tag_id=tags.id where rule_id='.$item['id']);
        $tagsrc=[];
        foreach ($taglist as $key => $value) {
            $tagsrc[]=$value['name'];
        }
        
        $table.='<td>';
        $table.=join(',',$tagsrc);
        $table.='</td>';
        $table.='<td>';
        $table.=$item['status'] === 'active'? 'Активный': 'Не активный';
        
        $table.='</td>';
        
        $table.='</tr>';
    }
echo '<div class="row pt-5">'.$table.'</div>';
}