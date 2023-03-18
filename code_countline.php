
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>笔记发布修改统计图 </title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>.highcharts-axis-resizer {
	stroke: #eee;
}
.highcharts-axis-resizer:hover {
	stroke: #ccc;
}
</style>	
<script src="js/highchart/jquery-1.8.3.min.js"></script>
<script src="js/highchart/highstock.js"></script>
<script src="js/highchart/exporting.js"></script>
<script src="js/highchart/highcharts-zh_CN.js"></script>
</head>

<?php 
include dirname(__FILE__).'/class/include.php';  
 
$log_list = $db->selectArray("SELECT addtime FROM ".$db->quote_id('code_content_log')." order by addtime asc ",'assoc');


//var_dump($log_list);   
 
if(empty($log_list)){
	
	$data_log = array();
}
else{
	
  foreach($log_list as $key=>$val){
  	
     $data_log[date('Y-m-d',$val['addtime'])][] = $val['addtime'];   	
  }	
 	
} 
 
// $sql = 'select log.* from code_content_log log where '.$where.' order by log.addtime desc '.$page->limit;
 
	
$sql = 'select addtime  from code_content order by  addtime asc ';
 

 // echo $sql;
$list_data = $db->selectArray($sql,'assoc');

if(empty($list_data)){
	
	$data = array();
}
else{
	
  foreach($list_data as $key=>$val){
  	
     $data[date('Y-m-d',$val['addtime'])][] = $val['addtime'];   	
  }	
 	
}
 
 //计算开始时间到现在 

 $total_day = ceil((time() - strtotime('2021-10-24'))/(3600*24) );
 

$add_data = array();
$log_data = array();

for($i=0;$i<$total_day;$i++ ){
   
  $tempday = date('Y-m-d',strtotime('+'.$i.' day',strtotime('2021-10-24')));	
  $add_data[] = array(strtotime($tempday)*1000+3600*24*1000,isset($data[$tempday])?count($data[$tempday]):0);
  $log_data[] = array(strtotime($tempday)*1000+3600*24*1000,isset($data_log[$tempday])?count($data_log[$tempday]):0);
  
  
}
 
 // print_r($add_data); 
 
?>
<body >
    <div id="container" style="min-width:400px;height:600px"></div>
    
<script>

	data = <?php echo json_encode($add_data); ?>;
	log_data = <?php echo json_encode($log_data); ?>;
	// 去掉多余的数据
 	Highcharts.each(data, function(d) {
		d.length = 2;
	});
	
	Highcharts.each(log_data, function(d) {
		d.length = 2;
	});
  
	
	Highcharts.stockChart('container', {
		rangeSelector: {
			selected: 2
		},
 credits: {
      enabled:false
 },
		title: {
			text: '笔记发布修改统计'
		},
	legend: {
		enabled: true
	},
		plotOptions: {
			dataLabels: {
				// 开启数据标签 
				enabled: true          
			},
			series: {
				showInLegend: true
			}
		},
		tooltip: {
			//headerFormat: '<small>{point.x|date}</small><br/>', 
		    //pointFormat: '{series.name}：{point.y}次 ', 
		    
		    formatter:function(){ 
		    	
		    	var temp =  Highcharts.dateFormat('%Y-%m-%d',this.point.x);
		    	
		    	return '<small>'+ temp +'</small><br/>'+this.series.name+this.point.y+'次';
		    	
		    	
		    },
			shared: false
		},
		series: [{
		   type: 'line',
			id: '000001',
			name: '发布数',
			data: data
	    	},
		{
		    type: 'line',
			id: '000002',
			name: '修改数',
			data: log_data
		}
	 ]
	});
 

</script>
</body>
</html>
 