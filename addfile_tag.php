<?php 
 
$data_json = trim(file_get_contents('mytag.js'),'jsontag_data=');

  // 解析json
$data = json_decode($data_json,true);

//print_r($data);

if($data){
	
foreach($data as $key=>$value){ 

     // 查找是否存在同名的有同名的则提示错误
    if(base64_encode($_POST['name'])==$value['name']){  
     //提示错误
     exit(json_encode(array('status'=>0,'msg'=>'已存在同名文件')));

    }

}

}

$data[] = array('name'=>base64_encode($_POST['name']),'path'=>base64_encode($_POST['path'])); 

file_put_contents('mytag.js','jsontag_data='.json_encode($data));  


 exit(json_encode(array('status'=>1,'msg'=>'保存成功')));