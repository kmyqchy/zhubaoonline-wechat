<?php
include_once 'tpl.php';

// $contentStr = $EventKey;
// text ( $fromUsername, $toUsername, $contentStr );
switch ($EventKey) {
	
	case mtbm : // 模特大赛 or 设计大赛报名
		include_once 'conn.php';
		// include_once 'mtbm.php';
		$sql = mysql_query ( "UPDATE ceshi SET status='2' WHERE username='$fromUsername'" ); // sql_update ( ceshi, status, 1 );
		$sql_mtbm = mysql_query ( "SELECT * FROM mtbm WHERE username='$fromUsername'" );
		$mtbm = mysql_fetch_array ( $sql_mtbm );
		if($mtbm == null) $please = "请输入姓名：";
		else $please = " ";
		if ($sql) {
			$contentStr = "进入模特报名模式！请根据提示进行报名操作。（由于未搞定原因 没准是微信的原因呢
			o(>﹏<)o，
			可能出现无回复情况，请重新输入，
☆⌒(*＾-゜)v THX!!）
【注意：如有填写错误请先完成全部后修改，请尽量避免重新填写。】
$please"; // mtbm ( '', $fromUsername, $msgtype );
			text ( $fromUsername, $toUsername, $contentStr ); // 把录入数据库下载这 完全
		}
		$sql = mysql_query ( "SELECT * FROM mtbm WHERE username='$fromUsername'" );
		$val = mysql_fetch_array ( $sql );
		if ($val == NULL) {
			$sql = mysql_query ( "INSERT INTO mtbm (username) VALUES ('$fromUsername')" );
		}
		break;
	/*
	 * case news : // 最近照片
	 *
	 * break;
	 * case photo : // 图库
	 * break;
	 * case introduction : // 学生会介绍
	 * $contentStr = "介绍";
	 * // $content[] = array("Title"=>"方倍工作室", "Description"=>"", "PicUrl"=>"http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", "Url" =>"http://m.cnblogs.com/?u=txw1958");
	 * text ( $fromUsername, $toUsername, $contentStr );
	 * break;
	 * case wtbm : // 微淘报名
	 * break;
	 * case sjbm : // 模特大赛 or 设计大赛报名
	 * // include_once 'conn.php';
	 * // $sql=mysql_query("UPDATE ceshi SET status='2' WHERE username='$fromUsername'");
	 * $contentStr = "进入报名模式，退出请输入【:quit】，进行设计大赛报名请输入【sjbm】。";
	 * text ( $fromUsername, $toUsername, $contentStr );
	 * break;
	 * case mtds : // 模特大赛抢票
	 * break;
	 * case pmhbm : // 拍卖会报名
	 * break;
	 */
	default :
		$contentStr = "点击菜单：" . $EventKey;
		text ( $fromUsername, $toUsername, $contentStr );
		break;
}

/*
 * header("Content-type: text/html; charset=utf-8");
 * define("ACCESS_TOKEN", "VX3DCHOm4N6NAUtt4CsA9I6yVmBywjUjNJQgS2K5OLB-E32u_TCdZxpRS1Z6rdl_UHOnmNDsggAmHaIzYAfolxYSBEltYe4QMs7YFWjs7MU");
 *
 *
 * //创建菜单
 * function createMenu($data){
 * $ch = curl_init();
 * curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".ACCESS_TOKEN);
 * curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
 * curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
 * curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
 * curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
 * curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
 * curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
 * curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
 * curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 * $tmpInfo = curl_exec($ch);
 * if (curl_errno($ch)) {
 * return curl_error($ch);
 * }
 *
 * curl_close($ch);
 * return $tmpInfo;
 *
 * }
 *
 * //获取菜单
 * function getMenu(){
 * return file_get_contents("https://api.weixin.qq.com/cgi-bin/menu/get?access_token=".ACCESS_TOKEN);
 * }
 *
 * //删除菜单
 * function deleteMenu(){
 * return file_get_contents("https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".ACCESS_TOKEN);
 * }
 *
 *
 *
 *
 *
 * $data = '{
 * "button":[
 * {
 * "type":"click",
 * "name":"首页",
 * "key":"home"
 * },
 * {
 * "type":"click",
 * "name":"简介",
 * "key":"introduct"
 * },
 * {
 * "name":"菜单",
 * "sub_button":[
 * {
 * "type":"click",
 * "name":"hello word",
 * "key":"V1001_HELLO_WORLD"
 * },
 * {
 * "type":"click",
 * "name":"赞一下我们",
 * "key":"V1001_GOOD"
 * }]
 * }]
 * }';
 *
 *
 *
 *
 * echo createMenu($data);
 * //echo getMenu();
 * //echo deleteMenu();
 */
?>