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

$sql = "CREATE TABLE IF NOT EXISTS testtable1 (";
$sql = $sql."id varchar(10) NOT NULL,";
$sql .= "name varchar(50) NOT NULL,";
$sql .= "score int NOT NULL,";
$sql .= "PRIMARY KEY(id))";

$result = mysqli_query($connect, $sql);

if($result)
{
	echo "테이블 생성 성공<br>";
}
else
{
	echo "테이블 생성 실패<br>";
}

mysqli_close($connect);
?>
</html>