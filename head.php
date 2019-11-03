<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo $common_title ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="css/bootstrap.css" rel="stylesheet" media="screen">

<style type="text/css">
body {
	padding-top: 150px;
}

</style>


</head>

<body>
	<div class="navbar navbar-inverse navbar-fixed-top">
	
	<div class="container">
	 
	  </button>
	  <a class="navbar-brand" href="<? echo $common_path ?>"><? echo $common_title ?></a>
	  
	    <ul class="nav navbar-nav pull-right">
	        <? if (!isset($_SESSION['id'])){ ?>
				 <li><a href="register.php">회원가입</a></li>
				 <li><a href="login.php">로그인</a></li>
			<? }else{ ?>
				 <li class="dropdown">
			        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><? echo $_SESSION['id'] ?> <b class="caret"></b></a>
			        <ul class="dropdown-menu">
			            <? if ($_SESSION['level'] == 100){ ?>
		                	<li><a href="admin.php">관리자</a></li>
		                	<li class="divider"></li>
		                <? } ?>    
		                <li><a href="member.php">정보수정</a></li>
		                <li><a href="logout.php">로그아웃</a></li>
			        </ul>
			     </li>	
			 <? } ?>      	
	     </ul>
	  
	  
	</div><!-- /.container -->
</div><!-- /.navbar -->	
	

<div class="container">
	<div class="row">
	
