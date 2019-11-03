<?php
session_start();
header("content-type:text/html; charset=utf-8");
include_once ("lib/common.php");
include_once ("lib/dbconn.php");
include_once ("lib/function.php");
if (!get_board_table_code($_GET['table'])) alert('존재하지 않는 게시판입니다.', $common_path);
include_once ("head.php");
?>

<h1><strong><? echo trim($_GET['table']) ?></strong></h1>

<table class="table">
   <thead>	
	  <tr>
	  	<td align="center" width="50"><strong>번호</strong></td>
	    <td><strong>제목</strong></td>
	    <td align="center" width="120"><strong>이름</strong></td>
	    <td align="center" width="80"><strong>날짜</strong></td>
	    <td align="center" width="70"><strong>조회수</strong></td>
	  </tr>
   </thead>
<?
//글목록 구하기
if (!$_GET['search']){ //!검색
	//$sql = "select * from qboard where qb_table = '".trim($_GET['table'])."' order by qb_group_num desc, qb_ord asc"; //!메일 !레벨
	$sql = "select a.*, b.qm_email, b.qm_level from qboard as a join qboard_member as b on a.qm_user = b.qm_user ";
	$sql .= "where a.qb_table = '".trim($_GET['table'])."' order by a.qb_group_num desc, a.qb_ord asc"; //default
}else{
	//$sql = "select * from qboard where qb_table = '".trim($_GET['table'])."' and (qb_subject like '%".htmlspecialchars(urldecode($_GET['search']))."%' or qb_content like '%".htmlspecialchars(urldecode($_GET['search']))."%') order by qb_group_num desc, qb_ord asc"; //!메일 !레벨
	$sql = "select a.*, b.qm_email, b.qm_level from qboard as a join qboard_member as b on a.qm_user = b.qm_user ";
	$sql .= "where a.qb_table = '".trim($_GET['table'])."' and ";
	$sql .= "(a.qb_subject like '%".htmlspecialchars(urldecode($_GET['search']))."%' ";
	$sql .= "or a.qb_content like '%".htmlspecialchars(urldecode($_GET['search']))."%') ";
	$sql .= "order by a.qb_group_num desc, a.qb_ord asc"; //제목(qb_subject), 내용(qb_content) 검색
}
$result = mysql_query($sql, $connect);
$total_record = mysql_num_rows($result); //전체 글 수

if ($total_record == 0) echo "<script>alert('등록된 게시글이 없습니다.');//history.go(-1);</script>"; //데이터가 하나도 없으면

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
	
	$qb_idx = $row['qb_idx'];
	$qb_table = $row['qb_table'];
	$qm_user = $row['qm_user'];
	$qb_subject = htmlspecialchars(get_mb_strimwidth($row['qb_subject'], 70)); //문자열 끊기
	$qb_date = str_replace("-", "/", (substr($row['qb_date'], 2, 8)));	
	$qb_hits = $row['qb_hits'];
	
	//join qboard_member as b
	$qm_email = $row['qm_email'];
	$qm_level = $row['qm_level'];
	if ($qm_level == 101) $qb_subject = "<strike>".$qb_subject."</strike>"; //회원탈퇴 취소선
	
	//답글 앞에 붙을 기호 만들기
	$space = "";
	for ($j=0; $j<$row['qb_depth']; $j++) $space = "&nbsp;".$space;
	
	//레코드 화면에 출력하기
?>
<tr>
	<td align="center"><? echo $number ?></td>
	<td>
		<? echo ($row['qb_depth'] > 0) ? $space."└" : "" ?>
        <a href="view.php?table=<? echo trim($_GET['table']) ?>&page=<? echo $page ?>&search=<? echo urlencode($_GET['search']) ?>&idx=<? echo $qb_idx ?>"><? echo $qb_subject ?>&nbsp;<? echo comment_cnt($qb_idx) ?></a>&nbsp;<? echo get_date_new($row['qb_date']) ?>
    </td>
    <td align="center"><a href="mailto:<? echo $qm_email ?>"><? echo $qm_user ?></a></td>
    <td align="center"><? echo $qb_date ?></td>
    <td align="center"><? echo $qb_hits ?></td>
</tr>
<?
	//end 레코드 화면에 출력하기
	
	$number--; //데이터 갯수 체크를 위한 변수를 1 감소시킴
} //for ($i = $start ; $i < $start + $scale && $i < $total_record ; $i++){
?>
</table>


<div style="text-align:center">
<?php
//게시판 목록 하단에 페이지 링크 번호 출력 / 한페이지에 보여줄 행, 현재페이지, 총페이지수, URL
echo get_paging($common_list_block, $page, $total_page, $common_path."/board.php?table=".trim($_GET['table'])."&search=".urlencode($_GET['search'])."&page=");
?>
</div>


<div class="row">
  <div class="col-lg-4"></div>	
  <div class="col-lg-4">
    <form>
      <div class="input-group">
        <input type="text" id="search" value="<? echo urldecode($_GET['search']) ?>" class="form-control">
        <div class="input-group-btn">
          <button type="button" class="btn btn-default" tabindex="-1" id="search_form">검색</button>
        </div>
      </div><!-- /.input-group -->   	
    </form>
  	
  </div>
  <div class="col-lg-4" style="text-align:right">
    <? if (isset($_SESSION['id'])){ ?>
    	<a href="write.php?table=<? echo trim($_GET['table']) ?>" class="btn btn-default">글쓰기</a>
	<? }else{ ?>
    	<a href="javascript:;" onclick="javascript:alert('글을 쓸 권한이 없습니다.\n회원이시라면 로그인 후 이용해 보십시오.');//location.replace('register.php')" class="btn btn-default">글쓰기</a>
    <? } ?>
  </div>
</div>




<? include_once ("tail.php") ?>