<?php
$host='http://s271548.smrtp.ru/'; // ссылка на сайт проверки
$site_id=4;
$user_id=1;
$code=md5(sha1($site_id.$user_id));
if (isset($_GET)){
    $ARR=$_GET;
}elseif (isset($_POST)) {
    $ARR=$_POST;
}
$ARR['code']=$code;
$ARR['site_id']=$site_id;
if (!empty($ARR)) 
{
    $data = http_build_query($ARR);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "$host/scan?$data");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $data = curl_exec($ch);
    curl_close($ch);
}