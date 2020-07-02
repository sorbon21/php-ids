<?php
require_once('core/root.php');
global $database;
global $filters;
session_start();
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

if(isset($_SERVER['PATH_INFO'])&&$_SERVER['PATH_INFO']=='/scan')/*отвечают за обработку запросов с сайтов клиентов*/ 
{

    loadModule('scan');
die();
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Система защиты информации</title>
    <link rel="stylesheet" href="./public/css/bootstrap.min.css">
    <link rel="stylesheet" href="./public/chart/apexcharts.css">
    <script src="./public/js/jquery-3.4.1.min.js"></script>
    <script src="./public/js/bootstrap.min.js"></script>
    <script src="./public/chart/apexcharts.min.js"></script>
</head>
<body>
<div class="container">
<div class="row pb-5 mb-5">
<?php
require_once 'app/navbar.php';
?>
</div>
<?php
/*/profile*/ 
        if (isset($_SERVER['PATH_INFO'],$_SESSION['user'])) {/*если польз авторизир он может восп всеми функциями системы*/ 
            loadModule($_SERVER['PATH_INFO']);
        }elseif(isset($_SERVER['PATH_INFO'])&&in_array($_SERVER['PATH_INFO'],['reg','auth'])){/*если польз не авторизиро он может зарег или авториз*/ 
            loadMOdule($_SERVER['PATH_INFO']);
        }else{
            loadMOdule('auth');
        }
    ?>
</div>
</body>
</html>