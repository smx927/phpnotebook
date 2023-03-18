<?php 
 //获取我的代码
include dirname(__FILE__).'/class/include.php';  

$title = $_GET['title'];
$id = intval($_GET['id']); 
$sql = "select * from code_content where id='".$id."' limit 1";
$data = $db->selectArray($sql,'assoc');

//echo $data[0]['code']; 
//清除下null  防止json解析出错
foreach ($data[0] as $key => $value) {
	
	if($value===null){

       $data[0][$key] ='';

	}
} 
echo json_encode($data[0]);
exit;
 
 