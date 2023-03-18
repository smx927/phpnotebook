<?php 
include dirname(__FILE__).'/class/include.php';  

$where =" 1 ";
$q = isset($_GET['q'])?trim($_GET['q']):'';
if($q){
 //修复不能搜索包含单引号内容的问题
 $where .=" and (C.title like \"%".$q."%\" or C.code like \"%".$q."%\" ) ";  
}

$lang = isset($_GET['lang'])?trim($_GET['lang']):'';
if($lang){
 $where .=" and C.lang = '".$lang."' "; 
}

$catid = isset($_GET['catid'])?trim($_GET['catid']):'';
if($catid){
 $where .=" and C.catid = ".$catid;  
}

$tag = isset($_GET['tag'])?trim($_GET['tag']):'';
if($tag){
 $where .=" and C.tag like '%".$tag."%' ";  
}

$ordertype = isset($_GET['ordertype'])? ' '.trim($_GET['ordertype']):' desc ';
$order = isset($_GET['order'])&&trim($_GET['order'])?''.trim($_GET['order']).$ordertype: ' C.updatetime desc, C.id desc';
 
 //echo $where;

$totalinfo = $db->select("SELECT Count(*) FROM ".$db->quote_id('code_content')." C where ".$where,'num');  
$total_num = $totalinfo[0];

$page = new page($total_num,5);
 
$sql = 'select C.*,(SELECT Count(*) FROM  code_content_log log where log.cid=C.id) edit_times from code_content C where '.$where.' order by   C.title like \'%'.$q.'%\'  desc,'.$order.'  '.$page->limit;

$data = $list_data = $db->selectArray($sql,'assoc');
$pages = str_replace('href=','onclick="load_data(this)" thref=',$page->fpage(array(0,2,3,4,5,6,7))); 


$data = array('data'=>$data,'pages'=>$pages,'sql'=>$sql); 

exit(json_encode($data));