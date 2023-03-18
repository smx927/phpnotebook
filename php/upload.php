<?php

$res = array(
'success'=>0,
'message'=>'上传失败',
'url'=>'',
);
 
 
 if(isset($_FILES)){
	 
	 $file = $_FILES['editormd-image-file'];
	 $pathinfo = pathinfo($file['name']);
	 
	 $dir ='../upload/'.date('Y-m-d');
	 $file_name = $dir.'/'.time().'.'.$pathinfo['extension'];
	 
	 if(!is_dir($dir)){
		 
		 mkdir($dir,'0777',true); 
	 }
 
	 move_uploaded_file($file['tmp_name'],$file_name);
	 $res['success'] =1;
	 $res['message'] ='上传成功';
	 $res['url'] = '.'.trim($file_name,'.'); 
	 
	echo json_encode($res); 
 }

?>