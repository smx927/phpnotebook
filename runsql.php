<?php
 
$sql = file_get_contents('runcode.php');

//isset($_GET['code_body'])?trim($_GET['code_body']):'select type, name, tbl_name from sqlite_master order by type';

//载入数据库配置文件
include dirname(__FILE__).'/class/include_sql.php';  
//'select type, name, tbl_name from sqlite_master order by type';
$list_data = $db->selectArray($sql,'assoc');

//如果sql执行失败则输出错误信息中止
if($list_data === NULL || $list_data === false){
	 exit("<br /><b>错误: ".$db->getError()."</b>");
 	}
//print_r($list_data);
$table_header = array(); 
if(!empty($list_data)){
 $table_header = array_keys(reset($list_data));   
}

// 通过表格展示查询到的信息  方便查看下面的不用动。
?>
<!DOCTYPE html>
<html>
 <body class="markdown-body">
 	
 	<table>
 	<tr>
 		<?php 
 		foreach($table_header as $key ){
 		?>
 		<th><?php echo $key;?></th>
 		<?php 
 		}?>
 	</tr>
 	<?php 
 		foreach($list_data as $key=>$value){
 		?>
 	<tr>
 		<?php
 	   foreach($table_header as $key ){	
 	   	?>
 		<td><?php echo $value[$key]; ?></td> 
 	  <?php
 		} 
 		?>
  
 	</tr>
 	<?php
      }
 	 ?>
 	
 	
 </table>
 <style type="text/css" >
 
 .markdown-body table {
  border-collapse: collapse;
  border-spacing: 0;
}

.markdown-body td,
.markdown-body th {
  padding: 0;
}

.markdown-body * {
  -moz-box-sizing: border-box;
  box-sizing: border-box;
}

 	.markdown-body table {
  display: block;
  width: 100%;
  overflow: auto;
  word-break: normal;
  word-break: keep-all;
}

.markdown-body table th {
  font-weight: bold;
   background-color: #efefef;
}

.markdown-body table th,
.markdown-body table td {
  padding: 6px 13px;
  border: 1px solid #ccc;
}

.markdown-body table tr {
  background-color: #fff;
  border-top: 1px solid #ccc;
}

.markdown-body table tr:nth-child(2n) {
  background-color: #f9f9f9;
}
 </style>	
 	
 	
 </body>
</html>