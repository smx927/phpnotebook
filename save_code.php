<?php
 
 $code = return_stripslashes($_POST['code_body']);
 $lang = isset($_POST['lang'])?return_stripslashes($_POST['lang']):'php';
 //去除转义
 //去除转义 斜杠\
function return_stripslashes($str){
    if(function_exists('get_magic_quotes_gpc') && @get_magic_quotes_gpc())//判断get_magic_quotes_gpc是否可用，如果可用则外部数据为被转义
    {
      return stripslashes($str);
     
    }else{
        return $str;
    }
}
 $data = array('code_body'=>$code,'lang'=>$lang);
 file_put_contents('runcode.php',$code);
 file_put_contents('lang.php',$lang);
 //file_put_contents('lang.txt',$lang);

 //header("Location:./runcode.php");


if($lang!=='php'){
$codearr= array('php_version'=>PHP_VERSION,'code_path'=>'http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['REQUEST_URI'])."/run".$lang.".php",'lang'=>$lang);
	
}
else{
$codearr= array('php_version'=>PHP_VERSION,'code_path'=>'http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['REQUEST_URI'])."/runcode.php",'lang'=>$lang);	
}


exit(json_encode($codearr));