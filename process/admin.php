<?php
session_start();
header("content-type:text/html; charset=utf-8");
include_once ("../lib/common.php");
include_once ("../lib/dbconn.php");
include_once ("../lib/function.php");
if ($_SESSION['level'] != 100) die ("Error"); //넘어온 변수 검사





if ($_POST['mode'] == "delete"){ //탈퇴 삭제

	$tmp_sql = "select qm_level from qboard_member where qm_user = '".trim($_POST['user'])."'";
	$tmp_result = mysql_query($tmp_sql, $connect);
	$tmp_row = mysql_fetch_array($tmp_result);

	if ($tmp_row['qm_level'] == "0"){ //사용자
		$sql = "update qboard_member set ";
		$sql .= "qm_level = 101, qm_updated_date = now() "; //회원탈퇴
		$sql .= "where qm_user = '".trim($_POST['user'])."'";
		mysql_query($sql, $connect);
	}
	
	//정상적으로 탈퇴가 되었습니다.
	









}

mysql_close();


?>

