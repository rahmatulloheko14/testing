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