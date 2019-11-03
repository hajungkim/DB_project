<?php
session_start();
header("content-type:text/html; charset=utf-8");
include_once ("lib/common.php");
include_once ("lib/dbconn.php");
include_once ("lib/function.php");
if (!get_board_table_code($_GET['table'])) alert('존재하지 않는 게시판입니다.', $common_path);
if (!isset($_GET['idx']) || (eregi("[^0-9]", trim($_GET['idx']))) || (get_board_idx_code($_GET['table'], $_GET['idx']) == 0)) //넘어온 변수 검사
	alert('삭제된 게시물이거나 잘못된 접근입니다.', $common_path); 
include_once ("head.php");

//페이지 변수 설정
$page = trim($_GET['page']); 
if (!$page) $page = 1; //페이지 번호를 1로 초기화

//$sql = "select * from qboard where qb_table = '".trim($_GET['table'])."' and qb_idx = ".trim($_GET['idx']); //!메일 !레벨
$sql = "select a.*, b.qm_email, b.qm_level from qboard as a join qboard_member as b on a.qm_user = b.qm_user ";
$sql .= "where a.qb_table = '".trim($_GET['table'])."' and a.qb_idx = ".trim($_GET['idx']);
$result = mysql_query($sql, $connect);
$row = mysql_fetch_array($result);

$qb_idx = $row['qb_idx'];
$qb_table = $row['qb_table'];
//$qb_group_num = $row['qb_group_num'];
//$qb_ord = $row['qb_ord'];
$qb_depth = $row['qb_depth'];
$qm_user = $row['qm_user'];
$qb_subject = htmlspecialchars($row['qb_subject']);
$qb_content = conv_content($row['qb_content']);
$qb_date = str_replace("-", "/", (substr($row['qb_date'], 2)));
$qb_hits = $row['qb_hits'];
$qb_ip = ip_hidden($row['qb_ip']);

//join qboard_member as b
$qm_email = $row['qm_email'];
$qm_level = $row['qm_level'];
if ($qm_level == 101){ //회원탈퇴 취소선
	$qb_subject = "<strike>".$qb_subject."</strike>"; 
	$qb_content = "<strike>".$qb_content."</strike>";
}

$sql = "update qboard set qb_hits = (qb_hits + 1) where qb_idx = '".trim($_GET['idx'])."'"; //조회수 증가
mysql_query($sql, $connect);

?>

<h1><strong><? echo trim($_GET['table']) ?></strong></h1>

<table class="table table-hover">
  <tr>
    <td width="100" align="right"><strong>아이디 : </strong></td>
    <td><? echo $qm_user ?></td>
  </tr>
  <tr>
    <td align="right"><strong>e-메일 : </strong></td>
    <td><a href="mailto:<? echo $qm_email ?>"><? echo $qm_email ?></a></td>
  </tr>
  <tr>
    <td align="right"><strong>날짜 : </strong></td>
    <td><? echo $qb_date ?></td>
  </tr>
  <tr>
    <td align="right"><strong>IP : </strong></td>
    <td><? echo $qb_ip ?></td>
  </tr>
  <tr>
    <td align="right"><strong>제목 : </strong></td>
    <td style="word-break:break-all;"><? echo $qb_subject ?></td>
  </tr>
  <tr>
    <td align="right"><strong>내용 : </strong></td>
    <td style="word-break:break-all;"><? echo $qb_content ?></td>
  </tr>
</table>

<br />
<? include_once ("comment.php"); //댓글 ?>
<br /><br>



<div class="row">
  <div class="col-lg-8">
	<? if ($_SESSION['id'] == $qm_user){  ?>
    	<a href="write.php?mode=modify&table=<? echo $_GET['table'] ?>&page=<? echo $page ?>&idx=<? echo $_GET['idx'] ?>" class="btn btn-primary btn-small">수정하기</a>
        <a href="#" id="delete" class="btn btn-primary btn-small">삭제하기</a>
    <? } ?>
    <? if (isset($_SESSION['id'])){ if ($qb_depth < 1){ //답변이 가능한지 검사 ?>
    	<a href="write.php?mode=reply&table=<? echo $_GET['table'] ?>&page=<? echo $page ?>&idx=<? echo $_GET['idx'] ?>" class="btn btn-primary btn-small">답변쓰기</a>  
    <? }}else{ if ($qb_depth < 1){ ?>
    	<a href="javascript:;" onclick="javascript:alert('글을 쓸 권한이 없습니다.\n회원이시라면 로그인 후 이용해 보십시오.');//location.replace('register.php')" class="btn btn-primary btn-small">답변쓰기</a>
    <? }} ?>
  </div>
  <div class="col-lg-4" style="text-align:right; border: 1ps solid #ff0000">
  	<a href="board.php?table=<? echo trim($_GET['table']) ?>&page=<? echo $page ?>" class="btn btn-default btn-small">목록</a>
  </div>
</div>






<? if ($_SESSION['id'] == $qm_user){ ?>
<script type="text/javascript"> 
//<![CDATA[

$(document).ready(function() { 
	
	$("#delete").click(function() {		
		if (window.confirm("삭제하시겠습니까?")) {
			$.post('process/write.php', 
			{	
				mode:"delete",
				table:"<? echo trim($_GET['table']) ?>",
				idx:<? echo trim($_GET['idx']) ?>
			},
			function(data) {
				if (data == 1){
					alert("이 글과 관련된 답변글이 존재하므로 삭제 할 수 없습니다.\n우선 답변글부터 삭제하여 주십시오.");
				}else{
					self.location.href = '<? echo $common_path."/board.php?table=".$_GET['table'] ?>';
				}				
			});		
		}else{
			return false;
		}		
	});
	
});

//]]> 
</script>
<? } ?>





<?php
include_once ("view.list.php");
include_once ("tail.php");
?>