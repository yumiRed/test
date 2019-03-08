	  	function weather($city){
            $key='0ef16225f71a96ee4f531d306449fad7';
            $url="https://restapi.amap.com/v3/weather/weatherInfo?key=".$key."&city=".$city."&extensions=base";
            $ch=curl_init();
            curl_setopt($ch , CURLOPT_URL,$url);
            curl_setopt($ch , CURLOPT_RETURNTRANSFER,1);
            $res = curl_exec($ch);
            if (curl_errno($ch)){
                var_dump(curl_errno($ch));
            }
            curl_close($ch);
            $data=json_decode($res,true);//把json转化为数组

                 $province=$data['lives']['province'];
                 $cityname=$data['lives']['city'];
                 $citytemp=$data['lives']['temperature'];
                 $citywea=$data['lives']['weather'];
				 $citydate=date("Y-m-d",time());
				 $reporttime=$data['lives']['reporttime'];
                 $cityhum=$data['lives']['humidity'];				 
                 $cityfengli=$data['lives']['windpower'];
                 $cityfengxiang=$data['lives']['winddirection'];
				 
            	 $out=$province." ".$cityname."\n\r".$citydate."\n\r".$citywea."\n\r";
                 $out.="当前温度：".$citytemp."\n\r"."空气湿度：".$cityhum."\n\r"."风向风力：".$cityfengxiang." ".$cityfengli."\n\r"."数据发布的时间".$reporttime."\n\r";
                 

            $key='0ef16225f71a96ee4f531d306449fad7';
            $url="https://restapi.amap.com/v3/weather/weatherInfo?key=".$key."&city=".$city."&extensions=all";
            $ch=curl_init();
            curl_setopt($ch , CURLOPT_URL,$url);
            curl_setopt($ch , CURLOPT_RETURNTRANSFER,1);
            $res = curl_exec($ch);

            if (curl_errno($ch)){
                var_dump(curl_errno($ch));
            }
            curl_close($ch);
            	$data1=json_decode($res,true);//把json转化为数组				 
            	$cityforecast=$data1['forecast']['casts'];
                for($i=0;$i<count($cityforecast);$i++){
                     $out.="☀ ".$cityforecast[$i]["date"]." ".$cityforecast[$i]["week"]." ".$cityforecast[$i]["week"]."\n\r【白天】   天气气温：".$cityforecast[$i]["dayweather"]."  ".$cityforecast[$i]["daytemp"]."\n\r       风力风向：".$cityforecast[$i]["daywind"]." ".$cityforecast[$i]["daypower"]."\n\r";
					 $out.="\n\r【夜晚】   天气气温：".$cityforecast[$i]["nightweather"]."  ".$cityforecast[$i]["nighttemp"]."\n\r       风力风向：".$cityforecast[$i]["nightwind"]." ".$cityforecast[$i]["nightpower"]."\n\r";
                 }
            return $out;
	}