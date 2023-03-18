<?php 
 //执行sqlite 删除
 
include dirname(__FILE__).'/class/include.php';

$id    = intval($_POST['id']); 
$title    = $_POST['title'];

$sql = "select * from code_content where title='".$title."' limit 1";
$data_exists = $db->selectArray($sql,'assoc');

//防止重名  
if(!$data_exists){
      // 提示错误
    exit(json_encode(array('status'=>0,'msg'=>'数据不存在！')));

 }


$query = "DELETE FROM ".$db->quote_id('code_content')." WHERE ".$db->quote_id('id')."=".$db->quote($id);
  
 // exit(json_encode(array('status'=>0,'msg'=>'禁止删除')));  
$db->query($query); 
exit(json_encode(array('status'=>1,'msg'=>'删除成功'))); 