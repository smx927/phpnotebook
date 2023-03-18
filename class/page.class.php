<?php

 

    class page{

        private $total;    //数据表中总记录数

        private $listRows; //每页显示行数

        private $limit;    //限制条数

        private $uri;     //当前页的URL

        private $pageNum; //一共多少页

        private $page;    //当前页

        private $config =array('header'=>"个","prev"=>"‹","next"=>"›","first"=>"‹‹","last"=>"››");//显示配置

        private $listNum=2; //同时显示页数最多为5页

 

        

        /*

        *$total

        *$listRows

        *$pa 预留用户自己加参

        */

        public function __construct($total,$listRows=10,$pa=""){

            $this->total=$total;        //获取总数

            $this->listRows=$listRows;  //获取单页显示数

            $this->uri=$this->getUri($pa); //获取过滤掉page后的url

            $this->page=!empty($_GET["page"])?intval($_GET["page"]):1; //获取当前页，没有默认1

            $this->pageNum=ceil($total/$listRows); //页数

            $this->limit=$this->setLimit();   //获取limit

            

            //var_dump($this);

        }

        

        //设置计算拼凑limit

        private function setLimit(){

            return "limit ".($this->page-1)*$this->listRows." ,". $this->listRows;

        }

 

        //获取当前页的url,处理掉url里面的page参数

        private function getUri($pa){

            //判断这个url里有没有？号，然后好添加数据

            $url = $_SERVER['REQUEST_URI'].(strpos($_SERVER["REQUEST_URI"],'?')?'':"?").$pa;

            

            //得到url的数组 Array ( [path] => /pageclass/test.php [query] => page=1 ) 

            $parse =parse_url($url);

            

 

            //判断是否有参数,干掉url中的page参数

            if(isset($parse['query'])){

                //把字符串以&拆分成数组

                parse_str($parse['query'],$params); 

                //unset掉数组中的page单元，不管有没有

                unset($params["page"]);

                //再把数组中的键值以&连接起来成字符串

                http_build_query($params);

                //拼接URL

                $url = $parse['path'].'?'.http_build_query($params);

            }

 

            

            return $url;

        }

   

        //用它来调用私有变量

        function __get($args){ 

            if($args=='limit')

                return $this->limit;

            else

                return null;

        }

        

        /********************处理显示样式*********************/

    

        //每页开始显示的第一条页码

        private function start(){

            if($this->total == 0)

                    return 0;

            else if($this->page<=$this->pageNum)

                    return ($this->page-1)*$this->listRows+1;

        }

        

        //每页开始显示的最后一条页码。主要考虑最后一页

        private function ends(){

            if($this->page<=$this->pageNum){

                return min($this->page*$this->listRows,$this->total);

            }

        }

 

        //每页显示多少条

        private function perNum(){

            if($this->page<=$this->pageNum){

                return $this->ends() - $this->start() +1;

            }

        }

 

        //*******页码显示系列

        //首页

        private function first(){
            $html='';
            if($this->page == 1){

                

            }else{

                $html="<a href=".$this->uri."&page=1>".$this->config['first']."</a>";

            }

 

            return $html;

        }

        

        //前一页

        private function preve(){

           if($this->page == 1){

                $html='';

            }else{

                $html="<a href=".$this->uri."&page=".($this->page-1).">".$this->config['prev']."</a>";

            }

 

            return $html;    

        }

        

        //****页码列表****

        private function pageList(){

            $linkPage="";

            

            $mid=floor($this->listNum/2);

 

            for($i=$mid;$i>=1;$i--){ //从当前页起往前显示

                $page=$this->page-$i;

 

                if($page<1){

                    continue;

                }

                $linkPage.="<a href=".$this->uri.'&page='.$page.">".$page."</a>";

            }

 

           // $linkPage.=" ".$this->page." "; //当前页



           



            if($this->pageNum>1){



              $linkPage.="<a class='on'   href=".$this->uri.'&page='.$this->page.">".$this->page."</a>";   

            } 

            

 

            for($i=1;$i<=$mid;$i++){

                $page=$this->page + $i; //从当前页起向后显示

                if($page<=$this->pageNum){

                    $linkPage.="<a href=".$this->uri.'&page='.$page.">".$page."</a>";

                }else{

                    break;

                }

            }

 

        

            return $linkPage;

        }

    

        //下一页

        private function nexts(){

           if($this->page >= $this->pageNum){

                $html='';

            }else{

                $html="<a href=".$this->uri."&page=".($this->page+1).">".$this->config['next']."</a>";

            }

 

            return $html;     

        }

        

        //最后一页

        private function last(){

           if($this->page >= $this->pageNum){

                $html='';

            }else{

                $html="<a href=".$this->uri."&page=".($this->pageNum).">".$this->config['last']."</a>";

            }

 

            return $html;  

        }

        

        //**直达那一页**

        private function goPage(){

            return '<input type="text" οnkeydοwn="javascript:if(event.keyCode==13){var page=(this.value>'.$this->pageNum.')?'.$this->pageNum.':this.value;location=\''.$this->uri.'&page=\'+page+\'\'}" value="'.$this->page.'" style="width:25px"><input type="button" value="GO" οnclick="javascript:var page=(this.previousSibling.value>'.$this->pageNum.')?'.$this->pageNum.':this.previousSibling.value;location=\''.$this->uri.'&page=\'+page+\'\'">  ';

		  

        }

 

        //输出样式处理

        function fpage($display=array(0,1,2,3,4,5,6,7,8)){        

            $html[0]="共<b>{$this->total}</b>{$this->config['header']} ";

            $html[1]="本页显示<b>{$this->perNum()}</b>条，本页<b>{$this->start()}-{$this->ends()}</b>";

            $html[2]="<b>"."$this->page/$this->pageNum"."</b> ";

            

            $html[3]=$this->first();

            $html[4]=$this->preve();

            $html[5]=$this->pageList();

            $html[6]=$this->nexts();

            $html[7]=$this->last();

            $html[8]=$this->goPage();

 

            $fpage='';

            foreach($display as $index){

                $fpage.=$html[$index];

            }

 

            return $fpage;

        }


         function pagearr($setpages=8){ 

        $num = $this->total;
        $curr_page = $this->page;
        $perpage = $this->listRows;

        $multipage = array
        (
             'page_first'  => $this->uri."&page=1", 
             'page_prev'  =>'', 
             'page_next'  =>'',  
             'page_last'  =>'',  
             'page_number' =>array(),  
             'page'  => $curr_page, 
             'page_count'  => ceil($num/$perpage),
             'count'  => $num,
             'setpages'  => $setpages,
        );
 


       //每页显示数量
      if($num > $perpage) {
        
        
        $page = $setpages+1;
        $offset = ceil($setpages/2-1);
        $pages = ceil($num / $perpage);
        
        $from = $curr_page - $offset;
        $to = $curr_page + $offset;
        $more = 0;
        if($page >= $pages) {
            $from = 2;
            $to = $pages-1;
        } else {
            if($from <= 1) {
                $to = $page-1;
                $from = 2;
            }  elseif($to >= $pages) {
                $from = $pages-($page-2);
                $to = $pages-1;
            }
            $more = 1;
        }
        
      
        if($curr_page>0) {
      
            $multipage['page_prev'] = $this->uri."&page=".($this->page-1); 
            
            if($curr_page==1) {
               
                $multipage['page_number'][1] = $this->uri."&page=1"; 
            } elseif($curr_page>6 && $more) {
                
                $multipage['page_number'][1] = $this->uri."&page=1"; 
            } else {
                
                $multipage['page_number'][1] = $this->uri."&page=1";  
            }
            
            
        }
        
        // echo $from."##".$to; exit;
        
        
        for($i = $from; $i <= $to; $i++) {
            
           
                $multipage['page_number'][$i] = $this->uri."&page=".$i;   
            
        }


        if($curr_page<$pages) {
          
            
            $multipage['page_number'][$pages] =  $this->uri."&page=".$pages;  
            $multipage['page_last'] = $this->uri."&page=".$pages;
            $multipage['page_next'] = $this->uri."&page=".($curr_page+1); 
            
        } elseif($curr_page==$pages) {
            //$multipage .= ' <a class="ok" >'.$pages.'</a> <a href="'.pageurl($urlrule, $curr_page, $array).'" class="a1">&gt;</a>';
            $multipage['page_number'][$pages] =  $this->uri."&page=".$pages;  
            $multipage['page_last'] =   $this->uri."&page=".$pages; 
            $multipage['page_next'] =  $this->uri."&page=".$curr_page; 
            
        } else {
            //$multipage .= ' <a href="'.pageurl($urlrule, $pages, $array).'">'.$pages.'</a> <a href="'.pageurl($urlrule, $curr_page+1, $array).'" class="a1">&gt;</a>';
           $multipage['page_last'] =  $this->uri."&page=".$pages; 
           $multipage['page_next'] =  $this->uri."&page=".$pages; 
        }
    }  
 
     return $multipage;

    }

     

    }

 

 

 