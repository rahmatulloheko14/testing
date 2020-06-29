<?php
error_reporting(0);
session_start();  
if($_SESSION['user']!=''){
	header("Location:home.php");
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Register</title>
	<link rel="stylesheet" type="text/css" href="css/styles2.css">
</head>
<body>
	<div id="content">
		<center><h2><u>Daftar dengan PDO</u></h2></center>
		<form action="register.php" method="POST" class="kotak">
			<div class="tengah">
				<label>Email <br>
					<input name="user" type="email" onchange="cekuser()" required autocomplete="float" />
				</label>
				<br/><div class="pesan"></div>

				<label>ktp <br>
					<input name="ktp" type="text"/>
				</label><br/>
				
				<label>Password <br>
					<input name="pass" type="password" required />
				</label><br/>
				<label>Nama <br>
					<input name="nama" type="text"/>
				</label><br/>
				<button name="submit">Register</button>
			</div>  
		</form>
	</div>

	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<script type="text/javascript">
		var user="";

		var cekuser = function(){
			var user = $("input[name=user]").val();
			// alert(user);		
			if (user!="") {
				$('.pesan').html("Sedang mencari.....");
				$.ajax({
					url: "cekuser.php",
					type: 'GET',
					data: {user : user},
					success:function(data){

						// alert(data);
						$('.pesan').html('');
						if(data=="1"){
							$('.pesan').html('<h5 class="warning merah"> * email <b>'+user+'</b> Tidak Tersedia / telah terdaftar </h5>');
						}else{
							$('.pesan').html('<h5 class="warning hijau">* email Tersedia ✔ </h5>');
						}
					},
					error: function(e){
						$('.pesan').html('koneksi bermasalah');
					}
					
				});
			}
		}	
		
	</script>
	

	<?php
	if(isset($_POST['submit'])){
		include("config.php");
		if(isset($_POST['user']) && isset($_POST['pass'])){
			$password=$_POST['pass'];
			$ktp = $_POST['ktp'];
			$sql=$dbh->prepare("SELECT COUNT(*) FROM `jc_account` WHERE `account_email`=?");
			$sql->execute(array($_POST['user']));

			if($sql->fetchColumn()!=0){
				die("User Exists");
			}else{        
				$p_salt = rand_string(20); 
				$site_salt="yogyakarta"; /*Common Salt used for password storing on site.*/
				$salted_hash = hash('sha256', $password.$site_salt.$p_salt);
				$nama = $_POST['nama'];
				$email = $_POST['user'];
					date_default_timezone_set("Asia/Jakarta");
				$tgl = date("Y-m-d H:i:s");
				$token=hash('sha256', md5($tgl)) ;
				$aktif=0;
				$sql=$dbh->prepare("INSERT INTO `jc_account` 
					(`id`, `account_email`, `account_ktp`, `account_pass`, `psalt`, `account_name`,`account_date`, `account_token`) VALUES 
					
					(null, :email, :ktp, :pass, :psalt, :nama, :tgl, :token);");					
					// -- (NULL, ?, ?, ?, ?, ?, ?);");      
				$sql->bindParam(':email',$email);
				$sql->bindParam(':ktp',$ktp);
				$sql->bindParam(':pass',$salted_hash);
				$sql->bindParam(':psalt',$p_salt);
				$sql->bindParam(':nama',$nama);
				$sql->bindParam(':tgl',$tgl);
				$sql->bindParam(':token',$token);
				// $sql->bindParam(':aktif',$aktif);

				$sql->execute();
				include("mail.php");
				// $sql->execute(array($_POST['user'], $ktp, $salted_hash, $p_salt, $nama, $tgl));
				if($sql){
					echo "Successfully Registered.";
				}else {
					echo "gagal";	# code...
				}
				// echo "Successfully Registered.";
			}
		}
	}

	function rand_string($length) {
		/* http://subinsb.com/php-generate-random-string */
		$str="";
		$chars = "yogyakartaabcdefghijklmanopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$size = strlen($chars);
		for($i = 0;$i < $length;$i++) {
			$str .= $chars[rand(0,$size-1)];
		}
		return $str;  
	}
	?>
	<!-- <br> -->
	<center><a href="index.php"><br>login</a></center>
	
</body>
</html>
