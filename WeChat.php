<?php
define ( "TOKEN", "12313" );
// traceHttp();
$wechatObj = new wechatCallbackapiTest ();
$wechatObj->valid ();

include_once 'StrAnalyse.php';
include_once 'tpl.php';
// include_once 'menu.php';
class wechatCallbackapiTest {
	public function valid() {
		$echoStr = $_GET ["echostr"];
		if ($this->checkSignature ()) {
			echo $echoStr;
			exit ();
		}
	}
	private function checkSignature() {
		$signature = $_GET ["signature"];
		$timestamp = $_GET ["timestamp"];
		$noce = $_GET ["nonce"];
		$token = TOKEN;
		$tmpArr = array (
				$token,
				$timestamp,
				$noce 
		);
		sort ( $tmpArr );
		if (sha1 ( implode ( $tmpArr ) ) == $signature) {
			return true;
		} else {
			return false;
		}
	}
}
function traceHttp() {
	logger ( "REMOTE_ADDR:" . $_SERVER ["REMOTE_ADDR"] . ((strpos ( $_SERVER ["REMOTE_ADDR"], "101.226" )) ? " From Weixin" : " Unknow IF") );
	logger ( "QUERY_SRING:" . $_SERVER ["QUERY_STRING"] );
}
function logger($content) {
	file_put_contents ( "log.html", date ( 'Y-m-d H:i:s ' ) . $content . "<br>", FILE_APPEND );
}

$postStr = $GLOBALS ["HTTP_RAW_POST_DATA"];

// extract post data
if (! empty ( $postStr )) {
	
	$postObj = simplexml_load_string ( $postStr, 'SimpleXMLElement', LIBXML_NOCDATA );
	$fromUsername = $postObj->FromUserName;
	$toUsername = $postObj->ToUserName;
	$keyword = trim ( $postObj->Content );
	$msgtype = $postObj->MsgType;
	$event = $postObj->Event;
	$EventKey = trim ( $postObj->EventKey );
	$picurl = $postObj->PicUrl;
	$time = time ();
	$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Event><![CDATA[%s]]></Event>
							<EventKey><![CDATA[%s]]></EventKey>
							<Content><![CDATA[%s]]></Content>
							<PicUrl><![CDATA[%s]]></PicUrl>
							<FuncFlag>0</FuncFlag>
							</xml>";
	// if(!empty( $keyword ))
	
	if (trim ( $msgtype ) == "event" and trim ( $event ) == "subscribe") {
		$msgType = "text";
		include 'conn.php';
		$sql = mysql_query ( "SELECT * FROM  ceshi WHERE username='$fromUsername'" );
		$val = mysql_fetch_array ( $sql );
		if ($val == NULL) {
			$sql = mysql_query ( "INSERT INTO ceshi (`username`)VALUES ('$fromUsername')" );
			// "INSERT INTO ceshi (username) VALUES ('$fromUsername')" );
		}
		if ($sql)
			$contentStr = "您好，欢迎关注珠宝手机报。您可以输入’help‘来获取帮助并查看我们所支持的功能。";
		else
			$contentStr = "您好，欢迎关注珠宝手机报。您可以输入’help‘来获取帮助并查看我们所支持的功能。由于数据库错误，您的第一条命令可能会被系统无视。";
			// $resultStr = sprintf($textTpl, $fromUsername,$toUsername,time(), $msgType, $contentStr);
			// echo $resultStr;
		text ( $fromUsername, $toUsername, $contentStr );
	} elseif (trim ( $msgtype ) == "event" and trim ( $event ) == "CLICK") {
		logger("click:".$EventKey);
		include 'menu.php';		
	} else {
		// $msgType = "text";
		// $contentStr = "Welcome to wechat world!";
		logger ( "keyword:" . $keyword );
		if($picurl)
		{
			$keyword ='';
			$resultStr = str ( $msgtype, $fromUsername, $toUsername, $keyword, $picurl ); // sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
			echo $resultStr;
		}
		else
		{
			$picurl = '';
			$resultStr = str ( $msgtype, $fromUsername, $toUsername, $keyword, $picurl ); // sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
			echo $resultStr;
		}
	}
	/*
	 * else{
	 * echo "Input something...";
	 * }
	 */
} else {
	echo "";
	exit ();
}
?>

