<?php
header("Content-type: application/json; ");//ответ тело json
header("Access-Control-Allow-Origin: *");//доступ из других хостингов
global $database;
$ARR=$_GET;
if (isset($_POST)) {
    $ARR=$_POST;
}
unset($ARR['site_id']);
unset($ARR['code']);
if (!isset($_REQUEST['site_id'],$_REQUEST['code'])) 
{
       echo json_encode(['msg'=>'Доступ запрещен!!!','status'=>'Нет доступа']);

}else {
    $return=[];

    $detect=new Filter($_REQUEST['site_id'],$_REQUEST['code'],($ARR));
    if ($detect->checkSite()) {//проверка клиента 
        foreach ($ARR as $key => $value) {
            $detectEd=$detect->it_Safe($value,$ARR);//проверка значения на всех сигнатурах
            if (count($detectEd)>0) 
            {
                
                $return[]=['title'=>'Поле ('.$key.') заражён!','key'=>$key,'detected'=>$detectEd] ;     
            }
        }    

        if (!empty($return)) {
            echo json_encode(['msg'=>'Найдены уязвимости!!!','status'=>'Уязвимо','result'=>$return]);
        }else{
            echo json_encode(['msg'=>'уязвимости не найдено!!!','status'=>'Не Уязвимо','result'=>$return]);
        }
    }else {
        echo json_encode(['msg'=>'Доступ запрещен!!!','status'=>'Нет доступа']);
    }
    
}
die();
?>