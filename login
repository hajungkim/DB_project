<?
include_once("session.php");

if($_SESSION[ID] != ""){
	echo "
	<script>
	location.replace('login_success.php');
	</script>
	";
	die;
}
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="list.css">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<br>
<br>
<table width="500" border="0" align="center" cellpadding="5" cellspacing="0" bgcolor="#EBDBF2" style="border:0px #333333 solid;border-top-width:3px;">
	<tr>
		<td><b>�α���</b></td>
		<td align="right">&nbsp;</td>
	<tr>
</table>
<br>
<table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center">������ ������ ���� ȸ������ �����ϼ���.<a href="add_form_ex.php">
		[ȸ������]</a></td>
	</tr>
</table>
<br>
<form action="login_engine.php" method="post" name="login_form" id="login_form" style="margin:0px;" onSubmit="return checkForm(this.form);">
	<table width="500" border="0" align="center" cellpadding="5" cellspacing="1">
		<tr>
			<td width="195" align="right" bgcolor="#EFD8F1">&nbsp;&nbsp;
			<font color="#FF0000">*</font>
			&nbsp;���̵�</td>
			<td width="282" bgcolor="E8E8E8">
				<input name="ID" id="ID" type="text" size="20" style="border:1px #333333 solid; width:100px;height:20px;">
			</td>
		</tr>
		<tr>
			<td align="right" bgcolor="EFD8F1"> &nbsp;&nbsp;<font color="#FF0000">*</font>
				&nbsp;��ȣ</td>
			<td bgcolor="#E8E8E8">
				<input name="password" id="password" type="password" size="20" style="border:1px #333333 solid; width:100px;height:20px;">
			</td>
		</tr>
	</table>
	<br>
	<table  width="500" height="40" border="0" align="center" cellpadding="5" cellspacing="1" bgcolor="#EBDBF2" style="border:0px #333333 solid;border-bottom-width:3px;">
		<tr>
			<td align="center">
				<input type="submit" name="Submit" value="�α���">
				<input type="reset" name="Reset" value="���">
			</td>
		</tr>
	</table>
</form>

<script>
function checkForm(theForm){
	if(theForm.ID.value==""){
		alert("���̵� �Է��Ͻʽÿ�.");
		theForm.ID.focus();
		return false;
	} else {
		return true;
	}
}
</script>
</body>
</html>