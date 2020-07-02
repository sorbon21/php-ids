<?php
class DB
{
    public $ct;
    public $db;

    public function __construct()
    {
        $this->db=[/*конфигурация бд*/ 
            'host'=>'localhost',
            'user'=>'',
            'password'=>'',
            'db'=>'ids'
           ];
    }

    public function connect()/*подкл к бд*/ 
    {
        $this->ct = @new mysqli($this->db['host'],$this->db['user'],$this->db['password'],$this->db['db']);
        $this->ct->set_charset('utf8');

        if ($this->ct->connect_error)
        {
            return $this->ct->connect_error;

        }else
        {
            return $this->ct;/*возвр объект для подкл к бд*/ 
        }
    }
    public function findOne($sql){/*возвращение одной записи по запросу*/ 
        return (object) $this->connect()->query($sql)->fetch_assoc();
    }
    public function findMore($sql){/*возвращение масив записей по запросу*/ 

        $result=[];
        $sqlresult=$this->connect()->query($sql);
        while($row=$sqlresult->fetch_assoc()){
            $result[]=$row;
        }

        return $result;
    }

    public function add($sql)
    { 
        $result = $this->connect()->query($sql);
        if($result&&$this->ct->affected_rows)
        {
            /*возвращает идентификатор*/ 
            return ['status'=>true,'msg'=>'Добавлен в систему под номером '.$this->ct->insert_id,'id'=>$this->ct->insert_id];
        }else{
            /*возвращает ошибку*/ 
            return ['status'=>false,'msg'=>$this->ct->error];
        }   
    }

    public function edit($sql)
    { 
        $result = $this->connect()->query($sql);
        if($result&&$this->ct->affected_rows)
        {
            return ['status'=>true,'msg'=>'Обновления зафиксированы'];
        }else{
            return ['status'=>false,'msg'=>$this->ct->error?? 'Возможно не имеются не зафиксированные обновления'];
        }   
    }
};


