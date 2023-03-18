<?php
//修改代码 
include dirname(__FILE__).'/class/include.php';  

$title    = isset($_POST['title'])&&$_POST['title']?$_POST['title']:'';
$id = isset($_POST['id'])&&$_POST['id']?intval($_POST['id']):0; 

$sql = "select * from code_content where title='".$title."' limit 1";
$data_exists = $db->selectArray($sql,'assoc');

$sql = "select * from code_content where id='".$id."' limit 1";
$data_info = $db->selectArray($sql,'assoc');
 
if(empty($_POST['code_body'])){
 exit(json_encode(array('status'=>0,'msg'=>'内容不能为空！')));	
}
 if(empty($data_info)){

    exit(json_encode(array('status'=>0,'msg'=>'内容不存在')));
 }
//执行修改
//print_r($_POST); 
$query = "UPDATE ".$db->quote_id('code_content')." SET ";
$update_arr = array(
  'title'=>$_POST['title'],
  'description'=>$_POST['description'],
  'catid'=>$_POST['catid'],
  'lang'=>$_POST['lang'],
  'tag'=>implode(',',array_filter(explode(',',$_POST['tag'])) ), //过滤空值
  'listorder'=>$_POST['listorder'],
  'code'=>$_POST['code_body'],
  'updatetime'=>time()
); 

foreach($update_arr as $key=>$val){

   $query.= $db->quote_id($key)."=".$db->quote($val).","; 
}

$query = trim($query,',')."  WHERE id=".$data_info[0]['id'];  
 
 // 新增修改日志 记录修改前后差异
require_once './class/class.Diff.php';
$query_log = "INSERT INTO  ".$db->quote_id('code_content_log');


 $diff_code = Diff::compare(
 	$data_info[0]['code'],  
    $_POST['code_body']
    ); 

$diff_description = Diff::compare(
 	$data_info[0]['description'],  
    $_POST['description']
    );
 //var_export($diff);
 

$changedata_code = Diff::toString($diff_code,'<br/>');
$changedata_description = Diff::toString($diff_description,'<br/>');
//代码和描述修改的记录
if(!empty($changedata_code)||!empty($changedata_description)){ 
$insert_arr = array(
	'cid'=>$db->quote($data_info[0]['id']), 
	'changedata'=>$db->quote($changedata_code.$changedata_description), 
	'addtime'=>$db->quote(time()), 
);

 $query_cols = implode(',',array_keys($insert_arr)); 
 $query_vals = implode(',',$insert_arr);
 
 $query_log.=" (". $query_cols . ") VALUES (". $query_vals. ")";
	
 $log_id = $db->insert($query_log);
 
}
$db->query($query);  

exit(json_encode(array('status'=>1,'msg'=>'修改成功')));
 