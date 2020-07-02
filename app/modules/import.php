<?php
$return='';
$allert='';
global $database;

$filter_file='filter.xml';

if(file_exists($filter_file)){
    
    $xml=simplexml_load_file($filter_file);
    foreach ($xml as $key => $value) 
    {
        $rule=base64_encode($value->rule);
        $description=($value->description);
        $tags=$value->tags->tag;

        foreach ($tags as $key1 => $value1) {//добавление в систему
            $database->add("insert into tags(name) values('{$value1}')");  
        }
        $findOne=$database->findOne("select * from rules where title='$description' and content='$rule'");
    
        
        if (isset($findOne->id)) {
            foreach ($tags as $key1 => $value1) {
                $findOneTag=$database->findOne("select * from tags where name='$value1'");
            if (!isset($database->findOne("SELECT * FROM rules_tag where tag_id={$findOneTag->id} and rule_id={$findOne->id}")->id)) 
            {
                $database->add("insert into rules_tag(rule_id,tag_id) values({$findOne->id},{$findOneTag->id})");          
               
            }
            
            } 
                
            
        }else{
            $dbResult=$database->add("insert into rules(title,content,user_id) values('$description','$rule',1)");  
            if ($dbResult['status']===true) {
                foreach ($tags as $key1 => $value1) {
                    $findOneTag=$database->findOne("select * from tags where name='$value1'");
                    if (!isset($database->findOne("SELECT * FROM rules_tag where tag_id={$findOneTag->id} and rule_id={$dbResult['id']}")->id)) 
                    {
                        $database->add("insert into rules_tag(rule_id,tag_id) values({$dbResult['id']},{$findOneTag->id})");  
                       
                    }
                    
                }
            }

        }

        
    }


}


?>
<p class='alert alert-success'>Все правила импортированы</p>