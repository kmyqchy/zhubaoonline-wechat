<?php
define ( "TOKEN", "gemsu_weixin" ); // 与管理平台的TOKEN设置一致
define ( "DEBUG", 0 );

include_once 'StrAnalyse.php';
include_once 'tpl.php';

if (DEBUG) {
	$postStr = <<<XML
<xml>
<ToUserName><![CDATA[toUser]]></ToUserName>
<FromUserName><![CDATA[fromUser]]></FromUserName> 
<CreateTime>1348831860</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[12345679801]]></Content>
<MsgId>1234567890123456</MsgId>
</xml>
XML;
} else {
	$postStr = $GLOBALS ["HTTP_RAW_POST_DATA"];
	if (empty ( $postStr )) {
		echo 'wrong!';
		exit ( 0 );
	}
}

// 获取接收到信息的参数
$postObj = simplexml_load_string ( $postStr, 'SimpleXMLElement', LIBXML_NOCDATA );
$fromUsername = $postObj->FromUserName;
$toUsername = $postObj->ToUserName;
$keyword = trim ( $postObj->Content );
$msgtype = $postObj->MsgType;
$event = $postObj->Event;

// 判断是否为关注，并传送至str（StrAnalyse.php）函数处理
if (trim ( $msgtype ) == "event" and trim ( $event ) == "subscribe") // 判断是否是新关注
{
	$msgType = "text";
	$sql = mysql_query ( "SELECT * FROM user WHERE username='$fromUsername'" );
	$val = mysql_fetch_array ( $sql );
	if ($val == NULL) {
		$sql = mysql_query ( "INSERT INTO user (username) VALUES ('$fromUsername')" );
	}
	if ($sql)
		$contentStr = "您好，欢迎关注Flagplus自媒体实验室。您可以输入’help‘来获取帮助并查看我们所支持的功能。";
	else
		$contentStr = "您好，欢迎关注Flagplus自媒体实验室。您可以输入’help‘来获取帮助并查看我们所支持的功能。由于数据库错误，您的第一条命令可能会被系统无视。";
		// $resultStr = sprintf($textTpl, $fromUsername,$toUsername,time(), $msgType, $contentStr);
		// echo $resultStr;
	text ( $fromUsername, $toUsername, $contentStr );
} else {
	str ( $msgtype, $fromUsername, $toUsername, $keyword );
}

?>