<?php
session_start();
header("content-type:text/html; charset=utf-8");
include_once ("lib/common.php");
include_once ("lib/dbconn.php");
include_once ("lib/function.php");
if (!isset($_SESSION['id'])) alert('로그인을 하셔야 이용하실 수 있습니다.', $common_path);
include_once ("head.php");
?>


<h1><strong>Information</strong></h1>

<?php
//정보수정
if (isset($_SESSION['id'])){ 
	$sql = "select * from qboard_member where qm_user ='".trim($_SESSION['id'])."'";
	$result = mysql_query($sql, $connect);
	$row = mysql_fetch_array($result);
	$email = $row['qm_email'];
	$date = str_replace("-", ".", substr($row['qm_date'], 2));
	$date_period = get_date_period(substr($row['qm_date'], 0, 10)); //($date_period일전)
	$date_period = get_date_period($row['qm_date']); //($date_period일전)
	$updated_date = str_replace("-", ".", substr($row['qm_updated_date'], 2));
	$ip = ip_hidden($row['qm_ip']);
	$profile = htmlspecialchars($row['qm_profile']);
}
?>


<form>
<table class="table">
  <tr>
    <td width="180" align="right"><strong>아이디 : </strong></td>
    <td><? echo $_SESSION['id'] ?></td>
  </tr>
  <tr>
    <td align="right"><strong>가입일 : </strong></td>
    <td><? echo $date." (".$date_period."일전)" ?></td>
  </tr>
  <tr>
    <td align="right"><strong>최근접속일 : </strong></td>
    <td><? echo $updated_date ?></td>
  </tr>
  <tr>
    <td align="right"><strong><sup>*</sup>비밀번호 : </strong></td>
    <td><input type="password" id="member_modify_pwd" maxlength="20" class="form-control" placeholder="비밀번호" style="width:400px;" /></td>
  </tr>
  <tr>
    <td align="right"><strong><sup>*</sup>비밀번호 확인 : </strong></td>
    <td><input type="password" id="member_modify_pwd_confirm" maxlength="20" class="form-control" placeholder="비밀번호 확인" style="width:400px;" /></td>
  </tr>
  <tr>
    <td align="right"><strong><sup>*</sup>e-메일 : </strong></td>
    <td><input type="text" id="member_modify_email" maxlength="200" class="form-control" placeholder="e-메일" value="<? echo $email ?>" /></td>
  </tr>
  <tr>
    <td align="right"><strong>프로필 : </strong></td>
    <td><textarea id="member_modify_profile" maxlength="300" class="form-control" rows="3" placeholder="프로필" /><? echo $profile ?></textarea></td>
  </tr>
  <tr>
    <td align="right"><strong>IP : </strong></td>
    <td><? echo $ip ?></td>
  </tr>
  <tr>
    <td colspan="2" align="right">
    	<input type="button" id="member_modify_form" value="정보수정" class="btn btn-default" />
    	<input type="button" id="member_leave" value="회원탈퇴" class="btn" /></td>
  </tr>
</table>
</form>


<script type="text/javascript"> 
//<![CDATA[

$(document).ready(function() { 

	var re_pw = /^[a-z0-9_]{4,20}$/; //비밀번호 검사식 
	var re_mail = /^([\w\.-]+)@([a-z\d\.-]+)\.([a-z\.]{2,6})$/; //이메일 검사식
	
	$("#member_modify_form").click(function() {
		if(re_pw.test($("input#member_modify_pwd").val()) == true) { //비밀번호 검사 
			if ( $("input#member_modify_pwd").val() == $("input#member_modify_pwd_confirm").val() ){ //비밀번호 확인
				if(re_mail.test($( "input#member_modify_email" ).val()) == true) { //이메일 검사
					$.post('process/member.php', 
					{
						mode:"modify", 
						pwd:$("input#member_modify_pwd").val(),
						pwd_confirm:$("input#member_modify_pwd_confirm").val(),
						email:$( "input#member_modify_email" ).val(), 
						profile:$( "textarea#member_modify_profile" ).val()
					},
					function(data) {
						alert("변경하셨습니다.");
						self.location.reload();
					});
				}else{
					alert("이메일 주소가 잘못되었습니다.\n다시 입력해주세요.");
				}
			}else{
				alert("비밀번호가 일치하지 않습니다.\n다시 입력해주세요.");  	
			}	
		}else{
			alert("비밀번호을 입력하세요.");
		}
	});
	
	$("#member_leave").click(function() {
		var password = prompt('회원 탈퇴하시겠습니까?\n비밀번호 입력');
		if (password != null){
			$.post('process/member.php',{ mode:"leave", pwd:password }, function(data) {
				if (data == 0){
					alert("비밀번호을 입력하세요.");
					$("#member_leave").click();
				}else if (data == 1){
					alert("비밀번호가 일치하지 않습니다.\n다시 입력해주세요.");
					$("#member_leave").click();
				}else if (data == 2){
					alert("관리자 아이디는 접근 불가합니다.");
				}else if (data == 3){
					alert("정상적으로 탈퇴가 되었습니다.");
					self.location.href = '<? echo $common_path ?>';
				}
			});
		}	
	});

});

//]]> 
</script>
