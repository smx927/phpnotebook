 <head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />   
</head>
<style type="text/css">
    *{padding:0px;margin:0px;}
    table.tablecontent tr:hover td{ background:#444; cursor:pointer;}  
    
    body,td{font-size: 12px;color:#00ff00;background:#292929;}input,select,textarea{font-size: 12px;background-color:#FFFFCC;border:1px solid #fff}
    body{color:#FFFFFF;font-family:Verdana, Arial, Helvetica, sans-serif;
    height:100%;overflow-y:auto;background:#333333;SCROLLBAR-FACE-COLOR: #232323; SCROLLBAR-HIGHLIGHT-COLOR: #232323; SCROLLBAR-SHADOW-COLOR: #383838; SCROLLBAR-DARKSHADOW-COLOR: #383838; SCROLLBAR-3DLIGHT-COLOR: #232323; SCROLLBAR-ARROW-COLOR: #FFFFFF;SCROLLBAR-TRACK-COLOR: #383838;}
    input,select,textarea{background-color:#FFFFCC;border:1px solid #FFFFFF}
    a{color:#ddd;text-decoration: none;}
    a:hover{color:red; }
    
    .actall{background:#000000;font-size:14px;border:1px solid #999999;padding:2px;margin-top:3px;margin-bottom:3px;clear:both;}
    
    table.tablecontent tr.hover td{ background:#555; cursor:pointer;}  
    
    .layui-btn-normal {
    background-color: #1E9FFF;
}
 
.layui-btn {
    display: inline-block;
    height: 38px;
    line-height: 38px;
    padding: 0 18px;
    background-color: #009688;
    color: #fff;
    white-space: nowrap;
    text-align: center;
    font-size: 14px;
    border: none;
    border-radius: 2px;
    cursor: pointer;
}
.layui-layer-content{ color: #666;} 
    </STYLE>
<script src="ace/jquery.min.js" ></script>
<script type="text/javascript" src="layer/layer.js" ></script>
 <?php
   
   $lang_data = array('php','golang','python','sql','java','c','c++','html','txt','linux命令','git命令'); 

 ?> 
 <title>在线运行代码 </title>
 <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport" />
 <link rel="stylesheet" href="css/bootstrap.min.css">
 <link rel="stylesheet" href="css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="./js/jquery.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/bootstrap3-typeahead.min.js"></script>
    <!--load jqwidgets-->
    <link rel="stylesheet" href="./css/jqx.base.css">  
    <script src="./js/jqxcore.js"></script>
    <script src="./js/jqxsplitter.js"></script>
    <link rel="stylesheet" thref="./style/skin/base/app_code_edit.css" />


    <style type="text/css">

   #footer{ position: fixed; bottom: 0px; width: 100%; display:block; background: #CCC; line-height: 60px; height:60px; } 

   #footer button{
   display: inline-block;  margin-top: 5px;} 

   #left_part{ overflow-y: scroll; } 

   body{ overflow-x: hidden; }
 
   .ace_scrollbar-v {
     overflow-x: hidden;
     overflow-y: hidden;  
    overflow: hidden;
    top: 0;
}

    </style>
    <!-- load ace -->
<script  type="text/javascript" src="./ace/src-min-noconflict/ace.js"  charset="UTF-8"  ></script>
<script src="./ace/src-min-noconflict/ext-language_tools.js"></script>
    
    
    <!--mousetrap-->
    <script src="./js/mousetrap.min.js"></script>
    <!--init value-->
    <script type="text/javascript">
        var code_path = '';  
        // 获取根路径
        function getCodePath() {
            return code_path;
        }
        // 设置跟路径
        function setCodePath(value) {
        
               code_path = value;
          
        }
    </script>

 <nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">导航</span> 
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
             
        </div>
        <div id="navbar" class="collapse navbar-collapse">
        	 
            <ul class="nav navbar-nav">
            	<li><div class='diy_select'>

	            <div class="select-text">
	
	                <input type='hidden' name='lang' value="php"   id="lang" class='diy_select_input' />
	
	                <div class='diy_select_txt'>选择语言</div>
	
	                <div class='diy_select_btn'></div>
	
	            </div>
	
	            <div class="select-option">
	
	                <ul class='diy_select_list'>
	                    <?php foreach($lang_data as $lang){ ?>
	                    <li><?php echo $lang; ?></li>
	                    <?php } ?>
	                </ul>
	
	            </div>
	
	        </div> </li>
                <li><a id="code_run" href="javascript:runCode();"><img id="run_img" src="./images/run.png" height="20" />&nbsp;运行代码</a> </li>
                <li><a id="code_run" href="javascript:savetoMyCode();"><img src="./images/save.png" height="20" />&nbsp;存入我的代码</a> </li>
                
                
                <li><a href="./code_log.php" target="_blank" ><img src="./images/log.png" height="20" />&nbsp;修改记录</a> </li>
                
                <li><a href="code_countline.php" target="_blank" ><img src="./images/line.png" height="20" />&nbsp;统计图</a> </li>
                
                
                
            </ul>
            <ul class="nav navbar-nav navbar-right"> 
                <li><a href="./mycode.php" target="_blank" ><img src="./images/code.png" height="20" /> 我的代码</a></li>
                 
            </ul>
        </div>
    </div>
</nav>


 <div class='container-fluid' style="margin:0px;  padding:0px;" id="main_canvas">
    <div id='jqxSplitter'>
        <div id="left_part">
            <div id="code_body"   style="font-size:18px;"></div>
        </div>
        <div id="right_part" style=" border-left:42px solid #ccc; ">
            <iframe name="code_result" id="code_result" src="" width="99%" style="border:0"></iframe>
        </div>
    </div>
</div>
<script type="text/javascript">
    // 初始化左右分栏


   

    $('#jqxSplitter').jqxSplitter({
        width: '100%',
        height: '100%',
        splitBarSize: 24,
        orientation: 'horizontal', // horizontal,vertical
        panels: [
            {size: '80%', collapsible: false},
            {size: '20%', collapsible: true}
        ]
    });
    // 调整编码区高度
    function resizeCodeArea() {
        var frameHeight = $(document).height()-50;
        $('#main_canvas').height(frameHeight);
        $('#jqxSplitter').height(frameHeight);
        $('#code_body').height(frameHeight*8/10); 
        $('#code_result').height(frameHeight);
    }

    // 自定义高度
    resizeCodeArea();
    // 页面调整时候变更高度
    $(window).resize(function () {
       // resizeCodeArea();
    });

    // trigger extension
    var editor = ace.edit('code_body');
    editor.session.setMode('ace/mode/php');
   
    editor.setTheme('ace/theme/monokai');   //sqlserver monokai vibrant_ink
    // enable autocompletion and snippets
    editor.setOptions({
        enableBasicAutocompletion: true,
        enableSnippets: true,
        enableLiveAutocompletion: true
    });
    editor.setShowPrintMargin(false);
    editor.gotoLine(2);

    // 自动延迟保存代码
    var autoSave;
    editor.getSession().on('change', function (e) {
        if (autoSave) {
            clearTimeout(autoSave);
        }
        // autoSave = setInterval('saveCode()', 500);
    });

    // 发送提示信息
    function sendCodeNotice(msg) {
        return;
    }

    // 异步填充代码区域 myaddress
    $.get(
        "./default_code.php", 
        function (data) {
            if (data) {
                // 调整PHP版本号
                // 设置路径
                setCodePath(data.code_path);
                editor.setValue(data.code_body);
                if (editor.session.getLength() <= 1000) {
                    editor.gotoLine(editor.session.getLength());
                }
                editor.focus(); 
                // 刷新结果页
		document.getElementById('code_result').src = code_path; //+'?t='+new Date(); 
          
         $('#lang').val(data.lang);
         $('.diy_select_txt').html(data.lang); 
         
         editor.session.setMode('ace/mode/'+data.lang); 
         runCode();
		
            
            }
        },
        'json'
    );

    // 保存代码
    function saveCode() {
        $.post('./save_code.php',
            {
                code_path: getCodePath(),
                code_body: editor.getValue(),
                lang:$('#lang').val(),
                php_version: $('#php_version').val()
            },
            function (data) {
                if (data) {
                    // 设置路径
                    setCodePath(data.code_path);
                }
            },
            'json'
        );
    }

    // 提交代码
    function runCode() {

        $('#run_img').attr('src','./images/runing.png');
        $.ajax({
            type: "POST",
            url: "./save_code.php", 
            data: {
                code_path: getCodePath(),
                code_body: editor.getValue(),
                lang:$('#lang').val(),
                php_version: ' '
            },
            timeout: 5000,
            dataType: 'json',
            success: function (data) {
                 // 设置路径
              setCodePath(data.code_path);
                // 刷新结果页
  
        document.getElementById("code_result").src ='./loading.php?t='+new Date(); 
        
		setTimeout(function(){
		
		    document.getElementById("code_result").src =code_path+'?t='+new Date(); 
            $('#run_img').attr('src','./images/run.png');

		},1000);
		
		
		
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                if (textStatus == 'timeout') {
                    var errorText = '执行超时！';
                } else {
                    var errorText = "执行失败！";
                }
                sendCodeNotice("<span style='color:red'>" + errorText + "</span>");
            }
        });
    }
    
    
    //存入我的代码
    
    function savetoMyCode(){
    
        var title ='';
        
 layer.prompt({title: '请输入标题',maxlength: 1000,area:['600px','120px'], formType: 2}, function(text, index){
    layer.close(index);
      // layer.msg('您最后写下了：'+text);
    
     // 首先要取一个标题
          $.ajax({
            type: "POST",
            url: "./addmycode.php", 
            data: {
                title: text,
                code_body: editor.getValue(),
                lang:$('#lang').val(),
                php_version: ' '
            },
            timeout: 5000,
            dataType: 'json',
            success: function (data) {
            
  
                if(data.status==1){

                  layer.msg(data.msg);
                }
                else{
                   layer.msg(data.msg);
                }
		
		
		
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                if (textStatus == 'timeout') {
                    var errorText = '执行超时！';
                } else {
                    var errorText = "执行失败！";
                }
                
                layer.msg(errorText); 
                sendCodeNotice("<span style='color:red'>" + errorText + "</span>"); 
            }
        });
    
    
    
    
  });
    
   
    }
    
 

    // 绑定快捷键
    editor.commands.addCommand({
        name: 'myCommandR',
        bindKey: {win: 'Ctrl-R', mac: 'Command-R'},
        exec: function (editor) {
            runCode();
        },
        readOnly: false
    });
    editor.commands.addCommand({
        name: 'myCommandS',
        bindKey: {win: 'Ctrl-S', mac: 'Command-S'},
        exec: function (editor) {
            saveCode(); 
        },
        readOnly: false
    });
    editor.commands.addCommand({
        name: 'myCommandB',
        bindKey: {win: 'Ctrl-B', mac: 'Command-B'},
        exec: function (editor) {
            beautifyCode();
        },
        readOnly: false
    });
    // 绑定执行快捷键 --执行代码
    Mousetrap.bind(['ctrl+r', 'command+r'], function (e) {
        runCode();
    });
    // 绑定保存快捷键 -- 保存代码
    Mousetrap.bind(['ctrl+s', 'command+s'], function (e) {
        saveCode();
    });

    function myphpcode(title,fileurl){
      location.href=fileurl;
       return false;

        
      layer.closeAll();
      layer.open({
      type:2,
      title:title,
      offset: '0px',
       shadeClose: false,
       shade: false,
      
      area: ['100%', '100%'],
      content:fileurl+'?t='+new Date()
    });
      
    }
 
 
 function changelang(that){
 
 var lang = $(that).val();	
 console.log(lang);
 editor.session.setMode('ace/mode/'+lang);	
 	
 }

</script>


 <style type="text/css">
   /* diy_select */
 
 .diy_select { min-width: 120px; *width: 120px;height: 50px; margin-right: 5px; position: relative; color: #000;  cursor: pointer; }
 .diy_select_btn,
 .diy_select_txt { float: left; height: 100%; line-height: 50px; }
 .diy_select, .diy_select_list { }
 .select-text { padding:0;  height: 100%; width: 100%; }
 .diy_select_txt { width: 80%; }
 .diy_select_txt, .diy_select_list li { text-indent: 10px; overflow: hidden; }
 .diy_select_btn { width: 20%; background: url(./images/select_log.png) no-repeat center; }
 .select-option { width: 100%; }
 .diy_select_list {  position: absolute; top: 51px; left: -1px; z-index: 88888;

            border-top: none;

            width: 100%;

            display: none;

            background: #fff;

            overflow: auto;

        }

        .diy_select_list li {

            margin: 0;

            list-style: none;

            height:30px;

            line-height:30px;

            cursor: default;

            background: #fff; 

        }

        .diy_select_list li.focus {
 
            cursor: pointer;

            background: #3399FF;

            color: #fff

        }

        section {

            margin-top: 20%;

            margin-left: 20%;

        }

 </style>
<script src="./js/diy_select.js"></script>	 
 <footer id="footer" style="display:none;" >

     <button type="button" onclick="savetoMyCode();"   class="btn btn-default btn-lg">存入我的代码</button>
     <button type="button" onclick="runCode();"   class="btn btn-default btn-lg">运行代码</button>
     
     <a class="btn btn-default btn-lg" href="./mycode.php" target="_blank" >我的代码</a>
 
 
 </footer>