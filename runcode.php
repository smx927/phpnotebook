<?php
// array_chunk分割数组
$input_array = array('a', 'b', 'c', 'd', 'e');
print_r(array_chunk($input_array, 3));
echo'<br/>';
print_r(array_chunk($input_array, 3, true));

//-----------------------------------
//所有的数组操作都包括以下
// 1  数组添加值 修改值   删除值  获取键值 $arr[] = 1;  $arr[1]=2;   unset($arr[1])  array_keys(); 
// 2  数组第一个值 最后一个值  指定键值 reset(); end() 
// 3  数组从开头添加array_unshift() 或结尾添加数据array_push 从开头删除 array_shift 或结尾删除数据 array_pop
// 4  数组输出每项键和值  foreach() 
// 5  数组的 拆分 查找 插入 替换 array_chunk() array_search() in_array array_key_exists()  array_slice() 
// 6  计算数组长度 count()
// 7  数组的排序   按照值 sort() rsort()    键名 ksort() krsort()
// 8  数组去空  array_filter()
// 9  数组的拼接 array_merge()




