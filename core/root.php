<?php
require_once('core/DB.php');
require_once('core/Filter.php');
global $database;
global $filters;
$database=new DB();

function loadModule($name)
{
    require_once 'app/modules/'.basename($name).'.php';
}

function redirect($url){
    header("Location: $url");
}


function inputElement($name,$label,$value='',$type='text')
{ //функция для генерации input элемента 
    return '
    <div class="form-group">
        <label for="'.$name.'">'.$label.'</label>'.(
            ($type == 'textarea') ?
                ('<textarea class="form-control" required   id="' . $name . '" name="' . $name . '" placeholder="' . $label . '">' . ($value == '' ? '' : $value) . '</textarea>') :
                ('<input class="form-control" required type="' . $type . '" ' . ($value == '' ? '' : 'value="' . $value . '"') . ' id="' . $name . '" name="' . $name . '" placeholder="' . $label . '" />')
        ).
        
    '</div>';
    
    
}

function selectElement($name,$label,$options,$selected=NULL,$isMultiple=false){ //функция для генерации select элемента 
    $options_str='<option value="">Выбрать</option>';
    if($isMultiple==true){
        $options_str='';
    }    
    
        foreach ($options as $value) {

            if ($selected==$value['value']) {
                $options_str.='<option value="'.$value['value'].'" selected>'.$value['name'].'</option>';
            }else{
                $options_str.='<option value="'.$value['value'].'">'.$value['name'].'</option>';
            }
            
        }

    return '
    

    
    <div class="form-group">
        <label for="'.$name.'">'.$label.'</label>
        <select class="form-control" required id="'.$name.'" name="'.$name.'" '.($isMultiple==true ?'multiple':'').'>
                        '.$options_str.'
        </select>
    </div>';
    
}


