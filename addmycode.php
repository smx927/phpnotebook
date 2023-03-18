<?php
//执行sqlite 添加
//error_reporting(0);
include dirname(__FILE__).'/class/include.php';  

$lang = isset($_POST['lang'])&&$_POST['lang']?$_POST['lang']:'php'; 

$query = "INSERT INTO  ".$db->quote_id('code_content');
$insert_arr = array(
	'title'=>$db->quote($_POST['title']),
	'lang'=>$db->quote($lang), 
	'code'=>$db->quote($_POST['code_body']),
	'updatetime'=>time(),
	'addtime'=>$db->quote(time()), 
);   

$query_cols = implode(',',array_keys($insert_arr)); 
$query_vals = implode(',',$insert_arr);

$sql_exists ="select * from ".$db->quote_id('code_content')." where title='".$_POST['title']."'";
if($db->selectArray($sql_exists)){
  exit(json_encode(array('status'=>0,'msg'=>'已存在同名文件')));
}

$query.=" (". $query_cols . ") VALUES (". $query_vals. ")";
$new_id = $db->insert($query);
exit(json_encode(array('status'=>1,'msg'=>'添加成功'))); 