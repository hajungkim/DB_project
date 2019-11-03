


		</div><!-- /.col-lg-9-->	
		<div class="col-lg-3">
			<ul class="list-group">					
		        <? if (isset($_SESSION['id'])){ ?>   
	                <a href="member.php" class="list-group-item<? echo (stristr($_SERVER[PHP_SELF], '/member.php') !== FALSE) ? " active" : "" ?>">정보수정</a>
	                <a href="logout.php" class="list-group-item">로그아웃</a>
                <? } ?>
             </ul>
             <? if ($_SESSION['level'] == 100){ ?>
             <ul class="list-group">	
				<a href="admin.php" class="list-group-item<? echo (stristr($_SERVER[PHP_SELF], '/admin.php') !== FALSE) ? " active" : "" ?>">관리자</a>				
             </ul>
             <? } ?>  
	    </div><!-- /.col-lg-3-->
	</div><!-- /.row-->
</div><!-- /.container -->


<div class="container" style="padding-top:30px;">
	<p class="text-center">Copyright ⓒ MUKKABI. All rights reserved.</p>
</div>





<script type="text/javascript"> 
//<![CDATA[

$(document).ready(function() { 

	<? if (!isset($_SESSION['id'])){ ?>
	$("#login_form").click(function() {
		if ( $("input#login_id").val() ) {
			if ( $("input#login_pwd").val() ) {
				$.post('process/login.php', { id:$("input#login_id").val(), pwd:$("input#login_pwd").val() }, function(data) {
					if (data == 0){
						alert("가입된 회원이 아닙니다. 다시 가입하세요.");
					}else if (data == 1){
						alert("비밀번호가 일치하지 않습니다. 다시 입력해주세요.");
					}else if (data == 2){
						alert("탈퇴한 아이디이므로 접근하실 수 없습니다.");
					}else if (data == 3){	
						self.location.href = '<? echo $common_path ?>';
					}
				});
			}else{
				alert("비밀번호을 입력하세요.");
			}
		}else{
			alert("아이디를 입력하세요.");
		}
	});	
	<? } ?>
	
	$("#search_form").click(function() {
		if ( $("input#search").val() ) {
			self.location.href = "<? echo $common_path."/board.php?table=".trim($_GET['table'])."&search=" ?>" + $("input#search").val();
		}else{
			alert("검색어를 입력하세요.");
		}
	});
	
	$("input#search").keydown(function(e){
		if(e.keyCode == 13){
			//$("#search_form").click();
			return false;
		}	
	});
			
}); 

//]]> 
</script>


<script src="js/bootstrap.min.js"></script>

</body>
</html>
