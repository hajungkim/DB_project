<?php
session_start();
header("content-type:text/html; charset=utf-8");
include_once ("lib/common.php");
include_once ("lib/dbconn.php");
include_once ("lib/function.php"); 

$sql = "select a.*, b.qm_email from qboard_comment as a join qboard_member as b on a.qm_user = b.qm_user ";
$sql .= "where a.qc_parent = '".trim($_GET['idx'])."' and a.qc_idx < ".trim($_GET['lastPostID'])." "; 
$sql .= "order by a.qc_idx desc limit ".$common_comment_rows;
$result = mysql_query($sql, $connect);
$total_comment = mysql_num_rows($result);
if ($total_comment != 0){
?>
<table class="table table-hover">
<?
	while ($row = mysql_fetch_array($result)){
		$qc_idx = $row['qc_idx'];
		$qc_parent = $row['qc_parent'];
		$qc_user = $row['qm_user'];
		$qc_comment = conv_content($row['qc_comment']);
		$qc_date = str_replace("-", "/", (substr($row['qc_date'], 2, 18)));
		$qc_ip = ip_hidden($row['qc_ip']);
		$qc_email = $row['qm_email'];
	?>
	<tr class="wrdLatest dl-horizontal" idx="<? echo $qc_idx ?>">
	    <td>
            <dt><a href="mailto:<? echo $qc_email ?>"><? echo $qc_user ?></a></dt>
            <dd><? echo $qc_comment ?><? echo ($_SESSION['id'] == $qc_user) ? "<a href=\"javascript:comment_del_more(".$qc_parent.", ".$qc_idx.");\" class='close' title='삭제'>&times;</a>" : "" ?></dd>
        </td>
    </tr>                
	<? } //while ($row = mysql_fetch_array($result)){ ?>
</table>
<? } //if ($total_comment != 0){ ?>    


<script type="text/javascript"> 
//<![CDATA[

function comment_del_more(parent, idx) {
	if (window.confirm("댓글을 삭제하시겠습니까?")) {
		self.location.href = "process/comment.php?mode=delete&idx=" + idx;
	}else{
		return false;
	}
}

//]]> 
</script>