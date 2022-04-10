<center>

<?php require_once("dahili.php");

@$buton = $_POST["girisbuton"];
@$kulad = $_POST["ad"];
@$sifre = $_POST["sifre"];
$kulad2 = $kulad;
if ($buton):
	$kulad = md5(sha1(md5($kulad)));	$sifre = md5(sha1(md5($sifre)));

	$giriskontrol = "select * from kullanici where kulad='$kulad' and kulsifre='$sifre'";

	$gk = $db->prepare($giriskontrol);
	$gk->execute();
	$son = $gk->get_result();

	if ($son->num_rows != 0):


		setcookie("kulad", $kulad2, time() + 60 * 60 * 24);
		setcookie("sifre", $sifre, time() + 60 * 60 * 24);

		echo "<BR><BR><h3>GİRİŞ BAŞARILI</h3>";
		header("refresh:2,url=index.php");

	else:

		echo "<BR><BR><h3>GİRİŞ BAŞARISIZ</h3>";
		header("refresh:2,url=index.php");
	endif;



else:
	echo "<BR><BR><h3>HATA OLUŞTU</h3>";
	header("refresh:2,url=index.php");
endif;
?>
</center>