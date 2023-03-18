<?php
 @date_default_timezone_set(@date_default_timezone_get());
 header("Content-type:text/html; charset=utf-8"); //指定下编码 
 include 'Database.class.php';
 $tdata = array();
 $tdata['name'] = 'sqlzoo.db'; 
 $tdata['path'] = './data/sqlzoo.db';
 $tdata['type'] = 3;
 $db = new Database($tdata); 


 include 'page.class.php';