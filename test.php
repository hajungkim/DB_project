<?php
$num1 = 1;
$num2 = 5;

echo "Hello world<br><br>";
echo "num1 = $num1<br>";
echo "num2 = $num2<br><br>";

function sum($n1, $n2)
{
	return $n1 + $n2;
}

$sum = sum($num1, $num2);

echo "num1 + num2 = $sum<br>";

$conn = mysqli_connect("210.117.181.22", "s201655082", "database1!", "s201655082");

if(mysqli_connect_error($conn))
{
	echo "Fail, ".mysqli_error()."<br>";
}
else
{
	echo "Success<br>";
}
?>