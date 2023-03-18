<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta http-equiv="Content-Script-Type" content="text/javascript">
	<meta http-equiv="Cache-Control" content="no-transform">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	<meta name="renderer" content="webkit">
	<title>我的代码-代码管理</title>
	<meta name="description" content="在线运行代码,实现阅读代码像阅读小说一样有乐趣，代码的说明和代码本身一样重要,不断积累发挥时间的力量。">
	<link rel="stylesheet" href="./style/skin/base/app_code_edit.css"/>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script  type="text/javascript" src="./ace/src-min-noconflict/ace.js"  charset="UTF-8"  ></script>
  <script src="./ace/src-min-noconflict/ext-language_tools.js"></script>
  <script src="./ace/emmet.min.js"></script>
<script type="text/javascript" src="./layer/layer.js" ></script> 
<link rel="stylesheet" href="md-editor/css/style.css" />
<link rel="stylesheet" href="md-editor/css/editormd.css" />

</head>
<body class="codebody" >
<?php 
include dirname(__FILE__).'/class/include.php';  

$where =" 1 ";
$q = isset($_GET['q'])?trim($_GET['q']):'';
if($q){

 $where .=" and (C.title like '%".$q."%' or C.code like '%".$q."%') "; 
}

$totalinfo = $db->select("SELECT Count(*) FROM ".$db->quote_id('code_content')." C  where ".$where,'num');  
$total_num = $totalinfo[0];

$page = new page($total_num,5);
 
$sql = 'select C.*,(SELECT Count(*) FROM  code_content_log log where log.cid=C.id) edit_times from code_content C where '.$where.' order by C.title like \'%'.$q.'%\'  desc , C.updatetime desc, C.id desc '.$page->limit;

$list_data = $db->selectArray($sql,'assoc');
// print_r($list_data);

$sql_cat = 'select * from code_category where  1  order by catid asc ';

$cat_data = $db->selectArray($sql_cat,'assoc');

$cat_arr = array();

foreach((array)$cat_data as $v){
	
   $cat_arr[$v['catid']] = $v['catname'];
   
}

 // print_r($cat_arr);
 
$lang_data = array('php','golang','python','sql','java','c','c++','html','txt','linux命令','git命令'); 

$tag_data = array('学习中','待研究','已掌握','易出错','常用技能');
// ↓↑ 
$order_data = array(
'updatetime'=>'更新时间↓','addtime'=>'发布时间↓',
'edit_times'=>'修改次数↓','listorder'=>'推荐★↓' );


$listorder_data = array(0=>'☆ 0星',1=>'★ 1星',2=>'★★ 2星',3=>'★★★ 3星',4=>'★★★★ 4星',5=>'★★★★★ 5星');

?>
<style type="text/css">

html, body{overflow-y: scroll;user-select:auto; }
	body,ul,li {padding:0; margin: 0; font-size: 14px;}
 .head_nav{ width:100%; height:50px; }
 .searchtitle{ width:198px; border: 1px solid #cc; height: 30px;   }

.codebody{ padding-top:60px;}
.head_nav{position:fixed;top:0px; z-index:999; background:#fff;padding-bottom:10px; box-shadow:0 0 5px #888;  }
#codelist{ width:96%; padding:1% 2% 0 2%; display: block; clear: both;
 background: #fefefe; overflow: hidden;
 
  }
#codelist li{ text-indent:0px;  padding:0; cursor: pointer; margin-top: 5px; list-style: none; overflow: hidden; line-height:20px;position: relative; }
 
#codelist li.hover{ background:#ebebeb; }
 
#codelist i.fa-times-circle {
    -webkit-transition: opacity .2s;
    transition: opacity .2s;
    opacity: 0;
    display: block;
    cursor: pointer;
    color: #c00;
    top: 0px; 
    right: 5px;
    position: absolute;
    font-style: normal;
}

#codelist li:hover i {
    opacity: 1;
  }
#codelist li.hover i {
    opacity: 1;
  } 
