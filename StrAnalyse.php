<meta charset="utf-8">
<?php
include 'conn.php';
include 'tpl.php';
function str($msgtype, $fromUsername, $toUsername, $keyword, $picurl) // 传入函数
{
	// 新用户首次使用时在数据库中注册
	// select username，若不存在则创建
	$sql =  mysql_query("SELECT * FROM ceshi WHERE username='$fromUsername'");
	$val = mysql_fetch_array ( $sql );
	if ($val == NULL) {
		$sql =  mysql_query("INSERT INTO ceshi (`username`)VALUES ('$fromUsername')");
		if ($sql)
			$contentStr = '如果您看到这一条语句，说明，您刚说的那句话被系统无视了。不过，系统已经恢复正常了~您可以正常使用';
		else
			$contentStr = '如果您每次都看到这句话，这表示，系统已经出现了严重问题。。。我们希望您可以立即联系管理员（email：zjqzxc@flagplus.net),并提供错误代码：mysql_error_01';
		text ( $fromUsername, $toUsername, $contentStr );
	} else {
		$id = $val ['id'];
		$status = $val ['status'];
		$lastfunctime = $val ['lastfunctime'];
		$time = time ();
	}
	
	if ($keyword == '：quit' or $keyword ==':quit' or $keyword =='【：quit】' or $keyword =='【:quit】') { // 强制终止操作
		$sql = mysql_query("UPDATE ceshi SET status='0' WHERE username='$fromUsername'");
		$contentStr = '终止当前操作，返回常规模式';
		text ( $fromUsername, $toUsername, $contentStr );
		exit ( 0 );
	}
	
	switch ($status) // 模式选择
{
		case 0 :
			
			// echo '常规模式';
			$contentStr = normal ( $msgtype, $fromUsername, $toUsername, $keyword );
			break;
		case 1 :
			include_once 'xsh.php';
			$contentStr = xsh ( $keyword, $fromUsername, $msgtype );
			$sql = mysql_query ( "UPDATE ceshi SET lastfunctime='$time' WHERE id='$id'" );
			text ( $fromUsername, $toUsername, $contentStr );
			break;
		case 2 :
			include_once 'mtbm.php';
			$contentStr = mtbm ( $keyword, $fromUsername, $msgtype, $picurl );
			$sql = mysql_query ( "UPDATE ceshi SET lastfunctime='$time' WHERE id='$id'" );
			text ( $fromUsername, $toUsername, $contentStr );
			break;
	}
	return $contentStr;
}
function normal($msgtype, $fromUsername, $toUsername, $keyword) {
	if ($msgtype == "text") {
		$str = $keyword;
		if ($str == 'help') {
			$helpstr = ' 帮助
这里是珠宝手机报。我们提供如下功能：（命令字符只能识别英文半角）
1、命令行模式，提供一些指令以实现常用功能（请输入\':\'+命令内容，例如输入\':help\'(不包含单引号)可以获得该模式的帮助）；
2、留言模式，您可以给平台管理员留言而不会得到平台机器人的自动回复（请输入\'!\'+留言内容，例如输入\'!我要留言\'即可给平台管理员留言“我要留言”）；
3、聊天模式，您可以和平台的自动回复系统进行对话（什么也不用加，尽管说话吧。暂未开通）；
4、在任何模式下，输入‘:q!’命令即可返回至基础模式;输入‘:status’可以获取当前状态。无论处于任何模式，若10分钟无任何操作，将退出至基础模式；
5、查看正在开发中的功能，请输入’developing‘。
	';
			$contentStr = $helpstr;
		} elseif ($str == 'developing') {
			$contentStr = "以下功能尚未完善，仅供试用
1、学生会招新在线报名：
输入’:xshbm‘进入交互式报名模式；
输入’:xshbm show‘查看已填写的报名信息
";
		} else {
			$flag = substr ( $str, 0, 1 ); // 切取第一个字符，用于识别
			if ($flag == ':') {
				include 'command.php';
				$cmd = substr ( $str, 1 );
				$contentStr = command ( $cmd, $fromUsername, $toUsername, $msgtype );
			} else if ($flag == '!') // $contentStr='留言模式';
{
				include 'file.php';
				$str = substr ( $str, 1 );
				$contentStr = echosrt ( $str, $fromUsername );
			} else {
				// include 'chat.php';
				// $contentStr= chat($str,$fromUsername);
				$contentStr = '该命令在基础模式下无法识别，请help';
			}
		}
		// if(DEBUG2) echo $contentStr; else
		text ( $fromUsername, $toUsername, $contentStr );
		/*
		 * if($keyword=="news"){
		 * $title = "XXXX公司";
		 * $desription = "文字介绍内容";
		 * $image = "http://flagplus.duapp.com/img/flash_18.jpg";
		 * $turl = "http://flagplus.duapp.com/";
		 * news($fromUsername, $toUsername, $title, $desription, $image, $turl);
		 * }elseif($keyword=="music"){
		 * $title = "爱久见人心";
		 * $desription = "Bonjour_梁静茹_爱久见人心";
		 * $musicurl = "http://42.96.142.129/1.mp3";
		 * $hqmusicurl = "http://42.96.142.129/1.mp3";
		 * music($fromUsername, $toUsername, $title, $desription, $musicurl, $hqmusicurl);
		 * }else{
		 * text($fromUsername,$toUsername,$keyword);
		 * }
		 */
	} else {
		if ($msgtype != 'event') // event事件触发机理不明
{
			$s = '暂不支持类型为' . $msgtype . '的消息！';
			// if(DEBUG2) echo $contentStr; else
			text ( $fromUsername, $toUsername, $s );
		}
	}
}

// if(DEBUG){
// $t=$_REQUEST["t"];
// echo str('text', '2', '2',$t);
// }
?>