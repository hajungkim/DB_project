<html>

<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>

<body>

<?
$connect = mysqli_connect('210.117.181.22', 's201517014', 'rla11256++');

if($connect)
{
	echo "mysql 서버와 연결되었습니다.<br>";
	echo "<hr>";
}
else
{
	echo "<br>";
	echo "mysql 접속 실패";
}

$db = mysqli_select_db($connect, 's201517014');

if($db)
{
	echo "데이터베이스 선택 성공";
	echo "<hr>";
}
else
{
	echo "<hr>";
	echo "데이터베이스 선택 실패";
}

mysqli_query($connect, "set names utf8");

$sql = "INSERT INTO testtable1 VALUES";
$sql = "('soo0131', '철수', 200)";

$result = mysqli_query($connect, $sql);

if($result)
{
	echo "데이터 삽입 성공<br>";
}
else
{
	echo "데이터 삽입 실패<br>";
}

mysqli_close($connect);
?>
<html>