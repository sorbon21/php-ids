<?php
$return='';
global $database;
$user=isset($_SESSION['user'])?($_SESSION['user']):NULL;
if ($user!=NULL) 
{
  
$sqlforclient='1=1';
if($user->role=='Клиент'){

  $sqlforclient=' site_id= (select id from sites where user_id='.$user->id.') ';
  
} 

$forChart5=[];
$forChart1=$database->findMore('SELECT tags.name,COUNT(*)
as count FROM rules_tag
LEFT JOIN tags ON rules_tag.tag_id=tags.id

GROUP BY name
order by count
;');

$forChart2Helper=$database->findMore(
'SELECT distinct logs.rules_ids as id FROM logs
  where '.$sqlforclient.'
');
if(count($forChart2Helper)>0){

 $forchart2Ids=[];   
foreach ($forChart2Helper as $value) {
    $forchart2Ids[]=$value['id'];
}


$forChart2=$database->findMore('SELECT tags.name,COUNT(*)
as count FROM rules_tag
LEFT JOIN tags ON rules_tag.tag_id=tags.id
where rule_id in('.join(',',$forchart2Ids).')
GROUP BY name
order by count desc
;');


$chart2_series=[];
$chart2_labels=[];
foreach ($forChart2 as $item) {
    $chart2_series[]=intval($item['count']);
    $chart2_labels[]=$item['name'];
}


}


$chart1_series=[];
$chart1_labels=[];
foreach ($forChart1 as $item) {
    $chart1_series[]=intval($item['count']);
    $chart1_labels[]=$item['name'];
}


$forChart3=$database->findMore('SELECT date(logs.dt) as name,COUNT(*)as count FROM logs
where '.$sqlforclient.'
GROUP BY date(logs.dt)
ORDER by date(logs.dt);');


$forChart4=$database->findMore('SELECT sites.url as name ,COUNT(*) as count FROM logs LEFT JOIN sites ON sites.id=logs.site_id
where '.$sqlforclient.'
GROUP by sites.url
order by count desc');


$forChart5Helper=$database->findMore('SELECT logs.rules_ids as ids FROM logs

where '.$sqlforclient.'
');


if (count($forChart5Helper)>0) {
  # code...

$rule_ids=[];
foreach ($forChart5Helper as $item) 
{
  $rule_ids[]=$item['ids'];
}
$rule_ids=explode(',',join(',',$rule_ids));


$rule_count=array_count_values($rule_ids);
$rule_ids=array_unique($rule_ids);
foreach ($rule_ids as $item) 
{
  $forChart5[]=['name'=>$database->findOne('SELECT rules.title as name FROM rules
WHERE id='.$item.'
')->name,'count'=>$rule_count[$item]];

}

}

$chart3_series=[];
$chart3_labels=[];
foreach ($forChart3 as $item) {
    $chart3_series[]=intval($item['count']);
    $chart3_labels[]=$item['name'];
}




?>
    <style>
    .chart {
    max-width:100%;
    margin: 35px auto;
    padding: 0;
    color:black;
  }
    
  </style>
<div class="row pt-5 mt-5">
    <div class="col-6">
        <div  class="chart" id="chart1"></div>
    </div>
    <div class="col-6">
        <div  class="chart" id="chart2"></div>
    </div>
</div>
<div class="row pt-2 mt-2">
    <div class="col-6">
        <div  class="chart" id="chart3"></div>
    </div>
    <div class="col-6">
    <h5 class='text-center'>Статистика угроз по сайтам </h5>
        <table class='table table-hover'>
        <tr>
        <th>
        Названия сайта
        </th>
        <th>
        Количество атак
        </th>
        </tr>
      <?php

  foreach ($forChart4 as $item) {
    echo '<tr>';
    echo '<td>'.$item['name'].'</td>';
    echo '<td>'.$item['count'].'</td>';
    echo '</tr>';
  
  } ?></table>

    </div>

  <div class="row pt-5 mt-5">
    <div class="col-12">
    <table class='table table-hover'>
        <tr>
        <th>
        Названия угрозы
        </th>
        <th>
        Количество появления
        </th>
        </tr>
      <?php

  foreach ($forChart5 as $item) {
    echo '<tr>';
    echo '<td>'.$item['name'].'</td>';
    echo '<td>'.$item['count'].'</td>';
    echo '</tr>';
  
  } ?></table>

    </div>

</div>


</div>

<script>
  
  
  var options1 = {
          series: [{
            name: "Количество сигнатур",
            data: [<?=join(',',$chart1_series)?>]
        }],
          chart: {
          height: 350,
          type: 'line',
          zoom: {
            enabled: false
          }
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'straight'
        },
        title: {
          text: 'Сигнатуры по тегам определения угроз в наличии',
          align: 'left'
        },
        grid: {
          row: {
            colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
            opacity: 0.5
          },
        },
        xaxis: {
          categories: ["<?=join('","',$chart1_labels)?>"],
        }
        };


        var chart1 = new ApexCharts(document.querySelector("#chart1"), options1);
        chart1.render();



        var options2 = {
          series: [{
            name: "Количество сигнатур",
            data: [<?=join(',',$chart2_series)?>]
        }],
          chart: {
          height: 350,
          type: 'line',
          zoom: {
            enabled: false
          }
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'straight'
        },
        title: {
          text: 'Сигнатуры по тегам определения угроз в логах',
          align: 'left'
        },
        grid: {
          row: {
            colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
            opacity: 0.5
          },
        },
        xaxis: {
          categories: ["<?=join('","',$chart2_labels)?>"],
        }
        };


        var chart2 = new ApexCharts(document.querySelector("#chart2"), options2);
        chart2.render();





        var options3 = {
          series: [{
            name: "Количество сигнатур",
            data: [<?=join(',',$chart3_series)?>]
        }],
          chart: {
          height: 350,
          type: 'line',
          zoom: {
            enabled: false
          }
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'straight'
        },
        title: {
          text: 'Сигнатуры по тегам определения угроз в логах',
          align: 'left'
        },
        grid: {
          row: {
            colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
            opacity: 0.5
          },
        },
        xaxis: {
          categories: ["<?=join('","',$chart3_labels)?>"],
        }
        };


        var chart3 = new ApexCharts(document.querySelector("#chart3"), options3);
        chart3.render();



  
</script>
<?php
}else{
  echo 'Нет доступа';
}

?>