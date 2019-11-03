<?php
session_start();
header("content-type:text/html; charset=utf-8");
if (isset($_SESSION['id'])) die ("Error");
include_once ("lib/common.php");
include_once ("lib/dbconn.php");
include_once ("lib/function.php");
include_once ("head.php");
?>



<div class="row">
  <div class="col-lg-6 col-offset-3">
	<h1><strong>Login</strong></h1>
	<form class="form-horizontal">
	  <div class="form-group">
	    <div class="col-lg-10">
	      <input type="text" class="form-control" id="login_id" placeholder="아이디">
	    </div>
	  </div>
	  <div class="form-group">
	    <div class="col-lg-10">
	      <input type="password" class="form-control" id="login_pwd" placeholder="비밀번호">
	    </div>
	  </div>
	  <a href="#" id="login_form" class="btn btn-default">로그인</a>
	</form>

  </div>
</div>


<?php
 //if ($_GET['mode'] == "reply")   if ($_GET['mode'] == "modify")

include_once ("tail.php");
?>