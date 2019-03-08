<?php     
	function responseMsg(){
    	$postArr=$GLOBALS['HTTP_RAW_POST_DATA'];//xml格式
        
		$postObj=simplexml_load_string( $postArr );
			
		if (strtolower($postObj->MsgType) == 'event'){
			if (strtolower($postObj->Event) == 'subscribe'){
				$toUser   = $postObj->FromUserName;
				$fromUser = $postObj->toUserName;
				$time     = time();
				$MsgType  = 'text';
				$Content  = '欢迎关注我们的微信公众号';
				$template = "<xml>
							<ToUserName>< ![CDATA[%s] ]></ToUserName>
							<FromUserName>< ![CDATA[%s] ]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType>< ![CDATA[%s] ]></MsgType>
							<Event>< ![CDATA[%s] ]></Event>
							</xml>";
				$info     = sprintf($template,$toUser,$fromUser,$time,$MsgType,$Content);			
			    echo $info;
			}
		}
		if (strtolower($postObj->MsgType) == 'text'){
		  if (strtolower($postObj->Content) == 'h'){
			$toUser   = $postObj->FromUserName;
			$fromUser = $postObj->toUserName;
			$time     = time();
			$MsgType  = 'text';
			$template = "<xml> 
						<ToUserName>< ![CDATA[%s] ]></ToUserName> 
						<FromUserName>< ![CDATA[%s] ]></FromUserName> 
						<CreateTime>%s</CreateTime> 
						<MsgType>< ![CDATA[%s] ]></MsgType> 
						<Content>< ![CDATA[你好] ]></Content> 
						</xml>";
			$info     = sprintf($template,$toUser,$fromUser,$time,$MsgType);
			echo $info;
		  }
		}
		

	}
?>