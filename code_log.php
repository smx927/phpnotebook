
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>修改记录</title>
    <style>
 a {
    text-decoration: none;
}
        .timeline { margin:10px 0 0 0; padding: 0; list-style: none; position: relative; }
        .timeline:before { content: ''; position: absolute; top: 0; bottom: 0; width: 5px; background: #ccc; left: 20px; margin-left: -10px; }
	.timeline > li { position: relative; }
	.timeline > li .lzc_time { display: block;   position: absolute;}
	.timeline > li .lzc_time span { display: block; text-align: right; }
	.timeline > li .lzc_time span:first-child { font-size: 8px; color: #bdd0db; }
	.timeline > li .lzc_time span:last-child { font-size: 12px; color: #3594cb; }
	.timeline > li:nth-child(odd) .lzc_time span:last-child { color: #6cbfee; }
	.timeline > li .lzc_label { margin: 0 8px 20px 30px; background: #dedede;  padding: 10px; position: relative; border-radius: 5px; min-height: 100px; /* box-shadow: 0 2px 8px 0 rgba(0, 0, 0, 0.19) */ }
	.timeline > li:nth-child(odd) .label { background: #579dc5; } 
	.timeline > li .lzc_label h2 {font-size: 16px; text-shadow: rgba(7, 84, 152, 0.71) 1px 1px 1px; }
	.timeline > li .lzc_label:after { right: 100%; border: solid transparent; content: " "; height: 0; width: 0; position: absolute; pointer-events: none; border-right-color: #dedede; border-width: 10px; top: 10px; }
	.timeline > li:nth-child(odd) .lzc_label:after { border-right-color: #dedede; }
	.timeline > li .lzc_icon { width: 10px; height: 10px; font-family: 'ecoico'; speak: none; font-style: normal; font-weight: normal; font-variant: normal; text-transform: none; font-size: 1.4em; line-height: 40px; -webkit-font-smoothing: antialiased; position: absolute; color: #fff; background: #333; border-radius: 50%; box-shadow: 0 0 0 3px #888; text-align: center; left: 32px; top: 15px; margin: 0 0 0 -25px; }
	.lzc_blogpic { width: 200px; height: 120px; overflow: hidden; display: block; float: left; margin-right: 20px; }
	.timeline li .lzc_time { -webkit-transition: all 1s; -moz-transition: all 1s; -o-transition: all 1s; }
	.timeline li:hover .lzc_icon { box-shadow: 0 0 0 4px #888; }
	.timeline li:hover .lzc_time { background: #dedede; border-radius: 20px 0 0 20px; }
	.timeline li:hover .lzc_time span { color: #fff; }
	
.addtime{ font-size:18px;}

#pages{ height:60px; margin-top:30px; }
#pages a{ height: 25px; line-height:25px; border: 1px solid #ccc; margin-left:2px; padding: 5px 10px; cursor: pointer; }
#pages a.on{ font-weight: bold; color: #f00; }

#pages input{ height: 35px; line-height:25px; border: 1px solid #ccc;  padding: 0px 20px; }


    </style>
    
<link rel="stylesheet" href="md-editor/css/style.css" />
<link rel="stylesheet" href="md-editor/css/editormd.css" />    
<script src="md-editor/js/jquery.min.js"></script>

<script src="md-editor/js/lib/marked.min.js"></script>
<script src="md-editor/js/lib/prettify.min.js"></script>
<script src="md-editor/js/editormd.js"></script> 
 
</head>

<?php 
include dirname(__FILE__).'/class/include.php';  
$where =" 1 ";
$cid = isset($_GET['cid'])?trim($_GET['cid']):'';
if($cid){

 $where .=" and (log.cid =".$cid.") ";  
}

$totalinfo = $db->select("SELECT Count(*) FROM ".$db->quote_id('code_content_log')." log where ".$where,'num');  
$total_num = $totalinfo[0];

$page = new page($total_num,5);
 
// $sql = 'select log.* from code_content_log log where '.$where.' order by log.addtime desc '.$page->limit;
 
	
$sql = 'select log.*,c.title from code_content_log log inner join code_content c on log.cid=c.id  where '.$where.' order by log.addtime desc '.$page->limit;
 

 // echo $sql;
$list_data = $db->selectArray($sql,'assoc');

if(empty($list_data)){
	
	$list_data = array();
}
 
?>
<body >
    <article>
	<div>
		
		 <?php if($cid>0&&$list_data){  ?><h3 style="color:#666;"> <?php echo $list_data[0]['title'];?> </h3> <?php } ?>
	    <ul id = "sale_list" class="timeline">
	    	
	    <?php 
   foreach($list_data as $key=>$info){
   ?>
    
    <li class="time-line-item" >
    	
		    <div class="lzc_icon"></div>
		    <div class="lzc_label" data-scroll-reveal="enter right over 1s" >
		    	<span class="addtime"  >🕒 <?php echo date('Y-m-d H:i:s',$info['addtime']);?> 
		    	
		    <?php if($cid<=0){  ?><div style="color:#666;"><?php echo $info['title'];?> </div> <?php } ?>
			
			
			 
			</span>
			<div style="margin-top:8px" style="border:1px solid red;"  >
				 <?php 
				 echo str_replace(array('<?php','?>'),array('&lt?php','?&gt'), $info['changedata']);?>
			  
		     </div>
		         
			 
		    </div>
		</li>
 
<?php

   }
 ?>    	
	    
		 
	    </ul>
	</div>
    </article>
  <div id="pages" style="text-align: center;" > 
   <?php echo  $page->fpage(array(0,2,3,4,5,6,7)); ?>
   </div> 
</body>
</html>
 