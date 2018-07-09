<?php
function xsh($str,$fromUsername,$msgtype)
{
	$txt='';
	$sql=mysql_query("SELECT * FROM xsh WHERE username='$fromUsername'");
	$xsh=mysql_fetch_array($sql);
	if($xsh==NULL){
		$sql=mysql_query("INSERT INTO xsh (username) VALUES ('$fromUsername')");
		$txt='欢迎使用微信报名系统！请根据提示进行报名操作。
请输入学号：';
	}elseif($xsh['schoolid']==null){
		$sql=mysql_query("UPDATE xsh SET schoolid='$str' WHERE username='$fromUsername'");
		if($sql) $txt='学号写入成功，请输入您的姓名：';
		else $txt='学号提交失败，请重试！';		
	}elseif($xsh['name']==null){
		$sql=mysql_query("UPDATE xsh SET name='$str' WHERE username='$fromUsername'");
		if($sql) $txt='姓名写入成功，请输入您的性别：';
		else $txt='姓名提交失败，请重试！';	
	}elseif($xsh['sex']==null){
		$sql=mysql_query("UPDATE xsh SET sex='$str' WHERE username='$fromUsername'");
		if($sql) $txt='性别写入成功，请输入您的意向部门：';
		else $txt='性别提交失败，请重试！';	
	}elseif($xsh['department']==null){
		$sql=mysql_query("UPDATE xsh SET department='$str' WHERE username='$fromUsername'");
		if($sql) $txt='意向部门写入成功，请输入您的电话：';
		else $txt='意向部门提交失败，请重试！';	
	}elseif($xsh['phone']==null){
		$sql=mysql_query("UPDATE xsh SET phone='$str' WHERE username='$fromUsername'");
		if($sql) $txt='电话写入成功，请输入您的Email：';
		else $txt='电话提交失败，请重试！';	
	}elseif($xsh['email']==null){
		$sql=mysql_query("UPDATE xsh SET email='$str' WHERE username='$fromUsername'");
		if($sql) $txt='Email写入成功，请输入您的自我介绍：';
		else $txt='Email提交失败，请重试！';	
	}elseif($xsh['selfintroduction']==null){
		$sql=mysql_query("UPDATE xsh SET selfintroduction='$str' WHERE username='$fromUsername'");
		if($sql) $txt='自我介绍写入成功，您可以继续填写备注.不需要填写请回复”无“';
		else $txt='自我介绍提交失败，请重试！';	
	}elseif($xsh['ramark']==null){
		$sql=mysql_query("UPDATE xsh SET ramark='$str' WHERE username='$fromUsername'");
		if($sql) $txt='备注写入成功，您可以上传您的照片（暂未开通）';
		else $txt='自我介绍提交失败，请重试！';	
	}else{
		$sql=mysql_query("UPDATE user SET status='0' WHERE username='$fromUsername'");
		if($sql)$txt='报名已经完成！';
		else $txt='异常退出';
	}
	return $txt;
}
?>