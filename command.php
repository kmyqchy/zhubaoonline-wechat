<?php
define("appID", "wx39d279a2a6d54fe2");
define("appsecret", "052ebf2d4c385a3c0c44a8fb25199609");

include_once '../conn_test.php';
//include_once '../conn.php';
include_once 'functon.php';


function get_access_token(){	
	return $json= curl_https('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.appID.'&secret='.appsecret);
}

function access_token(){
	$sql=mysql_query("SELECT * FROM config WHERE item='access_token'");
	$val=mysql_fetch_array($sql);
	if($val) {
		$de_json=json_decode($val[1],TRUE);
		if((time()-$de_json['time'])>($de_json['expires_in']/2)){ //时间间隔超过access token有效期的一半
			$json=get_access_token();
			$de_json=json_decode($json,TRUE);
			$access_token=$de_json['access_token'];
			$expires_in=$de_json['expires_in'];
			$time=time();
			$access_token_arr=array("access_token"=>"$access_token","expires_in"=>"$expires_in","time"=>"$time");
			$access_token_json=json_encode($access_token_arr);
			$sql=mysql_query("UPDATE config SET value='$access_token_json' WHERE item='access_token'");
			//if ($sql) echo 'ok';else die('Error: ' . mysql_error());
			return $access_token=$access_token;
			
		}else{
			//echo '没超';
			return $access_token=$de_json['access_token'];
		}
		//print_r($de_json);
	}else{//若access_token配置不存在，则创建
		$json=get_access_token();
		$de_json=json_decode($json,TRUE);
		$access_token=$de_json['access_token'];
		$expires_in=$de_json['expires_in'];
		$time=time();
		$access_token_arr=array("access_token"=>"$access_token","expires_in"=>"$expires_in","time"=>"$time");
		$access_token_json=json_encode($access_token_arr);
		//$access_token_json=0;
		//echo $access_token_json;
		//echo "INSERT INTO config ('access_token') VALUES ($access_token_json)<br />";
		$sql=mysql_query("INSERT INTO config (item,value) VALUES ('access_token','$access_token_json')");
		//if ($sql) echo 'ok';else die('Error: ' . mysql_error());
		return $access_token=$access_token;
	}

}

function getinfo($fromUsername){
	$json= strip_tags(curl_https('https://api.weixin.qq.com/cgi-bin/user/info?access_token='.access_token().'&openid='.$fromUsername.'&lang=zh_CN'));
	$de_json=json_decode($json,TRUE);
    if (isset($de_json['errcode'])) $txt='error:'.$json;//出现错误则返回错误代码
    	else if (!$de_json['subscribe']) $txt='您尚未关注我们，请先“关注”哦';
    		else {
    			$txt=$de_json;//拉取信息，并存入数据库
    		}
    //$txt='Token='.TOKEN.'$txt='.$txt;
	return $txt;
}

function command($str,$fromUsername,$toUsername,$msgtype)
{
$cmd=explode(" ",$str);	
  switch ($cmd[0])
	{
		case 'help':
			if (!isset ($cmd[1]))
			$contentStr='命令行模式--帮助
			输入\':time\'可获取当前服务器的时间;
			输入\':set\'可以进行账户设置，详情请输入\':help set\'查看详细帮助;
			';
			else {
				switch ($cmd[1]){
					case 'set':
						$contentStr='set命令使用帮助：
						请使用\':set(空格)参数=值[(空格)参数2=值2...]\'的格式
						例如设置姓名为myname，性别为男：\':set name=myname sex=男\'
						可用参数列表：
						name;sex
						';
				}
			}
			break;
		case 'status':
			$sql=mysql_query("SELECT * FROM user WHERE username='$fromUsername'");
			$val=mysql_fetch_array($sql);
			switch ($val['status'])
			{
				case 0:
					$contentStr='基础模式';
					break;
				case 1:
					$contentStr='学生会报名模式';
					break;
				case 2:
					$contentStr='模特报名模式';
			}
			$contentStr='当前模式为：'.$contentStr;
			break;
		case 'time':
			date_default_timezone_set('Asia/Shanghai');
			$contentStr='现在是'.date('Y-m-d H:i:s', time());
			break;
		case 'set':
			$len_cmd=count($cmd)-1;
			$cmd_count=0;
			$cmd_fail='';
			for($i=1;$i<=$len_cmd;$i++){
				$value=explode("=",$cmd[$i]);
				echo '设置变量'.$value[0].'的值为'.$value[1].'<br />';
				switch ($value[0]){
					case 'name':
						$sql=mysql_query("UPDATE user SET name='$value[1]' WHERE username='$fromUsername'");
						if($sql) $cmd_count++;
						break;
					default:
						$cmd_fail=$cmd_fail.$value[0].';';
				}
			}		
			if($cmd_fail==NULL) $contentStr='成功执行命令数：'.$cmd_count;
				else $contentStr='成功执行命令数：'.$cmd_count.'，未识别参数:'.$cmd_fail.'，请使用：help set 命令查看可设置参数';
			break;
		case 'info':
			$sql=mysql_query("SELECT * FROM user WHERE username='$fromUsername'");
			$val=mysql_fetch_array($sql);
			$contentStr='name='.$val['name'];
			break;
		case 'test':
			$contentStr='TestMode:$keyword='.$keyword.' ;$fromUsername='.$fromUsername.'$toUsername ='.$toUsername;
			break;
		case '学生会报名':
		case 'xshbm':
			if (!isset ($cmd[1]))
			{
				$time=time();
				$sql=mysql_query("SELECT * FROM user WHERE username='$fromUsername'");
				$val=mysql_fetch_array($sql);
				$id=$val['id'];
				$sql=mysql_query("UPDATE user SET status='1' , lastfunctime='$time' WHERE id='$id'");
				//echo $sql;
				include_once 'xsh.php';
				//echo $cmd[0];
				$contentStr=xsh($cmd[0],$fromUsername,$msgtype);
			}else
			{
				switch ($cmd[1])
				{
					case 'show':
					case '查看':
						$contentStr=showxsh($fromUsername);
					break;
				}
			}		
			break;
		case 'getinfo':
			return getinfo($fromUsername);
			break;
		default:
			$contentStr='命令未能识别！请重新输入';
	}
	return $contentStr;
}

print_r(getinfo('oJ_2YjiGEKAIM2nMd1YQgSE5Ydnc'));
?>