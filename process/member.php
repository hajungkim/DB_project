<?php
session_start();
header("content-type:text/html; charset=utf-8");
include_once ("../lib/common.php");
include_once ("../lib/dbconn.php");
include_once ("../lib/function.php");


if ($_POST['mode'] == "modify"){ //정보수정

	if (!$_SESSION['id'] or !$_POST['pwd'] or !$_POST['pwd_confirm'] or !$_POST['email'] or ($_POST['pwd'] != $_POST['pwd_confirm'])) die ("Error"); //넘어온 변수 검사
	
	//회원정보
	$sql = "update qboard_member set ";
	$sql .= "qm_pwd = password('".trim($_POST['pwd'])."'), ";
	$sql .= "qm_email = '".trim($_POST['email'])."', ";
	$sql .= "qm_updated_date = now(), ";
	$sql .= "qm_profile = '".trim($_POST['profile'])."', ";
	$sql .= "qm_ip = '".$_SERVER['REMOTE_ADDR']."' ";
	$sql .= "where qm_user = '".trim($_SESSION['id'])."'";
	mysql_query($sql, $connect); 
	
	
	
	
	



}else if ($_POST['mode'] == "leave"){ //회원탈퇴

	if (!$_SESSION['id']) die ("Error");
	
	if (!$_POST['pwd']){
		echo 0; //비밀번호을 입력하세요.
	}else{
	
		$tmp_sql = "select qm_pwd from qboard_member where qm_user = '".trim($_SESSION['id'])."'";
		$tmp_result = mysql_query($tmp_sql, $connect);
		$tmp_row = mysql_fetch_array($tmp_result);
		
		if ($tmp_row['qm_pwd'] != get_password(trim($_POST['pwd']))){ //입력된 비밀번호와 저장된 비밀번호
			echo 1; //비밀번호가 일치하지 않습니다. 다시 입력해주세요.
		}else{
			if ($_SESSION['level'] == "100"){ 
				echo 2; //관리자 아이디는 접근 불가합니다.
			}else{
				$sql = "update qboard_member set ";
				$sql .= "qm_level = 101, qm_updated_date = now() "; //회원탈퇴
				$sql .= "where qm_user = '".trim($_SESSION['id'])."'";
				mysql_query($sql, $connect);
	
				session_destroy(); //로그아웃 세션
				
				echo 3; //정상적으로 탈퇴가 되었습니다.
			}
		}
	
	} //if (!$_POST['password']){







}	
	
	
	
mysql_close();

?>