<?php
session_start();
header("content-type:text/html; charset=utf-8");
if (isset($_SESSION['id'])) die ("Error");
include_once ("lib/common.php");
include_once ("lib/dbconn.php");
include_once ("lib/function.php");
include_once ("head.php");
?>


<h1><strong>회원가입</strong></h1>


<form>
<table class="table">
  <tr>
    <td width="180" align="right"><strong><sup>*</sup>아이디 : </strong></td>
    <td><input type="text" id="register_id" maxlength="20" class="form-control" placeholder="아이디" style="width:400px;" /></td>
  </tr>
  <tr>
    <td align="right"><strong><sup>*</sup>비밀번호 : </strong></td>
    <td><input type="password" id="register_pwd" maxlength="20" class="form-control" placeholder="비밀번호" style="width:400px;" /></td>
  </tr>
  <tr>
    <td align="right"><strong><sup>*</sup>비밀번호 확인 : </strong></td>
    <td><input type="password" id="register_pwd_confirm" maxlength="20" class="form-control" placeholder="비밀번호 확인" style="width:400px;" /></td>
  </tr>
  <tr>
    <td align="right"><strong><sup>*</sup>e-메일 : </strong></td>
    <td><input type="text" id="register_email" maxlength="200" class="form-control" placeholder="e-메일" ></td>
  </tr>
  <tr>
    <td align="right"><strong>프로필 : </strong></td>
    <td><textarea id="register_profile" maxlength="500" class="form-control" rows="3" placeholder="프로필" /></textarea></td>
  </tr>
  <tr>
    <td colspan="2" align="right"><input type="button" id="register_form" value="회원가입" class="btn btn-default" /></td>
  </tr>
</table>
</form>



<script type="text/javascript"> 
//<![CDATA[

$(document).ready(function() { 

	var re_id = /^[a-z0-9_]{4,20}$/; //아이디 검사식 
	var re_pw = /^[a-z0-9_]{4,20}$/; //비밀번호 검사식 
	var re_mail = /^([\w\.-]+)@([a-z\d\.-]+)\.([a-z\.]{2,6})$/; //이메일 검사식
	
	$("#register_form").click(function() {
		if (re_id.test($("input#register_id").val()) == true) { //아이디 검사
			if (re_pw.test($("input#register_pwd").val()) == true) { //비밀번호 검사 
				if ($("input#register_pwd").val() == $("input#register_pwd_confirm").val()){ ////비밀번호 확인
					if (re_mail.test($( "input#register_email" ).val()) == true) { //이메일 검사
						$.post('process/register.php',
						{
							id:$("input#register_id").val(),
							pwd:$("input#register_pwd").val(),
							pwd_confirm:$("input#register_pwd_confirm").val(),
							email:$( "input#register_email" ).val(),
							profile:$( "textarea#register_profile" ).val()
						},
						function(data) {
							//alert(data);
							if (data == 0){
								alert("이미 사용중인 아이디 입니다.");
							}else if (data == 1){
								alert("탈퇴한 아이디이므로 접근하실 수 없습니다.");	
							}else if (data == 2){
								alert("회원가입을 진심으로 축하합니다.\n회원님의 패스워드는 암호화 코드로 저장되므로\n안심하셔도 좋습니다.\n회원의 탈퇴는 언제든지 가능하며 탈퇴 후,\n회원님의 모든 소중한 정보는 삭제하고 있습니다.\n고맙습니다.");
								self.location.href = '<? echo $common_path ?>';
								//self.location.reload();
							}
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
		}else{
			alert("아이디를 입력하세요.\n(소문자, 숫자, _만. 4~20자 이내)");
		}
	});

});

//]]> 
</script>




