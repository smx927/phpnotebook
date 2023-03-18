<?php
 @date_default_timezone_set("PRC");
 header("Content-type:text/html; charset=utf-8"); //指定下编码 
 include 'Database.class.php';
 $tdata = array();
 $tdata['name'] = 'mycode.db'; 
 $tdata['path'] = './data/mycode.db';
 $tdata['type'] = 3;
 $db = new Database($tdata); 


 include 'page.class.php';