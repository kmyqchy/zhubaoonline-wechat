<?php
//文字信息
function text($fromUsername,$toUsername,$contentStr){
$textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[%s]]></MsgType>
<Content><![CDATA[%s]]></Content>
<FuncFlag>0</FuncFlag>
</xml>";
	$msgType = "text";
	
	logger("content:".$contentStr);
	//$contentStr = "纯文字消息";
	//$resultStr = sprintf($picTpl, $fromUsername, $toUsername, $time, $msgType, $title,$desription,$image,$turl);
	$resultStr = sprintf($textTpl,$fromUsername,$toUsername,time(),$msgType,$contentStr);
	echo $resultStr;
}

//图文信息
function news($fromUsername,$toUsername,$title,$desription,$image,$turl){
$picTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[%s]]></MsgType>
<ArticleCount>1</ArticleCount>
<Articles>
<item>
<Title><![CDATA[%s]]></Title>
<Description><![CDATA[%s]]></Description>
<PicUrl><![CDATA[%s]]></PicUrl>
<Url><![CDATA[%s]]></Url>
</item>
</Articles>
<FuncFlag>1</FuncFlag>
</xml> ";
	$msgType = "news";
	//$title = "XXXX公司";
	//$desription = "文字介绍内容";
	//$image = "http://flagplus.duapp.com/img/flash_18.jpg";
	//$turl = "http://flagplus.duapp.com/";
    $resultStr = sprintf($picTpl, $fromUsername, $toUsername, time(), $msgType, $title,$desription,$image,$turl);
    echo $resultStr;
}

//音乐消息
function music($fromUsername,$toUsername,$title,$desription,$musicurl,$hqmusicurl){
$musicTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[%s]]></MsgType>
<Music>
<Title><![CDATA[%s]]></Title>
<Description><![CDATA[%s]]></Description>
<MusicUrl><![CDATA[%s]]></MusicUrl>
<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
</Music>
<FuncFlag>1</FuncFlag>
</xml> ";
	$msgType = "music";
	//$title = "爱久见人心";
	//$desription = "Bonjour_梁静茹_爱久见人心";
	//$musicurl = "http://42.96.142.129/1.mp3";
	//$hqmusicurl = "http://42.96.142.129/1.mp3";
	$resultStr = sprintf($musicTpl, $fromUsername, $toUsername,time(), $msgType, $title,$desription,$musicurl,$hqmusicurl);
    echo $resultStr;
}


?>