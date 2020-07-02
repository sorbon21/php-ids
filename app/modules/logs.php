<?php
$return='';
global $database;
$dbResult=[];
$user=$_SESSION['user']??NULL;
$action=$_REQUEST['action']?? 'list';
if ($action=='list' &&isset($user->id,$user->role,$user->login)) {
    $items=$database->findMore('SELECT sites.url,
    concat(users.fio," (",users.login,")")
    as client,
    logs.id,
    logs.rules_ids,
    logs.req_vals,
    logs.req_vars,
    logs.dt
    FROM logs LEFT JOIN sites
    ON logs.site_id=sites.id
    LEFT JOIN users
    ON
    sites.user_id=users.id
    
    order by logs.id desc
    ');


    if ($user->role=='Клиент') {

        $items=$database->findMore('SELECT sites.url,
        concat(users.fio," (",users.login,")")
        as client,
        logs.id,
        logs.rules_ids,
        logs.req_vals,
        logs.req_vars,
        logs.dt
        FROM logs LEFT JOIN sites
        ON logs.site_id=sites.id
        LEFT JOIN users
        ON
        sites.user_id=users.id
            where users.id='.$user->id.'
            order by logs.id desc
        ');
    
    }

    $table='<table class="table table-hover table-bordered">';
    $table.='<tr>';
    $table.='<th>';
    $table.='№';
    $table.='</th>';
    $table.='<th>';
    $table.='Сайт';
    $table.='</th>';
    $table.='<th>';
    $table.='Владелец';
    $table.='</th>';
    $table.='<th>';
    $table.='Списиок угроз';
    $table.='</th>';
    $table.='<th>';
    $table.='Теги атак';
    $table.='</th>';
    $table.='<th>';
    $table.='Поля формы';
    $table.='</th>';
    $table.='<th>';
    $table.='Угроза';
    $table.='</th>';
    $table.='<th>';
    $table.='Дата';
    $table.='</th>';
    
    $table.='</tr>';
    foreach ($items as $item) {
        $table.='<tr>';
        $table.='<td>';
        $table.=$item['id'];
        $table.='</td>';
        $table.='<td>';
        $table.=$item['url'];
        $table.='</td>';
        $table.='<td>';
        $table.=$item['client'];
        $table.='</td>';

       $rulesList= $database->findMore("SELECT title

        FROM rules
         WHERE id IN({$item['rules_ids']})");

        $table.='<td><ul>';

        foreach ($rulesList as $valuelist) {
            $table.="<li>{$valuelist['title']}</li>";
        }
        $table.='</ul></td>';

        $tagList= $database->findMore("SELECT DISTINCT name FROM rules_tag
LEFT JOIN tags ON tag_id=tags.id
WHERE rule_id in ({$item['rules_ids']})");

        $table.='<td><ul>';

        foreach ($tagList as $valuelist) {
            $table.="<li>{$valuelist['name']}</li>";
        }
        $table.='</ul></td>';

        $table.='<td>';
        $table.= "<a target='_blank' href='/viewval?content={$item['req_vals']}'>Посмотреть значения</a>"; //"<textare class='form-control'>".base64_decode()."</textarea>";
        $table.='</td>';

        $table.='<td>';
        
        $table.= "<a target='_blank' href='/viewval?content={$item['req_vars']}'>Посмотреть значения</a>"; //"<textare class='form-control'>".base64_decode()."</textarea>";
        $table.='</td>';
        
        $table.='<td>';
        $table.=$item['dt'];
        $table.='</td>';
        $table.='</tr>';
    }
echo '<div class="row pt-5">'.$table.'</div>';
}

if ($action=='logout') {
    session_destroy();
    redirect('/auth');
}