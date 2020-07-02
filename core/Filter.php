<?php
class Filter extends DB
{
  
    private $site_id;//идентификатор сайта
    private $code;//код для проверки сайта
    private $vars;
    
    public function __construct($site_id,$code,$vars)
    {
        parent::__construct();
        $this->site_id=$site_id;
        $this->code=$code;
        $this->vars=$vars;
    }
    public function checkSite()//функция проверки кода клиента 
    { 
        $site=$this->findOne("select * from sites where id=".$this->site_id);
        if (isset($site->id)) {
            return md5(sha1($site->id.$site->user_id))===$this->code;///если код верныйн
        }
        return false;//если не верный  код 
    }
    public function it_Safe($input,$ARR)//функция проверки данных от клиентов на наличия угроз
    {
        $return=[];
        $return_ids=[];
        foreach ($this->findMore('select * from rules') as $key => $value) //правила
        {
            $rule=base64_decode($value['content']);//декодирование сигнатур
            if ((bool)preg_match("/{$rule}/ms",$input)) {//сопоставление
                $return[]=$value['title'];  ///добавление в массив  названия и id
                $return_ids[]=$value['id'];  
            }
        }
        if(count($return_ids)>0) //если именются угрозы лоигруем
        {
            $this->AddToLog(join(",",$return_ids),$ARR,$input);
        }
        return $return;
    }
    private function AddToLog($ids,$vars,$detectval){//функция для логирования 
          $detectval=base64_encode($detectval);
          $vars=base64_encode(json_encode($vars));
          $vals="{$this->site_id},'{$ids}','{$vars}','{$detectval}'";
        $this->add("INSERT INTO `logs`(`site_id`, `rules_ids`, `req_vals`, `req_vars`) VALUES ($vals)");
    }
}
