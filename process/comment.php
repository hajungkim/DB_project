<?php
session_start();
header("content-type:text/html; charset=utf-8");
include_once ("../lib/common.php");
include_once ("../lib/dbconn.php");
include_once ("../lib/function.php");




if ($_POST['mode'] == "write"){ //글쓰기
	
	if (!$_SESSION['id'] or !$_POST['mode'] or !$_POST['idx'] or !$_POST['comment']) die ("Error"); //넘어온 변수 검사
	
	$tmp_sql = "select max(qc_idx) as max_qc_idx from qboard_comment";
	$tmp_result = mysql_query($tmp_sql, $connect);
	$tmp_row = mysql_fetch_array($tmp_result);
	$max_qc_idx = $tmp_row['max_qc_idx'] + 1;
	
	// 레코드 삽입
	$sql = "insert into qboard_comment (qc_idx, qc_parent, qm_user, qc_comment, qc_date, qc_ip) ";
	$sql .= "values (".$max_qc_idx.", ".trim($_POST['idx']).", '".trim($_SESSION['id'])."', ";
	$sql .= "'".trim($_POST['comment'])."', now(), '".$_SERVER['REMOTE_ADDR']."')";
	mysql_query($sql, $connect);
	
	
	
	








}else if ($_POST['mode'] == "delete"){ //삭제하기

	if (!$_SESSION['id'] or !$_POST['mode'] or !$_POST['idx']) die ("Error");
	
	$tmp_sql = "select qm_user from qboard_comment where qc_idx = '".trim($_POST['idx'])."'";
	$tmp_result = mysql_query($tmp_sql, $connect);
	$tmp_row = mysql_fetch_array($tmp_result);
	if ($tmp_row['qm_user'] != $_SESSION['id']) die("Error"); //본인의 글이 아닙니다.
	
	$sql = "delete from qboard_comment where qm_user = '".trim($_SESSION['id'])."' and qc_idx = '".trim($_POST['idx'])."'";
	mysql_query($sql, $connect);

	
	
	
	
	



}else if ($_GET['mode'] == "delete"){ //comment.more.php 삭제하기

	if (!$_SESSION['id'] or !$_GET['mode'] or !$_GET['idx']) die ("Error");
	
	//redirect  
	$tmp_sql = "select a.qc_parent, b.qb_table from qboard_comment as a join qboard as b ";
	$tmp_sql .= "on a.qc_parent = b.qb_idx where a.qc_idx = ".trim($_GET['idx'])." order by a.qc_idx desc";
	$tmp_result = mysql_query($tmp_sql, $connect);
	$tmp_row = mysql_fetch_array($tmp_result);
	$qc_parent = $tmp_row['qc_parent'];
	$qb_table = $tmp_row['qb_table'];
	
	$tmp_sql = "select qm_user from qboard_comment where qc_idx = '".trim($_GET['idx'])."'";
	$tmp_result = mysql_query($tmp_sql, $connect);
	$tmp_row = mysql_fetch_array($tmp_result);
	if ($tmp_row['qm_user'] != $_SESSION['id']) die("Error"); //본인의 글이 아닙니다.
	
	$sql = "delete from qboard_comment where qm_user = '".trim($_SESSION['id'])."' and qc_idx = '".trim($_GET['idx'])."'"; //삭제
	$result = mysql_query($sql, $connect);
	
	if ($result){
		$tmp_url = $common_path."/view.php?table=".trim($qb_table)."&idx=".trim($qc_parent); //이전 페이지
		echo "<script type='text/javascript'>location.replace('".$tmp_url."');</script>";
		exit;
	}
	
	
	
}










mysql_close();


?>
