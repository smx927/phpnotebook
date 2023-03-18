<?php
$code = file_get_contents('runcode.php');
/*
code = editor.getValue();
	stdin = '';
	if ($('#stdin').length > 0) { 
		stdin = $("#stdin").val();
	}
	token = '4381fe197827ec87cbac9552f14ec62a';
	runcode = 6;
	$.post("https://tool.runoob.com/compile2.php",{code:code,token:token,stdin:stdin,language:runcode, fileext:"go"},function(data){
	    $("#compiler-textarea-result").val(data.output + data.errors);
	});

*/
  //从菜鸟获取编译结果
  // echo exe_cainiao($code);  
   echo exe_bccn($code);  
  // echo exe_dooccn($code);

// 从菜鸟速度比较慢 5秒
function exe_cainiao($code){ 
  $token = get_token(); // '4381fe197827ec87cbac9552f14ec62a';
  $postdata = array(
		'code'=>$code,
		'token'=>$token,
		'stdin'=>'stdin',
		'language'=>'runcode',
		'fileext'=>"go"
   );
$res_data = request_post('https://tool.runoob.com/compile2.php',$postdata);
 //var_dump($res_data);
$data_arr = json_decode($res_data,true);
return $data_arr['output']?$data_arr['output']:$data_arr['errors'];

}


//bccn编译  3秒
function exe_bccn($code){

 $postdata = array(
		'code'=>$code,
		'language'=> 'go',
        'compiler'=> 'go',
        'version'=> '1.12.5', 
		'inputs'=>'',
   );
 
 //https://www.bccn.net/run/ajax_save_code
 $csrftoken=get_csrftoken();//'cPh06hPRUxD7im85zcGdqVouZpP8pjTHOVZZfIogoG5nholzpWB7XnJval4ABUxD';
 $header = array(
  'cookie: csrftoken='.$csrftoken.';',
 'x-csrftoken:'.$csrftoken,
 'origin: https://www.bccn.net',
 'referer:https://www.bccn.net/run/',
 );

$res_data = request_post('https://www.bccn.net/run/ajax_save_code',$postdata,$header);
 //var_dump($res_data);
$data_arr = json_decode($res_data,true);
return  $data_arr['message']?$data_arr['message']:$res_data;  

}


//dooccn 3秒

function exe_dooccn($code){

$postdata = array(
    'code'=>base64_encode($code), 
    'stdin'=>'123\nhaha2',
    'language'=>'6',
   );



   $header = array(
    'Host: runcode-api2-ng.dooccn.com',
    'Origin: https://www.dooccn.com',
    'Referer: https://www.dooccn.com/go/',
   ); 

$res_data = request_post('https://runcode-api2-ng.dooccn.com/compile2',$postdata,$header);
 //var_dump($res_data);
$data_arr = json_decode($res_data,true);
return $data_arr['output']?$data_arr['output']:$data_arr['errors'];

  // https://runcode-api2-ng.dooccn.com/compile2
}

/**
     * 模拟post进行url请求
     * @param string $url
     * @param string $param
     */

    function request_post($url = '', $param = '',$header=array()) {

        if (empty($url) || empty($param)) {

            return false;

        }
 
        $postUrl = $url;

        $curlPost = $param;

        $ch = curl_init();//初始化curl

        curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页

        curl_setopt($ch, CURLOPT_HEADER, 0);//是否返回header 

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上

        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式

        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
		 //设置header头
		 if(!empty($header)){ curl_setopt($ch, CURLOPT_HTTPHEADER, $header); }

        $data = curl_exec($ch);//运行curl

        curl_close($ch);
 
        return $data;

    }

function get_csrftoken(){

  if(isset($_COOKIE['go_csrftoken'])&&$_COOKIE['go_csrftoken']){

    return $_COOKIE['go_csrftoken'];
  }
 else{
  $htmlcontent = request_post('https://www.bccn.net/run/',array('time'=>1));
  $reg_token ='/<meta\s+content=[\'""](\w+)[\'""]\s+name="csrftoken">/';
  preg_match($reg_token,$htmlcontent,$match);
  setcookie('go_csrftoken',$match[1], time()+3600*24);
  echo '重新获取了token'; 
  return $match[1];

 }


}
function get_token(){


  if(isset($_COOKIE['go_token'])&&$_COOKIE['go_token']){

    return $_COOKIE['go_token'];
  }
 else{
  $htmlcontent = request_post('https://c.runoob.com/compile/21/',array('time'=>1));
  $reg_token ='/token\s*=\s*[\'""](\w+)[\'""];/';
  preg_match($reg_token,$htmlcontent,$match);
  setcookie('go_token',$match[1], time()+3600*24);
  echo '重新获取了token'; 
  return $match[1];

 }
 
}

?>