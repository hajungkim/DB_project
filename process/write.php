<?php
session_start();
header("content-type:text/html; charset=utf-8");
include_once ("../lib/common.php");
include_once ("../lib/dbconn.php");
include_once ("../lib/function.php");


if ($_POST['mode'] == "write"){ //글쓰기

	if (!$_SESSION['id'] or !$_POST['mode'] or !$_POST['table'] or !$_POST['subject'] or !$_POST['content']) die ("Error"); //넘어온 변수 검사

	$depth = 0; //depth, ord 를 0으로 초기화
	$ord = 0;
	
	$tmp_sql = "select max(qb_idx) as max_qb_idx from qboard";
	$tmp_result = mysql_query($tmp_sql, $connect);
	$tmp_row = mysql_fetch_array($tmp_result);
	$max_qb_idx = $tmp_row['max_qb_idx'] + 1;
	
	// 레코드 삽입
	$sql = "insert into qboard (qb_idx, qb_table, qb_group_num, qb_ord, qb_depth, ";
	$sql .= "qm_user, qb_subject, qb_content, qb_date, qb_ip) ";
	$sql .= "values (".$max_qb_idx.", '".trim($_POST['table'])."', ".$max_qb_idx.", ".$ord.", ".$depth.", ";
	$sql .= "'".trim($_SESSION['id'])."', '".trim($_POST['subject'])."', '".trim($_POST['content'])."', now(), '".$_SERVER['REMOTE_ADDR']."')";
	$result = mysql_query($sql, $connect);








}else if ($_POST['mode'] == "reply"){ //답변쓰기

	if (!$_SESSION['id'] or !$_POST['mode'] or !$_POST['table'] or !$_POST['idx'] or !$_POST['subject'] or !$_POST['content']) die ("Error");
	
	if (isset($_POST['idx'])){ //답변 글
	
		// 부모 글 가져오기
		$sql = "select * from qboard where qb_idx = ".trim($_POST['idx']);
		$result = mysql_query($sql, $connect);
		$row = mysql_fetch_array($result);
		
		// 부모 글로 부터 group_num, depth, ord 값 설정
		$group_num = $row['qb_group_num'];
		if ($row['qb_ord'] >= 1) die ("Error"); //답변이 가능한지 검사
		$ord = $row['qb_ord'] + 1;
		$depth = $row['qb_depth'] + 1;
		
		// 해당 그룹에서 ord 가 부모글의 ord($row['qb_ord']) 보다 큰 경우엔
		// ord 값 1 증가 시킴
		$sql = "update qboard set qb_ord = qb_ord + 1 where qb_table = '".trim($_POST['table'])."' and ";
		$sql .= "qb_group_num = ".$row['qb_group_num']." and qb_ord > ".$row['qb_ord'];
		mysql_query($sql, $connect);  
		
		$tmp_sql = "select max(qb_idx) as max_qb_idx from qboard";
		$tmp_result = mysql_query($tmp_sql, $connect);
		$tmp_row = mysql_fetch_array($tmp_result);
		$max_qb_idx = $tmp_row['max_qb_idx'] + 1;
		
		// 레코드 삽입
		$sql = "insert into qboard (qb_idx, qb_table, qb_group_num, qb_ord, qb_depth, qm_user, qb_subject, qb_content, qb_date, qb_ip) ";
		$sql .= "values (".$max_qb_idx.", '".trim($_POST['table'])."', ".$group_num.", ".$ord.", ".$depth.", ";
		$sql .= "'".trim($_SESSION['id'])."', '".trim($_POST['subject'])."', '".trim($_POST['content'])."', now(), '".$_SERVER['REMOTE_ADDR']."')";
		$result = mysql_query($sql, $connect); //$sql 에 저장된 명령 실행
		
		if ($result){
			echo $common_path."/view.php?table=".$_POST['table']."&page=".$_POST['page']."&idx=".$max_qb_idx; //경로
		}
	}
	
	
	







}else if ($_POST['mode'] == "modify"){ //수정하기


	if (!$_SESSION['id'] or !$_POST['mode'] or !$_POST['table'] or !$_POST['idx']) die ("Error");
	
	$sql = "update qboard set ";
	$sql .= "qb_subject = '".trim($_POST['subject'])."', ";
	$sql .= "qb_content = '".trim($_POST['content'])."', ";
	$sql .= "qb_date = now(), ";
	$sql .= "qb_ip = '".$_SERVER['REMOTE_ADDR']."' ";
	$sql .= "where qm_user = '".trim($_SESSION['id'])."' and qb_table = '".trim($_POST['table'])."' and qb_idx = ".trim($_POST['idx']);
	mysql_query($sql, $connect);










}else if ($_POST['mode'] == "delete"){ //삭제하기

	if (!$_SESSION['id'] or !$_POST['mode'] or !$_POST['table'] or !$_POST['idx']) die ("Error");
	
	$tmp_sql = "select qb_group_num, qb_ord from qboard where qb_idx = ".trim($_POST['idx']);
	$tmp_result = mysql_query($tmp_sql, $connect);
	$tmp_row = mysql_fetch_array($tmp_result);
	$qb_group_num = $tmp_row['qb_group_num'];
	$qb_ord = $tmp_row['qb_ord'];	
	
	if ($qb_ord != 0) { //자식 글 삭제
		$sql = "delete from qboard where qm_user = '".trim($_SESSION['id'])."' ";
		$sql .= "and qb_table = '".trim($_POST['table'])."' and qb_idx = ".trim($_POST['idx']);
		mysql_query($sql, $connect);
		
		$sql = "delete from qboard_comment where qc_parent = ".trim($_POST['idx']); //댓글 삭제
		mysql_query($sql, $connect);
		
	}else{
		$tmp_sql1 = "select count(*) as cnt from qboard where qb_table = '".trim($_POST['table'])."' and qb_group_num = ".$qb_group_num;
		$tmp_result1 = mysql_query($tmp_sql1, $connect);
		$tmp_row1 = mysql_fetch_array($tmp_result1);
		
		if ($tmp_row1['cnt'] == 1){ //부모 글 삭제
			$sql = "delete from qboard where qm_user = '".trim($_SESSION['id'])."' ";
			$sql .= "and qb_table = '".trim($_POST['table'])."' and qb_idx = ".trim($_POST['idx']);
			mysql_query($sql, $connect);
			
			$sql = "delete from qboard_comment where qc_parent = ".trim($_POST['idx']); //댓글 삭제
			mysql_query($sql, $connect);

		}else{
			echo 1; //이 글과 관련된 답변글이 존재하므로 삭제 할 수 없습니다.\n우선 답변글부터 삭제하여 주십시오.
		}
	}
	









}

mysql_close();


?>

