<?php
    function index(){
        $timestamp=$_GET['timestamp'];
        $nonce=$_GET['nonce'];
        $token='weixin';
        $signature=$_GET['signature'];
        $echostr=$_GET['echostr'];
        $array=array($timestamp,$nonce,$token);
        sort($array);
        //echo $array;
        $tmpstr=implode('',$array);//join
        $tmpstr=sha1($tmpstr);

        if($tmpstr==$signature && $echostr){
            echo $echostr;
            exit;
        } else {
            recieveMsg();
        }
         //echo 'ok'; 
    }
    function sendtext($postObj,$a){
    	switch ($a){
            case '1': $Content='1111111111';
                    break;
      		case '2': $Content='2222222222';
               		break;
     		case '3': $Content='3333333333';
           			break;     
            case 0:$Content="请检查格式！";
                    break;
         }		 	
			$toUser   = $postObj->FromUserName;
			$fromUser = $postObj->ToUserName;
			$time     = time();
			$MsgType  = 'text';
			$template = "<xml> 
						<ToUserName><![CDATA[%s]]></ToUserName> 
						<FromUserName><![CDATA[%s]]></FromUserName> 
						<CreateTime>%s</CreateTime> 
						<MsgType><![CDATA[%s]]></MsgType> 
						<Content><![CDATA[%s]]></Content> 
						</xml>";
			$info =sprintf($template,$toUser,$fromUser,$time,$MsgType,$Content);
            if ($info) {echo $info;} else {echo 'failed';}
    }

    function sendnews($postObj,$outarr){
    		$toUser   = $postObj->FromUserName;
			$fromUser = $postObj->ToUserName;
			$time     = time();
        	$template = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName> 
						<FromUserName><![CDATA[%s]]></FromUserName> 
						<CreateTime>%s</CreateTime> 
                        <MsgType><![CDATA[news]]></MsgType>
                        <ArticleCount>1</ArticleCount>
                        <Articles>
                            <item>
                            <Title><![CDATA[%s]]></Title>
                            <Description><![CDATA[%s]]></Description>
                            <PicUrl><![CDATA[]]></PicUrl>
                            <Url><![CDATA[]]></Url>
                            </item>
                        </Articles>
                        </xml>";
        	$info =sprintf($template,$toUser,$fromUser,$time,$outarr[0],$outarr[1]);
            if ($info) {echo $info;} else {echo 'failed';}
    }
    function recieveMsg(){
    	$postArr=$GLOBALS['HTTP_RAW_POST_DATA'];//xml格式
       
        $postObj=simplexml_load_string($postArr);
		
        switch(strtolower($postObj->MsgType)){
            case 'event': if (strtolower($postObj->Event) == 'subscribe'){subscribereply($postObj);}
                          break;
            case 'text' : $keyword = $postObj->Content;
           				 $keyword=preg_replace("/\s(?=\s)/","\\1",$keyword);
           				 $arr = explode(" ",$keyword);
               			 if (count($arr) == 1) {
                                    sendtext($postObj,$keyword);
                 		  } else {
                             switch ($arr[0]){
                                 case "天气": if (count($arr)==2) {
                                     				sendnews($postObj,weather($arr[1]));
                                        		} else {sendtext($postObj,0);}
                                     		  break;
                         		  }
                        break;
        }	  
	}
    }
  	function weather($city,$extensions){
		$key='0ef16225f71a96ee4f531d306449fad7';
		$url="https://restapi.amap.com/v3/weather/weatherInfo?key=".$key."&city=".$city."&extensions=".$extensions;
		$ch=curl_init();
		curl_setopt($ch , CURLOPT_URL,$url);
		curl_setopt($ch , CURLOPT_RETURNTRANSFER,1);
		$res = curl_exec($ch);

		if (curl_errno($ch)){
			var_dump(curl_errno($ch));
		}
        curl_close($ch);
		//rr = json_decode($res,true);//把json转化为数组
		$data=json_decode($res,true);

        		 $province=$data['lives'][0]['province'];
                 $cityname=$data['lives'][0]['city'];
                 $citytemp=$data['lives'][0]['temperature'];
                 $citywea=$data['lives'][0]['weather'];
				 $citydate=date("m-d-Y",time());
				 $reporttime=$data['lives'][0]['reporttime'];
                 $cityhum=$data['lives'][0]['humidity'];				 
                 $cityfengli=$data['lives'][0]['windpower'];
                 $cityfengxiang=$data['lives'][0]['winddirection'];
           	 $out[0]=$province." ".$cityname." ".$citywea."\n\r".$citydate."\n\r";
                 $out[1]="当前温度：".$citytemp."  "."空气湿度：".$cityhum."\n\r"."风向风力：".$cityfengxiang." ".$cityfengli."  "."数据发布的时间:\n\r".$reporttime;
        return $out;
    }
    
    function subscribereply($postObj){
				$toUser   = $postObj->FromUserName;
				$fromUser = $postObj->ToUserName;
				$time     = time();
				$MsgType  ='text';
				$Content  ='【查询天气】输入格式:“天气”+空格+城市编码。（如：天气 110101）';
				$template="<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							</xml>";
				$info     =sprintf($template,$toUser,$fromUser,$time,$MsgType,$Content);			
			    echo $info;      
    }
	index();
?>