.right-content-top{    margin-left:220px;  height:50px; font-size:16px;  } 
.right-content{ border: 1px solid #ccc;  margin-left:220px; margin-right:10px; min-height:500px;  font-size:16px; display: none;  }
.codetitle{ margin-left:10%;  height:30px;  width:30%; min-width:100px; margin-top: 10px;  margin-right: 10px; margin-bottom: 10px; text-indent: 10px; }


.savecode,.addcode, .runcode,.close{ height:30px;padding:0 10px;  border:none;  margin-top: 15px; cursor: pointer; background: #009688; color:#fff;}

/*.addcode{ margin-left:20%;}*/
.delcode{ float:right; height:30px; width: 120px; margin-top: 10px; margin-right: 10px;cursor: pointer;}

.searchcode{height:30px; border:none;  margin-top: 10px;   cursor: pointer; background: #009688; color:#fff;}
.backcode{height:30px; border:none;  margin-top: 10px; margin-left:20px;   cursor: pointer; background: #009688; color:#fff;}
 
#codecontent{ width:100%;  height:635px;  float: left; font-size: 16px;  }


#codelist li pre{

    background: #333;
    height: 137px;
    color: #fefefe;
    overflow-y: scroll; 
    overflow-x: hidden;
    padding: 10px;
    font-family: monospace;
    white-space: pre;
    margin: 10px 0px;
}
#codelist li pre code{ font-size:1.2rem; line-height: 1.2rem; }
#codelist h3{ padding: 0px; margin:0px;
    display: inline-block;
    font-size: 20px;
    font-weight: 500;
    line-height: 28px;
    word-break: break-all; }

#codelist .moveupdown{ position: absolute; width:90%; z-index: 99; height: 100%; }

a.edit_times{ color:#888; padding-left:10px; }

#codelist li span.star{ margin-left:10px;letter-spacing:1px; } 

#pages{ height:60px; margin-top:10px; }
#pages a{ height: 25px; line-height:25px; border: 1px solid #ccc; margin-left:2px; padding: 4px 12px; cursor: pointer; font-size:18px;}
#pages a.on{ font-weight: bold; color: #f00; }

#pages input{ height: 35px; line-height:25px; border: 1px solid #ccc;  padding: 0px 20px; }

.editormd-fullscreen{z-index: 99;}

.footer_fun{position: fixed; bottom: 0px; width: 100%; height: 60px; text-align:center; background: #efefef;z-index: 19891018; }
.footer_fun .open_right{display: none;}

.footer_fun.hide_right{ width:0px; right:0; } 
.open_right{width: 15px;height:55px; line-height:55px;  background:#ddd; position: absolute; left: -15px; cursor: pointer;font-size:24px; font-weight: bold; }
.footer_fun.hide_right .open_right{display: block; }
.layui-layer-page .layui-layer-content { overflow:hidden;  }

b.run_previewcode{ border:1px solid #ccc; width:60px; display:inline-block; padding 0px 5px; text-indent:10px; position:relative; top:1px; font-weight:normal; right:0px; cursor:pointer;  }

.actall{background:#000000;font-size:14px;border:1px solid #999999;padding:2px;margin-top:3px;margin-bottom:3px;clear:both;}
.filter{ width: 96%;
    padding:1% 2% 0 2%;
    display: block;
    clear: both;
    background: #fefefe;
    overflow: hidden;}

.filter dl{display:block; clear:both; height:22px;line-height:22px;  margin:0; padding:0; }
.filter dt{ font-weight:bold; width:36px; float:left;}
 
.filter dd a{ min-width:20px; float:left; padding:0; margin:0px 5px; }
 

.tag-info{ color:#888; margin:5px;}
.tag-info span{ background:#eee;  border:1px solid #ccc; padding:1px 5px; margin-right:5px; }
.cat-info{ color:#888; margin-top:0px;}
.filter dd a{ cursor:pointer;}
.filter dd a.on{ color:red;font-weight:bold;}

a.tag{ border:1px solid #ccc; padding:2px 5px; margin-right:5px; cursor:pointer; color:#333; }
a.selected{background:#eee; color:#333;}

</style>
 
 <div class="head_nav" >

   <form action="<?php ?>?"  method="get" id="search_form" thref=""  >
   	
        <input type="button" name="savecode" value="+ 新增代码" style="display:none;" onclick="addcode()" class="addcode" />  
        <input type="text" name="q" value="<?php echo $q; ?>" id="q" class="codetitle" placeholder="标题" />
        <input type="hidden" name="id" value="0" id="id"  />
        <input type="hidden" name="lang" value=""  id="lang"  />
        <input type="hidden" name="catid" value="0" id="catid"  />
        <input type="hidden" name="tag" value="" id="tag"  />
        <input type="hidden" name="order" value=""  id="order"  />
        <input type="hidden" name="ordertype" value="desc"  id="ordertype"  />

 <input type="button" thref="?" onclick="searchcode(this)" class="searchcode" id="search" value="🔍 搜索" /> 
 <input type="button" onclick="resetsearch(this)" class="searchcode" value="重 置" />
 <input type="button" value="返回编辑器"
 onclick="location.href='index.php'" class="backcode" /> 
        
  </form>
  <input type="hidden" name="title" value=""  id="edit_title" />
  <input type="hidden" name="lang" value=""  id="edit_lang"  />
  <input type="hidden" name="catid" value="0" id="edit_catid"  />
  <input type="hidden" name="tag" value="" id="edit_tag"  />
  <input type="hidden" name="listorder" value="0" id="edit_listorder"  />
        
		
 </div>

<div id="code_wrap" >
    <div  id="codecontent"  style="display: none;" > 
    </div>
 <div id="min-editormd" >
  <textarea style="display:none;" ></textarea>
</div>
    <footer class="footer_fun" style="display: none; " > 
        <div  onclick="closelayer(this)" class="open_right"  >‹</div> 
        <input type="button" name="savecode" value="编辑标题和属性" onclick="edit_title()" class="savecode" > 
        <input type="button" name="savecode" value="保存代码" onclick="savecode()" class="savecode" > 
        <input type="button" name="" onclick="runCode(this)"    class="runcode" value="执行代码">  
        <input type="button" name="" onclick="closelayer(this)"   class="close" value="收起底部">
    </footer>
 
</div>

<div class="codelist" >
	
	<div class="filter" >
		<dl>
			<dt>标签:</dt>
			
			<dd>
			<?php 
			foreach($tag_data as $value){
			 echo "<a onclick=\"choose_case(this,'tag','".$value."')\" >".$value."</a>"; 
			}
			?>
		    </dd> 
		</dl>
		<dl>
			<dt>分类:</dt>
			<dd>
			<?php 
			foreach($cat_arr as $key=>$value){
				
			 echo "<a onclick=\"choose_case(this,'catid','".$key."')\" >".$value."</a> ";
			
			}
			?>
			</dd>
			 
		</dl>
		<dl>
			<dt>语言:</dt>
			<dd>
			<?php 
			foreach($lang_data as $value){
				
			 echo "<a onclick=\"choose_case(this,'lang','".$value."')\" >".$value."</a>";
			
			}
			?>
		   </dd>
		</dl>
		<dl>
			<dt>排序:</dt>
			<dd>
			<?php 
			foreach($order_data as $key=>$value){
			 echo "<a onclick=\"choose_case(this,'order','".$key."')\" >".$value."</a> ";
			}
			?>
		   </dd>	 
		</dl>
	</div>
	
	
   <ul id="codelist" >

    <?php 
   foreach((array)$list_data as $info){
   ?>
    <li  title="<?php echo $info['title'];?>" > 
      <h3><?php echo $info['title'];?>  
         <i class="fa fa-times-circle" style="font-size:24px;" title="删除"  onclick="delCode('<?php echo base64_encode($info['title']);?>',<?php echo $info['id'];?>)"     ></i> </h3>  
         <?php 
         $info['tag'] = trim($info['tag']);
         if($info['tag']) { 
         	?>
        <div class="tag-info" ><?php
          $infotag_arr = explode(',',trim($info['tag'],','));
          foreach($infotag_arr as $tag){?><span><?php echo $tag;?></span><?php } ?>
         </div>
         <?php
         } ?> 
         <div class="cat-info" >
         分类: <?php echo isset($cat_arr[$info['catid']])?$cat_arr[$info['catid']]:'未知'; ?> |  
         语言:<?php echo $info['lang'];?>  
         发布时间: <?php echo date('Y-m-d H:i',$info['addtime']);?>  
         最后更新: <?php  echo date('Y-m-d H:i',$info['updatetime']);?> 
<a class="edit_times" target="_blank" href="code_log.php?cid=<?php echo $info['id'];?>" > 🕒 修改次数:<?php echo $info['edit_times'];?>
         </a>
         <span class="star" >★<?php echo  $info['listorder'];?></span>
         </div>
        <a onclick="show_code('<?php echo base64_encode($info['title']);?>',<?php echo $info['id'];?>)" >
          <div class="moveupdown" > </div>
          <pre><code><?php echo htmlspecialchars($info['code']);?></code></pre>
      </a>
    </li>

<?php

   }
 ?>
   </ul>

   <div id="pages" style="text-align: center;" > 
   <?php echo str_replace('href=','onclick="load_data(this)" thref=',$page->fpage(array(0,2,3,4,5,6,7))); ;?>
   </div> 

</div>
 	



<div id="resultcontent"   style="display: none; height: 100%;" >
   <iframe name="code_result" id="code_result"  src='#runcode.php' style="display:block;width:100%; height: 100%; border:none; " ></iframe>

</div>
 
<script type="text/javascript">

 function choose_case(that,field,value){

    //三态添加	第一次降序 第二次升序 第三次取消状态
    
 	if($(that).hasClass('on')){
      
      if($('#ordertype').val()=='desc' && field=='order'){
      	$('#'+field).val(value);
   
       	$('#ordertype').val('asc');
       	$(that).html($(that).html().substr(0,$(that).html().length-1)+'↑'); 
        
      }
      else{
      
       $(that).removeClass('on');
       $('#'+field).val('');
       
       if(field=='order'){
           $(that).html($(that).html().substr(0,$(that).html().length-1)+'↓'); 
       }
      	
      }
     
 		
 	}
 	else{
 	$(that).closest('dl').find('a').removeClass('on');
    $(that).addClass('on');
    
    $('#'+field).val(value);
    
    if(field=='order'){ 
    	$('#ordertype').val('desc');
        $(that).html($(that).html().substr(0,$(that).html().length-1)+'↓');
      }
 	}
 	
   //alert($(that).html());
  
   $('#search_form').attr('thref','?'+$('#search_form').serialize()); 
   
   
   load_data('#search_form');
  
 }

function utf8_to_b64(str) {
    return window.btoa(unescape(encodeURIComponent(str)));  
}

function b64_to_utf8(str) { 
    return decodeURIComponent(escape(window.atob(str)));
}
 
  function htmlencode(str){

    return $('<span/>').text(str).html(); 
 
  }

	</script>


	<script type="text/javascript" >

        var default_data ='';
  
        var ACE_editor = ace.edit('codecontent'); 
            ACE_editor.setOptions({
            enableBasicAutocompletion: true,
            enableSnippets: true,
            enableLiveAutocompletion: true
        }); 
        ACE_editor.setTheme('ace/theme/monokai'); //vibrant_ink; monokai; clouds; sqlserver; terminal; 
        ACE_editor.getSession().setMode('ace/mode/php');   //php  html   
       ACE_editor.setShowPrintMargin(false); //去掉分割线

       ACE_editor.setValue(default_data); 



 function show_code(title,id){
  //先清空旧的数据
  ACE_editor.setValue('');
  mdEditor.setValue('');
  
  title = b64_to_utf8(title);
  

  $('#edit_title').val(title);
  $('#id').val(id); 

  //
  $('#codecontent').show();
  $('#min-editormd').show();
  $('.footer_fun').show();
  
  //
  // mdEditor.previewing();
  // mdEditor.watch();
      layer.open({
      type:1,
      title:title,
      offset: '0px',
      resize: true,
      shadeClose: false,
      shade: false,     
      area: ['100%', '100%'],
      end: function () {

  $('#codecontent').hide();
  $('#min-editormd').hide();
  $('.footer_fun').hide();
  


      },
      content:$('#code_wrap') 
    });
       
   // console.log(encodeURIComponent(title)); 
  
    $.get(
        "get_mycode_one.php?id="+id+"&title="+encodeURIComponent(title),  
        function (data) {
            if (data) {
              
                  //赋值  2019 1108 05：16
                ACE_editor.setValue(data.code);
               ACE_editor.gotoLine(2);
              // ACE_editor.focus();
              
              
               if(data.lang=='javascript'){
              	ACE_editor.getSession().setMode('ace/mode/html');
              }
              else{
              	 ACE_editor.getSession().setMode('ace/mode/'+data.lang);
              }

              mdEditor.setValue(data.description);
              $('#edit_lang').val(data.lang);
              $('#edit_catid').val(data.catid);
              $('#edit_tag').val(data.tag);
              $('#edit_listorder').val(data.listorder);
              
           
  
            }
        },
        'json'
    );
 }	


// 新增代码
 function addcode(){
 var title = $('#edit_title').val();
    layer.prompt({title: '添加标题',maxlength: 1000,area:['600px','120px'], formType: 2}, function(text, index){
      layer.close(index);
      title = text;
      
      if(title==''){
      layer.msg('请输入名称');
      return false;
    }
 
 //alert(content);
   $.ajax({
            type: "POST",
            url: "./addmycode.php",   
            data: {
                title: title,
                code_body: ACE_editor.getValue() 
            },
            timeout: 5000,
            dataType: 'json',
            success: function (data) {
               // 刷新结果页
              //alert(data.status); 
    if(data.status==1){
       //重新加载代码列表
      layer.msg('添加成功！',{time:1000},function(){
       location.reload(); 	
      	
      });
      
     }

     else{

       layer.msg(data.msg,{time:1000}); 
     }
        
   },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                if (textStatus == 'timeout') {
                    var errorText = '执行超时！';
                } else {
                    var errorText = "执行失败！";
                }
                //alert(errorText);
                layer.msg(errorText,{time:2000});
            }
        });
 
       // layer.msg('您写下了：'+text); 
  }); 
  
  //获取标题和内容
 
 }
 //保存代码 包括标题和内容

  function savecode(){
    //获取id 标题 和内容
    var id =  $('#id').val(); 
    var title = $('#edit_title').val();

    var description = mdEditor.getMarkdown();
    var catid = $('#edit_catid').val();
    var lang = $('#edit_lang').val();
    var tag = $('#edit_tag').val();
    var listorder = $('#edit_listorder').val();
 
  
    if(title==''){

      return false;
    }
  //alert(content);
   $.ajax({
            type: "POST",
            url: "./edit_code.php", 
            data: {
                id: id, 
                title: title,
                catid:catid,
                lang:lang,
                tag:tag,
                listorder:listorder,
                description:description,
                
                code_body: ACE_editor.getValue() 
            },
            timeout: 5000,
            //dataType: 'json',
            success: function (data) {
            
    data = $.parseJSON(data); 
               // 刷新结果页
              //alert(data.status); 
    if(data.status==1){
               //重新加载代码列表
      layer.msg(data.msg,{time:1000});
      reloadcode();
    

     }
        
   },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                if (textStatus == 'timeout') {
                    var errorText = '执行超时！';
                } else {
                    var errorText = "执行失败！";
                }
                //alert(errorText);
                layer.msg(XMLHttpRequest.status+errorText,{time:2000});
            }
        });


  }

//搜索
function searchcode(that){

 $(that).attr('thref','?'+$('#search_form').serialize());
  load_data(that);
	
}
//重置搜索
function resetsearch(that){
location.href='mycode.php';
 	
}
 
 // 提交代码
    function runCode(that) {
        //执行前 自动保存
       // savecode();
       
       //识别下语言 默认php  其他的可能是sql  html
        var run_url = './runcode.php';
        var lang = $('#edit_lang').val();
       
       if(lang!='php'){
        	
           run_url = './run'+lang+'.php'; 	
        }
       

        $.ajax({
            type: "POST",
            url: "./save_code.php", 
            data: {
                code_path: run_url, 
                code_body: ACE_editor.getValue(), 
                lang:lang,
                php_version: ' '
            },
            timeout: 5000,
            dataType: 'json',
            success: function (data) {
 
                  layer.open({
                      type:1,
                      offset: '40%',
                      title:'运行结果',
                      resize: true,
                      shadeClose: false,
                      shade: false,
                      maxmin: false, //开启最大化最小化按钮 
                      area: ['90%', '50%'],
                      end: function () { $('#resultcontent').hide(); },
                      content:$('#resultcontent') 
                    });
  
        document.getElementById("code_result").src ='./loading.php?t='+new Date(); 
        
		setTimeout(function(){
		
		    document.getElementById("code_result").src =run_url+'?cname=中文&t='+new Date(); 
		},500);
		
		
		
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                if (textStatus == 'timeout') {
                    var errorText = '执行超时！';
                } else {
                    var errorText = "执行失败！";
                }

                layer.msg(errorText,{time:2000});
            }
        });
    }

    // 删除代码
    function delCode(title,id) {

       title = b64_to_utf8(title);
  
    if(title==''){

      return false;
    }

     layer.confirm('确定要删除吗？', {btn: ['确定', '取消'], title: "提示"},function(){

     $.ajax({
            type: "POST",
            url: "./delete_code.php", 
            data: {
                id:id,
                title:title,
                php_version: ' '
            },
            timeout: 5000,
            dataType: 'json',
            success: function (data) {
             
                // 刷新结果页
   
            layer.msg(data.msg,{time:2000}); 
            location.reload(); 
             //reloadcode();
        
        
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                if (textStatus == 'timeout') {
                    var errorText = '执行超时！';
                } else {
                    var errorText = "执行失败！";
                }
                 
                 layer.msg(errorText,{time:2000});
            }
        });


     });  
        
    }
 
  function closelayer(that){
 
        if($('.footer_fun').hasClass('hide_right')){
       
        $('.footer_fun').removeClass('hide_right');	
         $(".footer_fun").animate({left:0},"slow",function(){ 
        	
         	
        	
        });	
        	
        }
        else{
          
        	$(".footer_fun").animate({left:'100%'},"slow",function(){ 
        	
            $('.footer_fun').addClass('hide_right'); 	
        	
        });
        	
        	
        }
        
  
  }

 function choose_tag(that){
 	
   var tag_on  = $(that).hasClass('selected');	
   var tag_val = $('#edit_tag').val();
   var choose_val = $(that).attr('val'); 
   
   if(tag_on){
   	
   	$(that).removeClass('selected');
   	
   	 tag_val = tag_val.replace(choose_val,'');
   }
   else{
   	
   	$(that).addClass('selected');
   	tag_val+=','+$(that).attr('val');
   }
   
   tag_val = $.trim(tag_val);
   
   $('#edit_tag').val(tag_val); 
 	
 }
 
 
 
  function edit_title(){
    var title = $('#edit_title').val();
    var lang = $('#edit_lang').val();
    var catid = $('#edit_catid').val();
    var tag = $('#edit_tag').val();
    var listorder = $('#edit_listorder').val();
   
   var catarr = <?php echo json_encode($cat_data);?>;
   var langarr = <?php echo json_encode($lang_data);?>;
   var tagarr = <?php echo json_encode($tag_data);?>;
   var listorderarr = <?php echo json_encode($listorder_data);?>;
   
    var optionString = '<option value="0">请选择分类</option>'; 
    var selected_str ='';
    for(let i in catarr) {
    	
    	if(catid== catarr[i].catid ){
    	 selected_str =' selected ';	
    	}else{
    	  selected_str ='';	
    	}
    	
        optionString =optionString+'<option value="'+catarr[i].catid+'" '+selected_str+' >'+catarr[i].catname+"</option>";
    }
    
    var optionString2 = '<option value="">请选择语言</option>';;
    for(let i in langarr) {
    	
    	if(lang==langarr[i]){ 
    	 selected_str =' selected ';	
    	}else{
    	  selected_str ='';	
    	}
    	
        optionString2 =optionString2+'<option value="'+langarr[i]+'" '+selected_str+' >'+langarr[i]+"</option>";
    }
  
      var optionString_listorder = '<option value="-1">推荐星级</option>';;
    for(let i in listorderarr) {
    	
    	if(listorder==i){ 
    	 selected_str =' selected ';	
    	}else{
    	  selected_str ='';	
    	}
    	
        optionString_listorder =optionString_listorder+'<option value="'+i+'" '+selected_str+' >'+listorderarr[i]+"</option>";
    }
   
   
     var optionString_tag = ' ';
     
     console.log(tag.split(','));
    for(let i in tagarr) {
    	
    	if($.inArray(tagarr[i],tag.split(','))!=-1){ 
    	 selected_str =' class="tag selected" '; 	
    	}else{
    	  selected_str =' class="tag "  ';	
    	}
    	
        optionString_tag =optionString_tag+'<a onclick="choose_tag(this)" val="'+tagarr[i]+'"  '+selected_str+' >'+tagarr[i]+"</a>";
    }
    
    
 layer.prompt({
 	title: '编辑标题和属性',
 value:title,
 maxlength: 1000,
 id:'editcontent',
 area:['600px','120px'], 
 formType: 2}, 
 function(text, index){
 	  
      layer.close(index);
      $('#edit_title').val(text);
      $('#edit_catid').val($('#select_catid').val());
      $('#edit_lang').val($('#select_lang').val());
      $('#edit_listorder').val($('#select_listorder').val());
      
      savecode(); 
    // layer.msg('您写下了：'+text); 
 
  }); 

 $("#editcontent").append("<br/>分类: <select name='catid' id='select_catid'  >"+optionString+"</select>" 
 +"  语言:<select  name='lang' id='select_lang'  >"+optionString2+"</select>"
 +"  推荐:<select  name='listorder' id='select_listorder'  >"+optionString_listorder+"</select>"
 +"  <p  name='tag' id='select_tag' > 标签:"+optionString_tag+"</p>"
 
 
 
 ); 
 
  }


function removeEmpty(arr){   
  for(var i = 0; i < arr.length; i++) {
   if($.trim(arr[i]) == "" || typeof(arr[i]) == "undefined") {
      arr.splice(i,1);
      i = i - 1; // i - 1 ,因为空元素在数组下标 2 位置，删除空之后，后面的元素要向前补位
    }
   }
   return arr;
} 


function load_data(that){

  var urldata = $(that).attr('thref').split('?');
  var loading_index = layer.load(1, { 
  shade: [0.3,'#fff'] //0.1透明度的白色背景
});

  var catarr = <?php echo json_encode($cat_arr); ?> 
  //异步加载代码
  $.ajax({
            type: "get",
            url: "./ajax_data.php", 
            data: urldata[1],
            timeout: 5000,
            dataType: 'json',
            success: function (data) {
             // console.log(data);
    layer.close(loading_index);
    var str ='';
    var jsondata =  data.data; 
    var tag_arr =[];
    var tag_list = '';
    var taghtml='';
 
   $('#codelist').html('');
 
 $.each(jsondata,function(n,val){
         // console.log(n,val);
        tag='';
    	tag_list='';
    	taghtml='';
    	
    	if(val.tag){
    		tag_arr = val.tag.split(',');
    		tag_arr = removeEmpty(tag_arr);
    		 
    		 for(var i in tag_arr){
    		 	
    		  tag_list+='<span>'+tag_arr[i]+'</span>';  	 
    		 }

    	    taghtml ='<div class="tag-info" >'+tag_list+'</div>';
    	}
 
     str +='<li  title="'+val.title+'" >'+ 
      ' <h3> '+val.title+' <i class="fa fa-times-circle" title="删除"   onclick="delCode(\''+utf8_to_b64(val.title)+'\','+val.id+')"    ></i></h3>'+
      taghtml+
      ' <div class="cat-info" > '+
         '分类: '+(catarr[val.catid]?catarr[val.catid]:'未知')+ 
         '  | 语言:'+val.lang+  
         ' 发布时间: '+date("Y-m-d H:i",val.addtime)+'  '+ 
         ' 最后更新: '+date("Y-m-d H:i",val.updatetime)+
         '<a class="edit_times" target="_blank" href="code_log.php?cid='+val.id+'" > 🕒 修改次数:'+val.edit_times+
         ' <span class="star">★'+val.listorder+ 
         ' </div>'+
       '<a onclick="show_code(\''+utf8_to_b64(val.title)+'\','+val.id+')" > <div class="moveupdown" ></div> '+
       '<pre><code>'+htmlencode(val.code)+'</code></pre>'+
      '</a></li>';
 	
 	
 });
 
 
 
  $('#codelist').html(str);


             $('#pages').html(data.pages);

            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                if (textStatus == 'timeout') {
                    var errorText = '执行超时！';
                } else {
                    var errorText = "执行失败！";
                }
                 
                 layer.msg(errorText,{time:2000});
                 layer.close(loading_index);
            }
        });

}

 
// 和PHP一样的时间戳格式化函数
// @param  {string} format    格式
// @param  {int}    timestamp 要格式化的时间 默认为当前时间
// @return {string}           格式化的时间字符串
function date(format, timestamp ) {
    var a, jsdate=((timestamp) ? new Date(timestamp*1000) : new Date());
    var pad = function(n, c){
        if( (n = n + "").length < c ) {
            return new Array(++c - n.length).join("0") + n;
        } else {
            return n;
        }
    };
    var txt_weekdays = ["Sunday","Monday","Tuesday","Wednesday", "Thursday","Friday","Saturday"];        

    var txt_ordin = {1:"st",2:"nd",3:"rd",21:"st",22:"nd",23:"rd",31:"st"};

    var txt_months = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

    var f = {
        // Day
            d: function(){
                return pad(f.j(), 2);
            },
            D: function(){
                t = f.l(); return t.substr(0,3);
            },
            j: function(){
                return jsdate.getDate();
            },
            l: function(){
                return txt_weekdays[f.w()];
            },
            N: function(){
                return f.w() + 1;
            },
            S: function(){
                return txt_ordin[f.j()] ? txt_ordin[f.j()] : 'th';
            },
            w: function(){
                return jsdate.getDay();
            },
            z: function(){
                return (jsdate - new Date(jsdate.getFullYear() + "/1/1")) / 864e5 >> 0;
            },

        // Week
            W: function(){
                var a = f.z(), b = 364 + f.L() - a;
                var nd2, nd = (new Date(jsdate.getFullYear() + "/1/1").getDay() || 7) - 1;

                if(b <= 2 && ((jsdate.getDay() || 7) - 1) <= 2 - b){
                    return 1;
                } else{

                    if(a <= 2 && nd >= 4 && a >= (6 - nd)){
                        nd2 = new Date(jsdate.getFullYear() - 1 + "/12/31");
                        return date("W", Math.round(nd2.getTime()/1000));
                    } else{
                        return (1 + (nd <= 3 ? ((a + nd) / 7) : (a - (7 - nd)) / 7) >> 0);
                    }
                }
            },

        // Month
            F: function(){
                return txt_months[f.n()];
            },
            m: function(){
                return pad(f.n(), 2);
            },
            M: function(){
                t = f.F(); return t.substr(0,3);
            },
            n: function(){
                return jsdate.getMonth() + 1;
            },
            t: function(){
                var n;
                if( (n = jsdate.getMonth() + 1) == 2 ){
                    return 28 + f.L();
                } else{
                    if( n & 1 && n < 8 || !(n & 1) && n > 7 ){
                        return 31;
                    } else{
                        return 30;
                    }
                }
            },

        // Year
            L: function(){
                var y = f.Y();
                return (!(y & 3) && (y % 1e2 || !(y % 4e2))) ? 1 : 0;
            },
            //o not supported yet
            Y: function(){
                return jsdate.getFullYear();
            },
            y: function(){
                return (jsdate.getFullYear() + "").slice(2);
            },

        // Time
            a: function(){
                return jsdate.getHours() > 11 ? "pm" : "am";
            },
            A: function(){
                return f.a().toUpperCase();
            },
            B: function(){
                // peter paul koch:
                var off = (jsdate.getTimezoneOffset() + 60)*60;
                var theSeconds = (jsdate.getHours() * 3600) +
                                 (jsdate.getMinutes() * 60) +
                                  jsdate.getSeconds() + off;
                var beat = Math.floor(theSeconds/86.4);
                if (beat > 1000) beat -= 1000;
                if (beat < 0) beat += 1000;
                if ((String(beat)).length == 1) beat = "00"+beat;
                if ((String(beat)).length == 2) beat = "0"+beat;
                return beat;
            },
            g: function(){
                return jsdate.getHours() % 12 || 12;
            },
            G: function(){
                return jsdate.getHours();
            },
            h: function(){
                return pad(f.g(), 2);
            },
            H: function(){
                return pad(jsdate.getHours(), 2);
            },
            i: function(){
                return pad(jsdate.getMinutes(), 2);
            },
            s: function(){
                return pad(jsdate.getSeconds(), 2);
            },
            //u not supported yet

        // Timezone
            //e not supported yet
            //I not supported yet
            O: function(){
               var t = pad(Math.abs(jsdate.getTimezoneOffset()/60*100), 4);
               if (jsdate.getTimezoneOffset() > 0) t = "-" + t; else t = "+" + t;
               return t;
            },
            P: function(){
                var O = f.O();
                return (O.substr(0, 3) + ":" + O.substr(3, 2));
            },
            //T not supported yet
            //Z not supported yet

        // Full Date/Time
            c: function(){
                return f.Y() + "-" + f.m() + "-" + f.d() + "T" + f.h() + ":" + f.i() + ":" + f.s() + f.P();
            },
            //r not supported yet
            U: function(){
                return Math.round(jsdate.getTime()/1000);
            }
    };

    return format.replace(/[\\]?([a-zA-Z])/g, function(t, s){
        if( t!=s ){
            // escaped
            ret = s;
        } else if( f[s] ){
            // a date function exists
            ret = f[s]();
        } else{
            // nothing special
            ret = s;
        }

        return ret;
    });
}

</script>
 
<script src="md-editor/js/jquery.min.js"></script>
<script src="md-editor/js/editormd.js"></script> 
<script type="text/javascript">
      var mdEditor;

            $(function() {
                mdEditor = editormd("min-editormd", {
                    width   : "100%",
                    height  : 640,
                    syncScrolling : "single",
                    path    : "./md-editor/js/lib/",
                        // theme : "dark",
                        // previewTheme : "dark",
                        // editorTheme : "pastel-on-dark",
                        markdown : mdEditor,
                        toolbarIcons:'full',
                        codeFold : true,
                        //syncScrolling : false,
                        saveHTMLToTextarea : true,    // 保存 HTML 到 Textarea
                        searchReplace : true,
                        //watch : false,                // 关闭实时预览
                        htmlDecode : "style,script,iframe|on*",            // 开启 HTML 标签解析，为了安全性，默认不开启    
                        //toolbar  : false,             //关闭工具栏
                        //previewCodeHighlight : false, // 关闭预览 HTML 的代码块高亮，默认开启
                        emoji : true,
                        taskList : true,
                        tocm            : true,         // Using [TOCM]
                        tex : true,                   // 开启科学公式TeX语言支持，默认关闭
                        flowChart : true,             // 开启流程图支持，默认关闭
                        sequenceDiagram : true,       // 开启时序/序列图支持，默认关闭,
                        //dialogLockScreen : false,   // 设置弹出层对话框不锁屏，全局通用，默认为true
                        //dialogShowMask : false,     // 设置弹出层对话框显示透明遮罩层，全局通用，默认为true
                        //dialogDraggable : false,    // 设置弹出层对话框不可拖动，全局通用，默认为true
                        //dialogMaskOpacity : 0.4,    // 设置透明遮罩层的透明度，全局通用，默认值为0.1
                        //dialogMaskBgColor : "#000", // 设置透明遮罩层的背景颜色，全局通用，默认为#fff
                        imageUpload : true,
                        imageFormats : ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
                        imageUploadURL : "./php/upload.php",
                        onload : function() {
                            // console.log('onload', this);
                            //this.fullscreen();
                            //this.unwatch();
                            this.previewing();
 
                            //this.setMarkdown("#PHP");
                            //this.width("100%");
                            //this.height(480);
                            //this.resize("100%", 640);
                          
                           $('#min-editormd').hide();
                           moveupdown();
                            
                        }
                });
                
                /*
                // or
                mdEditor = editormd({
                    id      : "min-editormd",
                    width   : "90%",
                    height  : 640,
                    path    : "../lib/"
                });
                */
               
              //setTimeout(function(){ $('#min-editormd').hide();},100); 


            });

 
 
 
  function togglefullscreen(){
  	
    mdEditor.state.fullscreen?mdEditor.fullscreenExit():mdEditor.fullscreen();	
  }                         	
                           	    
                           	    
                          
 var ACE_editor_tp='';
 
 function run_previewcode(that){
 
  var data_code =''; 
  
  
  $(that).next().find('.linenums>li').each(function(){
  	
    data_code+=$(this).text()+'\n';  	
  	
  	
  });
   
 title = '运行代码';
 
 parent.layer.open({
      type: 1,
      title:title,
      id:"code_content",
      scrollbar: false,
      style:'position:relative',
     // offset: '10px',
      area: ['80%', '600px'],
      shadeClose: true, //点击遮罩关闭
      content: '<div id="editor_showcode" style="height:500px;"  >' +data_code+'</div> <footer  style="text-align:center; " >'+ 
        '<input type="button" name="" onclick="runCode_tp(this)"     class="runcode" value="执行代码"> '+
        '</footer>'
    });  
  //增加编辑器 
  
        ACE_editor_tp = ace.edit('editor_showcode');
        ACE_editor_tp.setOptions({
            enableBasicAutocompletion: true,
            enableSnippets: true,
            enableLiveAutocompletion: true
        }); 
      ACE_editor_tp.setTheme('ace/theme/sqlserver'); //vibrant_ink; monokai; clouds; sqlserver; terminal; 
      ACE_editor_tp.getSession().setMode('ace/mode/php');  //php  html  
      ACE_editor_tp.setShowPrintMargin(false); //去掉分割线   
   
 
 
       
     //赋值  2019 1108 05：16
     ACE_editor_tp.setValue(data_code); 
     ACE_editor_tp.gotoLine(2);
     // ACE_editor_tp.focus();
   
 	
 }

 function runCode_tp(that) {
        //执行前 自动保存
       // savecode();
       var run_url = './runcode.php';
       var lang = $('#edit_lang').val();
       if(lang!='php'){
        	
           run_url = './run'+lang+'.php'; 	
        }

        $.ajax({
            type: "POST",
            url: "./save_code.php", 
            data: {
                code_path: run_url,
                code_body: ACE_editor_tp.getValue(), 
                php_version: ' '
            },
            timeout: 5000,
            dataType: 'json',
            success: function (data) {
 
                  layer.open({
                      type:1,
                      offset: '40%',
                      title:'运行结果', 
                      resize: true,
                      shadeClose: false,
                      shade: false,
                      maxmin: false, //开启最大化最小化按钮 
                      area: ['90%', '50%'],
                      end: function () { $('#resultcontent').hide(); },
                      content:$('#resultcontent') 
                    });
  
        document.getElementById("code_result").src ='./loading.php?t='+new Date(); 
        
		setTimeout(function(){
		
		    document.getElementById("code_result").src =run_url+'?cname=中文&t='+new Date();  
		},500);
		
		
		
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                if (textStatus == 'timeout') {
                    var errorText = '执行超时！';
                } else {
                    var errorText = "执行失败！";
                }

                layer.msg(errorText,{time:2000});
            }
        });
    }


function moveupdown(){

 var title_mov = $('.editormd-toolbar').eq(0)[0];
 var editor_height = parseInt(mdEditor.settings.height);
 var editor_top = parseInt($('#min-editormd').css('top'));
var y = 0;
var t = 0;
var isDown = false;
//鼠标按下事件
title_mov.onmousedown=function(e) {
    //获取y坐标
    y = e.clientY;

    //获取顶部的偏移量
    t = title_mov.offsetTop;
    //开关打开
    isDown = true;
    //设置样式  
    title_mov.style.cursor = 'move';
    editor_height = $('#min-editormd').height();
    editor_top = parseInt($('#min-editormd').css('top'));
    //console.log();
 
};
//鼠标移动
window.onmousemove=function(e) {
    if (isDown == false) {
        return;
    }
  //console.log(e.target);

 if($(e.target).closest('.editormd-toolbar').length){  

    //计算移动后的顶部的偏移量 ===title_mov
    var nt = e.clientY - (y - t);
     console.log(editor_top);
    //this.height(480);
    mdEditor.resize("100%", editor_height-nt);
    $('#min-editormd').css('top',editor_top+nt);
    console.log(editor_top);
    //editor_top = nt;
  
  }
  else{

   isDown = false;

  }

}

//鼠标抬起事件
title_mov.onmouseup=function() {
    //开关关闭
    isDown = false;
    title_mov.style.cursor = 'default';
  
}	
	
	
	
}
 
   </script>
</body>
</html>
