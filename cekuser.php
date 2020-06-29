<?php 
include "config.php";

$username = "";

if(isset($_GET['user'])){
	// $username = mysqli_real_escape_string($conn, $_GET['user']);
	// $sql = "SELECT * FROM jc_account WHERE account_email LIKE '%$username%'";
	// $process = mysqli_query($conn, $sql);
	// $num = mysqli_num_rows($process);
	$user = $_GET['user'];
	$sql=$dbh->prepare("SELECT COUNT(account_email) as totaluser FROM `jc_account` WHERE `account_email` like '$user'");
	$sql->execute(array($user));
	$row = $sql->fetch();
	// echo $row['account_email'];
	$num = $row['totaluser'];
}
// echo $user."<br>";
// echo $num;
if($num == 0){
	echo "0"; 
}else{
	echo "1";
}

// echo $sql;

?>