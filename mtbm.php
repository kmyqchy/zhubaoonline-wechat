<?php
include_once 'show.php';
function mtbm($str, $fromUsername, $msgtype,$picurl) {
	$txt = '';
	$sql = mysql_query ( "SELECT * FROM mtbm WHERE username='$fromUsername'" );
	$mtbm = mysql_fetch_array ( $sql );
	if ($mtbm == NULL) {
		$sql = mysql_query ( "INSERT INTO mtbm (username) VALUES ('$fromUsername')" );
		$txt = "如果您看到这句话，请重新输入姓名:";
//请输入姓名：';
	} elseif ($mtbm ['name'] == null) {
		$sql = mysql_query ( "UPDATE mtbm SET name='$str' WHERE username='$fromUsername'" );
		if ($sql)
			$txt = '姓名写入成功，请输入您的性别：';
		else
			$txt = '姓名提交失败，请重试！';
	} elseif ($mtbm ['sex'] == null) {
		$sql = mysql_query ( "UPDATE mtbm SET sex='$str' WHERE username='$fromUsername'" );
		if ($sql)
			$txt = '性别写入成功，请输入您的年龄：';
		else
			$txt = '性别提交失败，请重试！';
	} elseif ($mtbm ['age'] == null) {
		$sql = mysql_query ( "UPDATE mtbm SET age='$str' WHERE username='$fromUsername'" );
		if ($sql)
			$txt = '年龄写入成功，请输入您的学校：';
		else
			$txt = '年龄提交失败，请重试！';
	} elseif ($mtbm ['school'] == null) {
		$sql = mysql_query ( "UPDATE mtbm SET school='$str' WHERE username='$fromUsername'" );
		if ($sql)
			$txt = '学校写入成功，请输入您的电话：';
		else
			$txt = '学校提交失败，请重试！';
	} elseif ($mtbm ['phone'] == null) {
		$sql = mysql_query ( "UPDATE mtbm SET phone='$str' WHERE username='$fromUsername'" );
		if ($sql)
			$txt = '电话写入成功，请输入您的Email：';
		else
			$txt = '电话提交失败，请重试！';
	} elseif ($mtbm ['email'] == null) {
		$sql = mysql_query ( "UPDATE mtbm SET email='$str' WHERE username='$fromUsername'" );
		if ($sql)
			$txt = 'Email写入成功，请输入您的自我介绍：（不需要填写请回复”无“）';
		else
			$txt = 'Email提交失败，请重试！';
	} elseif ($mtbm ['introduction'] == null) {
		$sql = mysql_query ( "UPDATE mtbm SET introduction='$str' WHERE username='$fromUsername'" );
		if ($sql)
			$txt = '自我介绍写入成功,请上传您的照片：';
		else
			$txt = '自我介绍提交失败，请重试！';
	} elseif ($mtbm ['photo'] == null) {
		$sql = mysql_query ( "UPDATE mtbm SET photo='$picurl' WHERE username='$fromUsername'" );
		if ($sql)
		{
			//$txt = '图片写入成功，';
			$sql = mysql_query ( "UPDATE ceshi SET status='0' WHERE username='$fromUsername'" );
			$txt = showmt($fromUsername);
		}
		else
			$txt = '图片提交失败，请重试！';
	} else {
		$sql = mysql_query ( "UPDATE ceshi SET status='0' WHERE username='$fromUsername'" );		
		//$txt = '报名已经完成！';
		$txt = showmt($fromUsername);

	}
	return $txt;
}
?>