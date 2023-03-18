<?php
//获取运行的代码 不能直接访问runcode.php不然变成运行php的结果
 $code = file_get_contents('runcode.php'); 
 $lang = file_get_contents('lang.php'); 
//$data = include('runcode.php');
 
$codearr = array('php_version' => PHP_VERSION, 'code_path' => 'http://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']) . "/runcode.php", 'code_body' => $code, 'lang' => $lang);

exit(json_encode($codearr));

//  {"php_version":71,"code_path":"","code_body":"<?php\n\n"}
?>



