


<form>
  <div class="input-group">
    <input type="text" id="comment" class="form-control"maxlength="150" <? echo (!isset($_SESSION['id'])) ? "readonly placeholder='로그인 후 작성 가능합니다'" : "" ?> />
    <div class="input-group-btn">
      <button type="button" class="btn btn-default" tabindex="-1" id="comment_form">댓글</button>
    </div>
  </div><!-- /.input-group -->      
</form>

<br />


<?
$sql = "select a.*, b.qm_email from qboard_comment as a join qboard_member as b on a.qm_user = b.qm_user ";
$sql .= "where a.qc_parent = '".trim($_GET['idx'])."' ";
$sql .= "order by a.qc_idx desc limit ".$common_comment_rows;
$result = mysql_query($sql, $connect);
$total_comment = mysql_num_rows($result);
if ($total_comment != 0){
?>
<table class="table table-hover">
<?
    while ($row = mysql_fetch_array($result)){
        $qc_idx = $row['qc_idx'];
        $qc_user = $row['qm_user'];
        $qc_comment = conv_content($row['qc_comment']);
        $qc_date = str_replace("-", "/", (substr($row['qc_date'], 2, 18)));
        $qc_ip = ip_hidden($row['qc_ip']);
        $qc_email = $row['qm_email'];
    ?>
    <tr class="wrdLatest dl-horizontal" idx="<? echo $qc_idx ?>">
        <td>    
          <dt><a href="mailto:<? echo $qc_email ?>"><? echo $qc_user ?></a></dt>
          <dd><? echo $qc_comment ?>
              <? echo ($_SESSION['id'] == $qc_user) ? "<button type='button' class='comment_del close' idx='".$qc_idx."' title='삭제'>&times;</button>" : "" ?></dd>
        </td>
    </tr>              
    <? } //while ($row = mysql_fetch_array($result)){ ?>
</table>      

    <div id="lastPostsLoader"></div>
   
    <? if ($total_comment >= $common_comment_rows) { ?>
        <input type="button" id="comment_more" value="더보기.." class="btn btn-default" style="width:100%;" />
    <? } ?>
<? } //if ($total_comment != 0){ ?>




<script type="text/javascript"> 
//<![CDATA[

$(document).ready(function() { 

    
    <? if (isset($_SESSION['id'])){ ?> 
    $("#comment_form").click(function() {
        if( $("input#comment").val() ) { //댓글
            $.post('process/comment.php', 
            {	
                mode:"write",
                idx:<? echo trim($_GET['idx']) ?>,
                comment:$("input#comment").val()
            },
            function() {
                self.location.reload();
            });
        }else{
            alert("댓글을 입력하세요.");
        }
    });
    <? } ?>
    
    
    $(".comment_del").click(function() {
        if (window.confirm("댓글을 삭제하시겠습니까?")) {
            $.post('process/comment.php', { mode:"delete", idx:$(this).attr("idx") }, function(data){
				self.location.reload();	
            });
        }else{
            return false;
        }
    });
    
    
    function lastPostFunc(){
        $('div#lastPostsLoader').html('<center><em>Loading...</em></center>');
        $.get('comment.more.php', 
        {
            idx:<? echo trim($_GET['idx']) ?>,
            lastPostID:$(".wrdLatest:last").attr("idx")
        },	
        function(data){
            if (data != "") {
                $(".wrdLatest:last").after(data);			
            }
            $('div#lastPostsLoader').empty();
        });
    };  
    
    $("#comment_more").click(function(){
		lastPostFunc();
        return false;
    });	

});

//]]> 
</script>