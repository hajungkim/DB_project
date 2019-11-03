<?php
session_start();
header("content-type:text/html; charset=utf-8");
include_once ("lib/common.php");
include_once ("lib/dbconn.php");
include_once ("lib/function.php");
if (!isset($_SESSION['id'])) alert('로그인을 하셔야 이용하실 수 있습니다.', $common_path);
if (!get_board_table_code($_GET['table'])) alert('존재하지 않는 게시판입니다.', $common_path);






if ($_GET['mode'] == "reply"){ //답변쓰기

	if (!isset($_GET['idx']) || (eregi("[^0-9]", trim($_GET['idx']))) || (get_board_idx_code($_GET['table'], $_GET['idx']) == 0)) //넘어온 변수 검사
	alert('삭제된 게시물이거나 잘못된 접근입니다.', $common_path); 
	
	include_once ("head.php");

	$sql = "select * from qboard where qb_idx = ".$_GET['idx'];
	$result = mysql_query($sql, $connect);
	$row = mysql_fetch_array($result);
	$reply_qb_table = $row['qb_table'];
	//$reply_qm_user = $row['qm_user'];
	$reply_qb_subject = "[re] ".htmlspecialchars(get_mb_strimwidth($row['qb_subject'], 40));
	$reply_qb_content = ">".$row['qb_content'];
	$reply_qb_content = str_replace("\n", "\n>", $reply_qb_content);
	$reply_qb_content = "\n\n\n".$reply_qb_content;
?>


    <h1><strong>Reply</strong></h1>
	
    
    
    <form>
    <table class="table">
      <tr>
        <td width="150" align="right"><strong>아이디 : </strong></td>
        <td><? echo $_SESSION['id'] ?></td>
      </tr>
      <tr>
        <td align="right"><strong><sup>*</sup>제목 : </strong></td>
        <td><input type="text" id="reply_subject" maxlength="150" class="form-control" placeholder="제목" value="<? echo $reply_qb_subject ?>" /></td>
      </tr>
      <tr>
        <td align="right"><strong><sup>*</sup>내용 : </strong></td>
        <td><textarea id="reply_content" class="form-control" rows="13" placeholder="내용" /><? echo $reply_qb_content ?></textarea></td>
      </tr>
      <tr>
        <td colspan="2" align="right"><input type="button" id="reply_form" value="답변쓰기" class="btn btn-default" /></td>
      </tr>
    </table>
    </form>


	<script type="text/javascript"> 
    //<![CDATA[
    
    $(document).ready(function() { 
        
        $("#reply_form").click(function() {
            if( $("input#reply_subject").val() ) { //제목
                if ( $("textarea#reply_content").val() ){ //내용
                    $.post('process/write.php', 
                    {	
                        mode:"reply",
                        table:"<? echo trim($_GET['table']) ?>",
						idx:<? echo trim($_GET['idx']) ?>,
						page:<? echo trim($_GET['page']) ?>,
                        subject:$("input#reply_subject").val(), 
                        content:$("textarea#reply_content").val()
                    },
                    function(data) {
                        self.location.href = data;
                    });
                }else{
                    alert("내용을 입력하세요.");  	
                }	
            }else{
                alert("제목을 입력하세요.");
            }
        });
        
    
    });
    
    //]]> 
    </script>
















<? }else if ($_GET['mode'] == "modify"){ //수정하기

	if (!isset($_GET['idx']) || (eregi("[^0-9]", trim($_GET['idx']))) || (get_board_idx_code($_GET['table'], $_GET['idx']) == 0)) //넘어온 변수 검사
	alert('삭제된 게시물이거나 잘못된 접근입니다.', $common_path); 

	$sql = "select * from qboard where qb_idx = ".$_GET['idx'];
	$result = mysql_query($sql, $connect);
	$row = mysql_fetch_array($result);
	$modify_qb_table = $row['qb_table'];
	$modify_qm_user = $row['qm_user'];
	$modify_qb_subject = htmlspecialchars($row['qb_subject']);
	$modify_qb_content = htmlspecialchars($row['qb_content']);
	
	if ($_SESSION['id'] != $modify_qm_user) alert('본인의 글이 아닙니다.', $common_path);
	
	include_once ("head.php");
?>	

	<h1><strong>Modify</strong></h1>	
    
    
    <form>
    <table class="table">
      <tr>
        <td width="150" align="right"><strong>아이디 : </strong></td>
        <td><? echo $_SESSION['id'] ?></td>
      </tr>
      <tr>
        <td align="right"><strong><sup>*</sup>제목 : </strong></td>
        <td><input type="text" id="modify_subject" maxlength="150" class="form-control" placeholder="제목" value="<? echo $modify_qb_subject ?>" /></td>
      </tr>
      <tr>
        <td align="right"><strong><sup>*</sup>내용 : </strong></td>
        <td><textarea id="modify_content" class="form-control" rows="13" placeholder="내용" /><? echo $modify_qb_content ?></textarea></td>
      </tr>
      <tr>
        <td colspan="2" align="right"><input type="button" id="modify_form" value="수정하기" class="btn btn-default" /></td>
      </tr>
    </table>
    </form>


	<script type="text/javascript"> 
    //<![CDATA[
    
    $(document).ready(function() { 
        
        $("#modify_form").click(function() {
            if( $("input#modify_subject").val() ) { //제목
                if ( $("textarea#modify_content").val() ){ //내용
                    $.post('process/write.php', 
                    {	
                        mode:"modify",
                        table:"<? echo trim($_GET['table']) ?>",
						idx:<? echo trim($_GET['idx']) ?>,
                        subject:$("input#modify_subject").val(), 
                        content:$("textarea#modify_content").val()
                    },
                    function(data) {
                        self.location.href = '<? echo $_SERVER['HTTP_REFERER'] ?>';	
                    });
                }else{
                    alert("내용을 입력하세요.");  	
                }	
            }else{
                alert("제목을 입력하세요.");
            }
        });
        
    
    });
    
    //]]> 
    </script>


















<? }else{ //글쓰기

	include_once ("head.php"); ?>

	<h1><strong>Write</strong></h1>
    
    <form>
    <table class="table">
      <tr>
        <td width="150" align="right"><strong>아이디 : </strong></td>
        <td><? echo $_SESSION['id'] ?></td>
      </tr>
      <tr>
        <td align="right"><strong><sup>*</sup>제목 : </strong></td>
        <td><input type="text" id="write_subject" maxlength="150" class="form-control" placeholder="제목" /></td>
      </tr>
      <tr>
        <td align="right"><strong><sup>*</sup>내용 : </strong></td>
        <td><textarea id="write_content" class="form-control" rows="13" placeholder="내용" /></textarea></td>
      </tr>
      <tr>
        <td colspan="2" align="right"><input type="button" id="write_form" value="글쓰기" class="btn btn-default" /></td>
      </tr>
    </table>
    </form>
    
    
    
    <script type="text/javascript"> 
    //<![CDATA[
    
    $(document).ready(function() { 
        
        $("#write_form").click(function() {
            if( $("input#write_subject").val() ) { //제목
                if ( $("textarea#write_content").val() ){ //내용
                    $.post('process/write.php', 
                    {	
                        mode:"write",
                        table:"<? echo trim($_GET['table']) ?>",
                        subject:$("input#write_subject").val(), 
                        content:$("textarea#write_content").val()
                    },
                    function(data) {
                        self.location.href = '<? echo $common_path."/board.php?table=".trim($_GET['table']) ?>';
                    });
                }else{
                    alert("내용을 입력하세요.");  	
                }	
            }else{
                alert("제목을 입력하세요.");
            }
        });
        
    
    });
    
    //]]> 
    </script>










<?php
} //if ($_GET['mode'] == "reply")   if ($_GET['mode'] == "modify")

include_once ("tail.php");
?>