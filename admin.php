<?php
session_start();
header("content-type:text/html; charset=utf-8");
include_once ("lib/common.php");
include_once ("lib/dbconn.php");
include_once ("lib/function.php");
if ($_SESSION['level'] != 100) alert('관리자 페이지를 사용할 수 있는 권한이 없습니다.', $common_path);
include_once ("head.php");
?>




<h1>Administrator</h1>

<table class="table table-hover">
   <thead>	
	  <tr>
	  	<td align="center" width="50"><strong>번호</strong></td>
	    <td align="center"><strong>ID</strong></td>
	    <td align="center"><strong>가입일</strong></td>
	    <td align="center"><strong>접속일</strong></td>
	    <td align="center"><strong>IP</strong></td>
	    <td><strong>프로필</strong></td>
	    <td align="center"><strong>권한</strong></td>
	    <td align="center"><strong>삭제</strong></td>
	  </tr>
   </thead>	  
<?
//아이디 목록 구하기
$sql = "select * from qboard_member order by qm_date desc";
$result = mysql_query($sql, $connect);
$total_record = mysql_num_rows($result); //전체 글 수

$scale = $common_page_rows; //한 화면에 표시되는 글 수

//전체 페이지 수($total_page) 계산
if ($total_record % $scale == 0){  //$total_record를 $scale로 나눈 나머지 계산 
	$total_page = floor($total_record/$scale); //나머지가 0일 때 
}else{
	$total_page = floor($total_record/$scale) + 1; //나머지가 0이 아닐 때
}

//페이지 변수 설정
$page = trim($_GET['page']); 
if (!$page) //페이지번호($page)가 0 일 때
	$page = 1; //페이지 번호를 1로 초기화

$start = ($page - 1) * $scale; //표시할 페이지($page)에 따라 $start 계산  
$number = $total_record - $start;

for ($i = $start ; $i < $start + $scale && $i < $total_record ; $i++){
	mysql_data_seek($result, $i); //가져올 레코드로 위치(포인터) 이동  
	$row = mysql_fetch_array($result); //하나의 레코드 가져오기
	
	$qm_user = $row['qm_user'];
	$qm_email = $row['qm_email'];
	$qm_date = str_replace("-", "/", (substr($row['qm_date'], 2, 8)));
	$qm_updated_date = str_replace("-", "/", (substr($row['qm_updated_date'], 2, 8)));
	$qm_ip = ip_hidden($row['qm_ip']);
	$qm_profile = htmlspecialchars($row['qm_profile']);
	$qm_level = $row['qm_level'];
	switch($qm_level) {  
		case 0 : $qm_level_str = "사용자"; break;
		case 100 : $qm_level_str = "관리자"; break;
		case 101 : $qm_level_str = "탈퇴자"; break;
	} 
	//레코드 화면에 출력하기
?>
<tr>
	<td align="center"><? echo $number ?></td>
	<td align="center"><a href="mailto:<? echo $qm_email ?>"><? echo $qm_user ?></a></td>
    <td align="center"><? echo $qm_date ?></td>
    <td align="center"><? echo $qm_updated_date ?></td>
    <td align="center"><? echo $qm_ip ?></td>
    <td><a href="#" title="<? echo $qm_profile ?>"><? echo get_mb_strimwidth($qm_profile, 40) ?></a></td>
    <td align="center"><? echo $qm_level_str ?></td>
    <td align="center" style="text-align: center"><? if ($qm_level == 0){ ?><? if ($qm_user != "test"){ ?><a href="#" title="<? echo $qm_user ?>" class="btn btn-danger btn-small-small delete" />Del</a><? }} ?></td>
</tr>
<?
	//end 레코드 화면에 출력하기
	
	$number--; //데이터 갯수 체크를 위한 변수를 1 감소시킴
} //for ($i = $start ; $i < $start + $scale && $i < $total_record ; $i++){
?>
</table>

<div style="text-align:center;">
<?php
//게시판 목록 하단에 페이지 링크 번호 출력 / 한페이지에 보여줄 행, 현재페이지, 총페이지수, URL
echo get_paging($common_list_block, $page, $total_page, $common_path."/admin.php?page=");
?>
</div>



<script type="text/javascript"> 
//<![CDATA[

$(document).ready(function() { 

	$( ".delete" ).click(function(){
		if (window.confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n정말 삭제하시겠습니까?")) {
			$.post('process/admin.php', { mode:"delete", user:$(this).attr("title") }, function(){
				alert("정상적으로 탈퇴가 되었습니다.");
				self.location.reload();
			});
		}else{
			return false;
		}
	});

	
});

//]]> 
</script>




