<?php
function showmt($fromUsername){
	$sql=mysql_query("SELECT * FROM mtbm WHERE username='$fromUsername'");
	$val=mysql_fetch_array($sql);
	$txt='您所填写的信息：
	姓名：'.$val['name'].'
	性别：'.$val['sex'].'
	年龄：'.$val['age'].'
	学校：'.$val['school'].'
	电话：'.$val['phone'].'
	Email：'.$val['email'].'
	自我介绍：'.$val['introduction'].'
	图片：已上传。
	报名完成，已返回常规模式。'
	;
	return $txt;
}
?>