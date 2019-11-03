<?php
session_start();
header("content-type:text/html; charset=utf-8");
include_once ("../lib/common.php");
include_once ("../lib/dbconn.php");
include_once ("../lib/function.php");

if (!$_POST['id'] or !$_POST['pwd'] or !$_POST['pwd_confirm'] or !$_POST['email'] or ($_POST['pwd'] != $_POST['pwd_confirm'])) die ("Error"); //넘어온 변수 검사

//같은 아이디가 있는지 검사
$tmp_sql = "select count(*) as cnt from qboard_member where qm_level != 101 and qm_user = '".trim($_POST['id'])."'";
$tmp_result = mysql_query($tmp_sql, $connect);
$tmp_row = mysql_fetch_array($tmp_result);
if ($tmp_row['cnt'] == 1){
	echo 0; //이미 사용중인 아이디 입니다.
}else{	
	//회원탈퇴 검사
	$tmp_sql1 = "select qm_level from qboard_member where qm_user = '".trim($_POST['id'])."'";
	$tmp_result1 = mysql_query($tmp_sql1, $connect);
	$tmp_row1 = mysql_fetch_array($tmp_result1);
	if ($tmp_row1['qm_level'] == "101"){
		echo 1; //탈퇴한 아이디이므로 접근하실 수 없습니다.
	}else{
		//회원정보
		$sql = "insert into qboard_member (qm_user, qm_pwd, qm_email, qm_date, qm_updated_date, qm_ip, qm_profile) ";
		$sql .= "values ('".trim($_POST['id'])."', password('".trim($_POST['pwd'])."'), '".trim($_POST['email'])."', ";
		$sql .= "now(), now(), '".$_SERVER['REMOTE_ADDR']."', '".trim($_POST['profile'])."')";
		$result = mysql_query($sql, $connect);
		if ($result){
			//세션
			$_SESSION['id'] = trim($_POST['id']);
			$_SESSION['level'] = 0; //사용자 레벨
			echo 2; //로그인
		}
	}
	
}

mysql_close();


?>

