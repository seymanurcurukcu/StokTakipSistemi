<?php


$db = new mysqli("localhost", "root", "", "stok_takip") or die("Bağlanamadı");
$db->set_charset("utf8");


class stok
{

	function kategoricek($veri)
	{

		$kuldeger = $this->kulal($veri);

		if ($kuldeger["yetki"] == 1):
			$sorgum = "select * from kategori";
			$gel = $veri->prepare($sorgum);
			$gel->execute();
			$b = $gel->get_result();
			echo '<div class="col-md-12">';
			while ($kategorison = $b->fetch_assoc()):

				echo '<a  class="btn btn-dark" id="linkim"  href="index.php?hareket=kategori&id=' . $kategorison["id"] . '" >' . $kategorison["ad"] . ' </a>';

			endwhile;
			echo '</div>';

		elseif ($kuldeger["yetki"] == 2):

			$talep = "select * from talepler";
			$ta = $veri->prepare($talep);
			$ta->execute();
			$satir = $ta->get_result();

			echo '<a  class="btn btn-dark" id="linkim"  href="index.php?hareket=talepler" >
			
			Stok Talepler <span class="badge badge-light">' . $satir->num_rows . '</span></a>';


		endif;


	} // kategori çek

	function kulal($v)
	{

		$kulsif = $_COOKIE["sifre"];
		$kul = "select * from kullanici where kulsifre='$kulsif'";
		$so = $v->prepare($kul);
		$so->execute();
		$s = $so->get_result();
		return $sonartik = $s->fetch_assoc();


	} // kullanıcı bilgisi çekiyor		



	function talepgonder($v)
	{
		$buton = $_POST["guncel"];
		$urunad = $_POST["urunad"];
		$urunid = $_POST["urunid"];
		$stok = $_POST["stok"];
		$tarih = date("d-m-Y");

		if ($buton):

			$ekle = "insert into talepler (urunid,urunad,talepstok) VALUES ($urunid,'$urunad',$stok)";
			$ekle2 = $v->prepare($ekle);
			$ekle2->execute();
			echo '<div class="col-md-12 text-center" style="margin-top:20px;">
					<div class="alert alert-info">		
						TALEP İLETİLDİ
					</div>
					</div>';


			header("refresh:2,url=index.php");



		else:
			echo '<div class="col-md-12 text-center" style="margin-top:20px;">
					<div class="alert alert-danger">		
						HATA OLUŞTU
					</div>
					</div>';


			header("refresh:2,url=index.php");

		endif;


	}

	function stokhareket($ver, $urunad, $urunid, $urunstok)
	{

		$sel = "select * from ayarlar";
		$cek = $ver->prepare($sel);
		$cek->execute();
		$son = $cek->get_result();
		$urunlerson = $son->fetch_assoc();

		if ($urunlerson["durum"] == 1):

			echo '  <form action="index.php?hareket=talepgonder" method="post">
  
 <input name="stok" type="text" style="width:50px; height:30px; margin-top:5px;" />
  <input name="urunad" type="hidden" value="' . $urunad . '"/>
    <input name="urunid" type="hidden" value="' . $urunid . '"/>
  <input name="guncel" type="submit" class="btn btn-outline-info btn-sm" style="margin-top:5px; margin-bottom:5px;" value="+"  />
   </form>';


		else:


			// ilk önce talep varmı bakıyorum

			$sel2 = "select * from talepler where urunid=$urunid";
			$cek2 = $ver->prepare($sel2);
			$cek2->execute();
			$son2 = $cek2->get_result();


			if ($son2->num_rows == 0):


				if ($urunstok <= 1000):

					$ekle = "insert into talepler (urunid,urunad,talepstok) VALUES ($urunid,'$urunad',500)";
					$ekle2 = $ver->prepare($ekle);
					$ekle2->execute();






				endif;





			endif;





		endif;





	}



	function varsayilan($verim)
	{

		$kuldeger = $this->kulal($verim);

		$sel = "select COUNT(*) AS toplam from urunler";
		$cek = $verim->prepare($sel);
		$cek->execute();
		$son = $cek->get_result();
		$urunlerson = $son->fetch_assoc();




		
$gosterilecekadet = 6;
		$toplam_icerik = $urunlerson['toplam'];



		
$toplam_sayfa = ceil($toplam_icerik / $gosterilecekadet);
		$sayfa = isset($_GET["hareket"]) ? (int)$_GET["hareket"] : 1;
		if ($sayfa < 1)
			$sayfa = 1;
		if ($sayfa > $toplam_sayfa)
			$sayfa = $toplam_sayfa;

		$limit = ($sayfa - 1) * $gosterilecekadet;


		$sel2 = "select * from urunler LIMIT $limit,$gosterilecekadet";
		$cek2 = $verim->prepare($sel2);
		$cek2->execute();
		$son2 = $cek2->get_result();




		if ($kuldeger["tercih"] == 1):




			while ($urunlerson2 = $son2->fetch_assoc()):
?>	
        
        <div class="col-md-1 table-bordered" style="border-radius:10px" id="kutuliste">        
        <div class="row" style="text-align:center">
        
        <div class="col-md-12 border-bottom " ><strong><?php echo $urunlerson2["ad"]; ?></strong></div> 
        
          <div class="col-md-12 border-bottom"><b class="text-danger">S: </b><?php echo $urunlerson2["stok"]; ?></div> 
          
        <div class="col-md-12">
        <form action="index.php?hareket=urunguncelle" method="post"> 
   <input type="hidden" name="urunid" value="<?php echo $urunlerson2["id"]; ?>" />     
 <input name="guncel" type="submit" class="btn btn-outline-dark btn-sm" value=">" style="margin-bottom:3px; margin-top:3px; " />
  </form>  
   </div>     
        
        
         <div class="col-md-12 border-top " >
  
<?php $this->stokhareket($verim, $urunlerson2["ad"], $urunlerson2["id"], $urunlerson2["stok"]); ?>
   
   </div> 
        
        
        </div>        
        
        </div>
        
 
        
        
        <?php

			endwhile;

?>
        
            <div class="container table-bordered table-dark text-center">
<!-- SAYFALAMA BÖLÜM -->
<div class="row" style="min-height:30px;">
		<div class="col-md-12">
<?php

			for ($s = 1; $s <= $toplam_sayfa; $s++):

				if ($sayfa == $s):

					echo $s . ' ';
				else:

					echo '<a href ="?hareket=' . $s . '">' . $s . '</a>';

				endif;

			endfor;


?>
        
        </div>		
</div>   
        
        <?php

		else:

?><br />
		     <table class="table table-hover text-center">
    <thead>
      <tr  style="background-color:#ECECEC">
        <th>Ürün Adı</th>
        <th>Stok Durumu</th>
        <th colspan="2">İşlem</th>
      </tr>
    </thead>
    
    <tbody>
        
		<?php
			while ($urunlerson2 = $son2->fetch_assoc()):
?>	
        
   
    
      <tr>
        <td><?php echo $urunlerson2["ad"]; ?></td>
        <td><?php echo $urunlerson2["stok"]; ?></td>
        <td>
        <form action="index.php?hareket=urunguncelle" method="post"> 
   <input type="hidden" name="urunid" value="<?php echo $urunlerson2["id"]; ?>" />     
 <input name="guncel" type="submit" class="btn btn-outline-dark btn-sm" value=">" style="margin-bottom:3px; margin-top:3px; " />
  </form>
  
  
   </td>
  <td> <?php $this->stokhareket($verim, $urunlerson2["ad"], $urunlerson2["id"], $urunlerson2["stok"]); ?></td>
 
  
      </tr>


        
        <?php

			endwhile;

			echo '   </tbody>
  </table>';

?>
		    <div class="container table-bordered table-dark text-center">
<!-- SAYFALAMA BÖLÜM -->
<div class="row" style="min-height:30px;">
		<div class="col-md-12">
<?php

			for ($s = 1; $s <= $toplam_sayfa; $s++):

				if ($sayfa == $s):
					echo $s . ' ';
				else:
					echo '<a href ="?hareket=' . $s . '">' . $s . '</a>';
				endif;

			endfor;


?>
        
        </div>		
</div>   
		<?php
		endif;


	} // default ürünler

	function kategoriyegore($verim, $katid)
	{


		$kuldeger = $this->kulal($verim);


		$sel = "select * from urunler where katid=$katid";
		$cek = $verim->prepare($sel);
		$cek->execute();
		$son = $cek->get_result();

		if ($kuldeger["tercih"] == 1):

			while ($urunlerson = $son->fetch_assoc()):
?>	
        
        <div class="col-md-1 table-bordered" style="border-radius:10px" id="kutuliste">        
        <div class="row" style="text-align:center">
        
        <div class="col-md-12 border-bottom " ><strong><?php echo $urunlerson["ad"]; ?></strong></div> 
        
          <div class="col-md-12 border-bottom"><b class="text-danger">S: </b><?php echo $urunlerson["stok"]; ?></div> 
          
        <div class="col-md-12">
        <form action="index.php?hareket=urunguncelle" method="post"> 
   <input type="hidden" name="urunid" value="<?php echo $urunlerson["id"]; ?>" />     
 <input name="guncel" type="submit" class="btn btn-outline-dark btn-sm" value=">" style="margin-bottom:3px; margin-top:3px; " />
  </form>  </div>     
        
                 <div class="col-md-12 border-top " >
  
<?php $this->stokhareket($verim, $urunlerson["ad"], $urunlerson["id"], $urunlerson["stok"]); ?>
   
   </div> 
        
        
        </div>        
        
        </div>
        
        
        <?php

			endwhile;

		else:

?>
		     <table class="table table-hover text-center">
    <thead>
      <tr style="background-color:#ECECEC">
        <th>Ürün Adı</th>
        <th>Stok Durumu</th>
        <th colspan="2">İşlem</th>
      </tr>
    </thead>
    
    <tbody>
        
		<?php
			while ($urunlerson = $son->fetch_assoc()):
?>	
        
   
    
      <tr>
        <td><?php echo $urunlerson["ad"]; ?></td>
        <td><?php echo $urunlerson["stok"]; ?></td>
        <td>
        <form action="index.php?hareket=urunguncelle" method="post"> 
   <input type="hidden" name="urunid" value="<?php echo $urunlerson["id"]; ?>" />     
 <input name="guncel" type="submit" class="btn btn-outline-dark btn-sm" value=">" style="margin-bottom:3px; margin-top:3px; " />
  </form> </td>
  
    <td> <?php $this->stokhareket($verim, $urunlerson["ad"], $urunlerson["id"], $urunlerson["stok"]); ?></td>
      </tr>


        
        <?php

			endwhile;

			echo '   </tbody>
  </table>';


		endif;


	} // kategori kontrol	

	function urunguncellegit($a)
	{
		@$buton = $_POST["guncel"];
		@$urunid = $_POST["urunid"];

		if ($buton):

			$sorgum = "select * from urunler where id=$urunid";
			$sorgu = $a->prepare($sorgum);
			$sorgu->execute();
			$b = $sorgu->get_result();
			$sorguson = $b->fetch_assoc();

?>
                    
                    <div class="col-md-4"></div>
                    
                    	<div class="col-md-4 table-bordered table-light" style="margin-top:50px; border-radius:10px;"> 
                               
        <div class="row" style="text-align:center">
        <div class="col-md-12"><strong><?php echo $sorguson["ad"]; ?></strong></div> 
        
          <div class="col-md-12"><b class="text-danger">  <form action="index.php?hareket=urunguncelleson" method="post"> 
          Adet : <input name="stok" type="text" /> </b></div> 
          
        <div class="col-md-12">
         <input type="hidden" name="mevcutstok" value="<?php echo $sorguson["stok"]; ?>" /> 
        <input type="hidden" name="urunidson" value="<?php echo $sorguson["id"]; ?>" /> <br />  
        <input name="guncelson" type="submit" class="btn btn-outline-success" value="Güncelle" style="margin-bottom:3px; " /></form></div> 
               
        </div>
        
        
        
        </div>
                    
         <div class="col-md-4"></div>        
                    
                    <?php


		else:
			echo '<div class="col-md-12 text-center" style="margin-top:20px;">
		<div class="alert alert-danger">
		
				HATA OLUŞTU
		</div>
		</div>';



			header("refresh:2,url=index.php");
		endif;




	} // guncelleme son

	function guncelson($c)
	{
		@$guncelson = $_POST["guncelson"];
		@$urunidson = $_POST["urunidson"];
		@$mevcutstok = $_POST["mevcutstok"];
		@$stok = $_POST["stok"];
		$sonstok = $mevcutstok - $stok;

		if ($guncelson):

			$gun = "update urunler set stok=$sonstok where id=$urunidson";
			$gunson = $c->prepare($gun);
			$gunson->execute();


			echo '<div class="col-md-12 text-center" style="margin-top:20px;">
		<div class="alert alert-success">
		
				BAŞARIYLA GÜNCELLENDİ
		</div>
		</div>';

			header("refresh:2,url=index.php");


		else:
			echo '<div class="col-md-12 text-center" style="margin-top:20px;">
		<div class="alert alert-danger">
		
				HATA OLUŞTU
		</div>
		</div>';
			header("refresh:2,url=index.php");

		endif;


	} // guncel son

	function linkkontrol($dveri)
	{

		$kuldeger = $this->kulal($dveri);

		if ($kuldeger["yetki"] == 1):
?>        
     <li class="nav-item" id="islem">
    <a class="nav-link btn btn-outline-warning" href="index.php">Anasayfa</a>
    </li>
  
  <li class="nav-item" id="islem">
    <a class="nav-link btn btn-outline-warning" href="index.php?hareket=sifredegistir">Şifre Değiştir</a>
  </li>
  
  <li class="nav-item" id="islem">
    <a class="nav-link btn btn-outline-warning"  href="index.php?hareket=cikis">Çıkış</a>
  </li>
        
        <?php
		else:

?>
        
		<li class="nav-item" id="islem">
    <a class="nav-link btn btn-outline-warning" href="index.php">Anasayfa</a>
    </li>             
  <li class="nav-item"  id="islem">
    <a class="nav-link btn btn-outline-warning" href="index.php?hareket=islemler">İşlemler</a>
  </li>
  <li class="nav-item" id="islem">
    <a class="nav-link btn btn-outline-warning" href="index.php?hareket=sifredegistir">Şifre Değiştir</a>
  </li>
  <li class="nav-item" id="islem">
    <a class="nav-link btn btn-outline-warning"  href="index.php?hareket=cikis">Çıkış</a>
  </li>
			
            
            <?php
		endif;


	} // link kontrol	

	function sifredegistir($gf)
	{

		@$buton = $_POST["girisbuton"];
		@$eskisifre = $_POST["eskisifre"];
		@$yen1 = $_POST["yen1"];
		@$yen2 = $_POST["yen2"];
		@$ckulsif = $_COOKIE["sifre"];
		if (!$buton):


?>        
        
        <div class="col-md-12" ><form action="index.php?hareket=sifredegistir" method="post"><strong>Eski şifre</strong> <br /><input name="eskisifre" type="password" placeholder="Eski Şifreni Gir" required="required"  /></div> 
        
          <div class="col-md-12"><strong>Yeni Şifre</strong><br /><input name="yen1" type="password"  placeholder="Yeni Şifre" required="required"   /></div> 
           <div class="col-md-12"><strong>Yeni Şifre Tekrar</strong><br /><input name="yen2" type="password" placeholder="Yeni Şifre Tekrar" required="required"  /></div> 
          
        <div class="col-md-12"><br />
        <input name="girisbuton" type="submit" class="btn btn-outline-success" value="DEĞİŞTİR" style="margin-bottom:3px; " />
        </form>
        </div>  
              
     
        <?php

		else:

			$eskisifre = md5(sha1(md5($eskisifre)));


			if ($ckulsif != $eskisifre):

				echo '<div class="col-md-12 text-center" style="margin-top:20px;">
		<div class="alert alert-danger">
		
				ESKİ ŞİFRE HATALI
		</div>
		</div>';


				header("refresh:2,url=index.php?hareket=sifredegistir");

			elseif ($yen1 != $yen2):

				echo '<div class="col-md-12 text-center" style="margin-top:20px;">
						<div class="alert alert-danger">		
							YENİ ŞİFRELER UYUMSUZ
						</div>
						</div>';


				header("refresh:2,url=index.php?hareket=sifredegistir");


			else:



				$kuldeger = $this->kulal($gf);

				$kulid = $kuldeger["id"];

				$yen1 = md5(sha1(md5($yen1)));

				setcookie("kulad", $_COOKIE["kulad"], time() + 60 * 60 * 24);
				setcookie("sifre", $yen1, time() + 60 * 60 * 24);

				$gunsor = "update kullanici set kulsifre='$yen1' where id=$kulid";
				$g = $gf->prepare($gunsor);
				$g->execute();

				echo '<div class="col-md-12 text-center" style="margin-top:20px;">
						<div class="alert alert-success">		
							ŞİFRE DEĞİŞTİRME BAŞARILI
						</div>
						</div>';

				header("refresh:2,url=index.php");

			endif;







		endif;

	} // şifre değiştir

	function cikis()
	{


		setcookie("kulad", $_COOKIE["kulad"], time() - 10);
		setcookie("sifre", $_COOKIE["sifre"], time() - 10);
		echo '<div class="col-md-12 text-center" style="margin-top:20px;">
						<div class="alert alert-danger">		
							ÇIKIŞ YAPILDI
						</div>
						</div>';
		header("refresh:2,url=index.php");

	} // çıkış 

	function tercihkontrol($g)
	{

		$kuldeger = $this->kulal($g);


		if ($kuldeger["yetki"] == 1):
?>
		
		
		       <form class="form-inline" action="index.php?hareket=tercih" method="post">
  <label class="my-1 mr-2" for="inlineFormCustomSelectPref">Listeleme</label>
  <select class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref" name="tercih">
<?php


			if ($kuldeger["tercih"] == 1):

				echo '<option value="1" selected="selected">Kutu</option>
  			  <option value="2">Liste</option>';

			else:

				echo '<option value="2" selected="selected">Liste</option>
					<option value="1" >Kutu</option>
  			  ';

			endif;
?>
        
             </select>
  <input type="submit" class="btn btn-sm btn-outline-success" name="buttonuy"  value="Uygula">
</form>
		<?php


		endif;


	} // tercih knotrol	

	function tercihguncelle($r)
	{

		@$buton = $_POST["buttonuy"];
		@$tercih = $_POST["tercih"];

		if ($buton):



			$kuldeger = $this->kulal($r);

			$kulid = $kuldeger["id"];


			$gun = "update kullanici set tercih=$tercih where id=$kulid";
			$s = $r->prepare($gun);
			$s->execute();

			echo '<div class="col-md-12 text-center" style="margin-top:20px;">
						<div class="alert alert-success">		
							TERCİH GÜNCELLENDİ
						</div>
						</div>';
			header("refresh:2,url=index.php");


		endif;



	} // tercih güncelle

	function islemler($bag)
	{

		$kulal = $this->kulal($bag);

		if ($kulal["yetki"] == 2):

			echo ' <div class="col-md-4"></div> 
        
          <div class="col-md-4">
          				<div class="row">
                    <div class="col-md-6" style="padding:5px;"><a href="index.php?hareket=kategoriekle" class="btn btn-outline-secondary">Kategori Ekle</a></div>  
                    
                    <div class="col-md-6" style="padding:5px;"><a href="index.php?hareket=urunlistesi" class="btn btn-outline-secondary">Ürün listesi</a></div>
					
					  <div class="col-md-6" style="padding:5px;"><a href="index.php?hareket=talepler" class="btn btn-outline-secondary">Talepler</a></div>
					
                     <div class="col-md-6" style="padding:5px;"><a href="index.php?hareket=rapor" class="btn btn-outline-secondary">Ürün Raporu</a></div>
                    
                        </div>
          
          </div> 
          
        <div class="col-md-4"></div>  ';

		elseif ($kulal["yetki"] == 3):


			echo ' <div class="col-md-4"></div> 
        
          <div class="col-md-4">
          				<div class="row">
                    <div class="col-md-6" style="padding:5px;"><a href="index.php?hareket=kategoriekle" class="btn btn-outline-secondary">Kategori Ekle</a></div>  
                    
                    <div class="col-md-6" style="padding:5px;"><a href="index.php?hareket=urunlistesi" class="btn btn-outline-secondary">Ürün listesi</a></div>
					
                     <div class="col-md-6" style="padding:5px;"><a href="index.php?hareket=rapor" class="btn btn-outline-secondary">Ürün Raporu</a></div>
					 
					    <div class="col-md-6" style="padding:5px;"><a href="index.php?hareket=kullaniciekle" class="btn btn-outline-secondary">Kullanıcı Ekle</a></div>
						
						   <div class="col-md-6" style="padding:5px;"><a href="index.php?hareket=ayar" class="btn btn-outline-secondary">Ayarlar</a></div>
                    
                        </div>
          
          </div> 
          
        <div class="col-md-4"></div>  ';

		else:

			echo '<div class="col-md-12 text-center" style="margin-top:20px;">
						<div class="alert alert-danger">		
							HATA VAR
						</div>
						</div>';
			header("refresh:2,url=index.php");



		endif;

?>
        
        
        
        
        <?php

	} // işlemler

	// İŞLEMLER MENÜSÜ FONKSİYONLAR

	function kategoriekle($vt)
	{
		@$katbuton = $_POST["katbuton"];
		@$katad = $_POST["katad"];


		if (!$katbuton):

?>
    
           <div class="col-md-12" ><form action="index.php?hareket=kategoriekle" method="post">
           
           <strong>Kategori Ad</strong> <br /><input name="katad" type="text" placeholder="Kategoriyi Gir" required="required"  /></div>      
  
          
        <div class="col-md-12"><br />
        <input name="katbuton" type="submit" class="btn btn-outline-success" value="EKLE" style="margin-bottom:3px; " />
        </form>
        </div>  
    <?php


		else:

			$katek = "insert into kategori (ad) VALUES('$katad')";
			$ek = $vt->prepare($katek);
			$ek->execute();

			echo '<div class="col-md-12 text-center" style="margin-top:20px;">
						<div class="alert alert-success">		
							KATEGORİ EKLENDİ
						</div>
						</div>';
			header("refresh:2,url=index.php");



		endif;




	} // kategori ekle


	function urunlistesi($vt)
	{


		$urunlerial = "select * from urunler";
		$urun = $vt->prepare($urunlerial);
		$urun->execute();
		$urunb = $urun->get_result();
		echo '<table class="table table-hover text-center">
		  <thead>
      <tr>
        <th colspan="4"><a href="index.php?hareket=urunekle" class="btn btn-success">ÜRÜN EKLE</a></th>
              
      </tr>
    </thead>
		
    <thead>
      <tr>
        <th>Ürün Adı</th>
        <th>Stok Durumu</th>
        <th>Güncelle</th>
        <th>Sil</th>
        
      </tr>
    </thead>
    
    <tbody>';

		while ($urunson = $urunb->fetch_assoc()):

			echo '<tr>
        <td>' . $urunson["ad"] . '</td>
        <td>' . $urunson["stok"] . '</td>
        <td><a href="index.php?hareket=urunguncellest&id=' . $urunson["id"] . '" class="btn btn-outline-success">Güncelle</a></td>
		<td><a href="index.php?hareket=urunsil&id=' . $urunson["id"] . '" class="btn btn-outline-danger">Sil</a></td>
      </tr>';

		endwhile;

?>
          </tbody>
  </table>
   <?php
	} // ürün listesi	

	function urunsil($vt)
	{

		@$urunid = $_GET["id"];

		if ($urunid != ""):

			$sil = "delete from urunler where id=$urunid";
			$s = $vt->prepare($sil);
			$s->execute();

			echo '<div class="col-md-12 text-center" style="margin-top:20px;">
						<div class="alert alert-success">		
							ÜRÜN BAŞARIYLA SİLİNDİ
						</div>
						</div>';
			header("refresh:2,url=index.php?hareket=urunlistesi");


		else:

			echo '<div class="col-md-12 text-center" style="margin-top:20px;">
						<div class="alert alert-danger">		
							HATA VAR
						</div>
						</div>';
			header("refresh:2,url=index.php");

		endif;

	}

	function urunguncellest($vt)
	{

		$urunid = $_GET["id"];

		if ($urunid != ""):

			$sorgum = "select * from urunler where id=$urunid";
			$sorgu = $vt->prepare($sorgum);
			$sorgu->execute();
			$b = $sorgu->get_result();
			$sorguson = $b->fetch_assoc();

?>
                    
                    <div class="col-md-4"></div>
                    
          	<div class="col-md-4 table-bordered table-light" style="margin-top:50px; border-radius:10px;"> 
                               
        <div class="row" style="text-align:center">
        <div class="col-md-12"><form action="index.php?hareket=urungson" method="post"><b class="text-danger"> Ürün Ad : </b><input name="urunad" type="text" value="<?php echo $sorguson["ad"]; ?>" /></div> 
        
          <div class="col-md-12"><b class="text-danger">  
          Adet : <input name="stok" type="text" value="<?php echo $sorguson["stok"]; ?>" /> </b></div> 
          
        <div class="col-md-12">
      
        <input type="hidden" name="urunidson" value="<?php echo $sorguson["id"]; ?>" /> <br />  
        <input name="guncelson" type="submit" class="btn btn-outline-success" value="Güncelle" style="margin-bottom:3px; " /></form></div> 
               
        </div>
        
        </div>
                    
         <div class="col-md-4"></div>        
                    
                    <?php
		endif;

	} // ürün güncelle satın alama

	function urunguncelsonst($vt)
	{


		@$guncelson = $_POST["guncelson"];
		@$urunidson = $_POST["urunidson"];
		@$urunad = $_POST["urunad"];
		@$stok = $_POST["stok"];

		if ($guncelson):

			$gun = "update urunler set ad='$urunad', stok=$stok where id=$urunidson";
			$gunson = $vt->prepare($gun);
			$gunson->execute();


			echo '<div class="col-md-12 text-center" style="margin-top:20px;">
		<div class="alert alert-success">
		
				BAŞARIYLA GÜNCELLENDİ
		</div>
		</div>';

			header("refresh:2,url=index.php?hareket=urunlistesi");


		else:
			echo '<div class="col-md-12 text-center" style="margin-top:20px;">
		<div class="alert alert-danger">
		
				HATA OLUŞTU
		</div>
		</div>';
			header("refresh:2,url=index.php");

		endif;

	} // ürün güncelle satın alama

	function urunekle($ttt)
	{
		@$guncelson = $_POST["guncelson"];
		@$urunad = $_POST["urunad"];
		@$stok = $_POST["stok"];
		@$katid = $_POST["katid"];

		if (!$guncelson):


?>
		
              <div class="col-md-4"></div>
                    
          	<div class="col-md-4 table-bordered table-light" style="margin-top:50px; border-radius:10px;"> 
                               
        <div class="row" style="text-align:center">
        <div class="col-md-12"><form action="index.php?hareket=urunekle" method="post"><b class="text-danger"> Ürün Ad : </b><input name="urunad" type="text" /></div> 
                <div class="col-md-12"><form action="index.php?hareket=urungson" method="post"><b class="text-danger">Kategori : </b> <select name="katid">
                
                <?php

			$sorgum = "select * from kategori";
			$sorgu = $ttt->prepare($sorgum);
			$sorgu->execute();
			$b = $sorgu->get_result();
			while ($sorguson = $b->fetch_assoc()):
				echo '<option value="' . $sorguson["id"] . '">' . $sorguson["ad"] . '</option>';
			endwhile;

?>
                
                
                
                </select>   </div> 
        
          <div class="col-md-12"><b class="text-danger">  
          Adet : <input name="stok" type="text" /> </b></div> 
          
        <div class="col-md-12">
      
        <br />  
        <input name="guncelson" type="submit" class="btn btn-outline-success" value="ÜRÜN EKLE" style="margin-bottom:3px; " /></form></div> 
               
        </div>
        
        
        
        </div>
                    
         <div class="col-md-4"></div>  
        
		<?php

		else:

			$urunek = "insert into urunler (katid,ad,stok) VALUES ($katid,'$urunad',$stok)";
			$e = $ttt->prepare($urunek);
			$e->execute();

			echo '<div class="col-md-12 text-center" style="margin-top:20px;">
		<div class="alert alert-success">
		
				ÜRÜN BAŞARIYLA EKLENDİ
		</div>
		</div>';
			header("refresh:2,url=index.php");



		endif;

	} // ürün ekle

	function talepler($g)
	{

		$talep = "select * from talepler";
		$ta = $g->prepare($talep);
		$ta->execute();
		$satir = $ta->get_result();


		echo '<table class="table table-hover text-center">
			
    <thead>
      <tr>
		<th>Ürün İD</th>
        <th>Ürün Adı</th>
        <th>Talep Edilen Stok</th>
        <th>Güncelle</th>
        <th>Sil</th>
        
      </tr>
    </thead>
    
    <tbody>';

		while ($urunson = $satir->fetch_assoc()):

			echo '<tr>
		<td>' . $urunson["urunid"] . '</td>
        <td>' . $urunson["urunad"] . '</td>
        <td>' . $urunson["talepstok"] . '</td>
        <td><a href="index.php?hareket=talepguncel&id=' . $urunson["id"] . '&urunid=' . $urunson["urunid"] . '" class="btn btn-outline-success">Güncelle</a></td>
		<td><a href="index.php?hareket=talepsil&id=' . $urunson["id"] . '" class="btn btn-outline-danger">Sil</a></td>
      </tr>';

		endwhile;

?>
        
          </tbody>
  </table>
<?php
	}

	function talepguncel($v)
	{


		$talepid = $_GET["id"];
		$urunid = $_GET["urunid"];

		$talepsor = "select * from talepler where id=$talepid";
		$talep = $v->prepare($talepsor);
		$talep->execute();
		$b = $talep->get_result();
		$son = $b->fetch_assoc();

		$talepstok = $son["talepstok"];
		$urunad = $son["urunad"];



?>
        
  <div class="col-md-4"></div>                    
                    	<div class="col-md-4 table-bordered table-light" style="margin-top:50px; border-radius:10px;"> 
                               
        <div class="row" style="text-align:center">
        
          <div class="col-md-12"><b class="text-danger">  <form action="index.php?hareket=talepguncelson" method="post"> 
          Ürün Ad : <input name="urunad" type="text" value="<?php echo $urunad; ?>" readonly="readonly" /> </b></div> 
          
                 <div class="col-md-12"><b class="text-danger">  
        Talep Stok : <input name="stok" type="text" value="<?php echo $talepstok; ?>" readonly="readonly" /> </b></div>
          
          
        <div class="col-md-12">
        
         <input type="hidden" name="talepid" value="<?php echo $talepid; ?>" /> 
         <input type="hidden" name="urunid" value="<?php echo $urunid; ?>" /> 
         <input type="hidden" name="stokadet" value="<?php echo $talepstok; ?>" />        
       
        <input name="guncelson" type="submit" class="btn btn-outline-success" value="Stok Ver" style="margin-bottom:3px; " /></form></div> 
               
        </div>
        
        
        
        </div>
                    
         <div class="col-md-4"></div>    
	
    <?php


	} // talep güncelle

	function talepguncelson($v)
	{

		$butonum = $_POST["guncelson"];
		$stokadet = $_POST["stokadet"];
		$urunid = $_POST["urunid"];
		$talepid = $_POST["talepid"];

		if ($butonum):


			$kul = "select * from urunler where id=$urunid";
			$so = $v->prepare($kul);
			$so->execute();
			$s = $so->get_result();
			$sonartik = $s->fetch_assoc();

			$sonstok = $sonartik["stok"] + $stokadet;


			$kul2 = "update urunler set stok=$sonstok where id=$urunid";
			$so2 = $v->prepare($kul2);
			$so2->execute();

			echo '<div class="col-md-12 text-center" style="margin-top:20px;">
					<div class="alert alert-success">		
						STOK VERİLDİ
					</div>
					</div>';

			$si = "delete from talepler where id=$talepid";
			$so3 = $v->prepare($si);
			$so3->execute();

			header("refresh:2,url=index.php?hareket=talepler");



		else:

			echo '<div class="col-md-12 text-center" style="margin-top:20px;">
					<div class="alert alert-danger">		
						HATA VAR
					</div>
					</div>';


			header("refresh:2,url=index.php?hareket=talepler");


		endif;



	} // talep güncel son

	function talepsil($v)
	{
		@$talepid = $_GET["id"];

		$si = "delete from talepler where id=$talepid";
		$so3 = $v->prepare($si);
		$so3->execute();

		echo '<div class="col-md-12 text-center" style="margin-top:20px;">
					<div class="alert alert-success">		
						TALEP SİLİNDİ
					</div>
					</div>';


		header("refresh:2,url=index.php?hareket=talepler");


	} // talep sil	

	function rapor($v)
	{

		$topkat = "select * from kategori";
		$topka = $v->prepare($topkat);
		$topka->execute();
		$topson = $topka->get_result();


		$topurun = "select * from urunler";
		$topka2 = $v->prepare($topurun);
		$topka2->execute();
		$topson2 = $topka2->get_result();
		$urundizi = $topson2->fetch_assoc();


		$topadet = "select SUM(stok) from urunler";
		$topka3 = $v->prepare($topadet);
		$topka3->execute();
		$satir = $topka3->get_result();
		$adetdizi = $satir->fetch_assoc();

?>
        
         <table class="table  text-center">	
    <thead>
      <tr>
        <th>Toplam Kategori</th>
        <th>Toplam Ürün Grup</th>
        <th>Toplam Stok Adet</th>
              
      </tr>
    </thead>
    <tbody>		
		<tr>
        <td><b class="text-danger"><?php echo $topson->num_rows; ?></b></td>
        <td><b class="text-danger"><?php echo $topson2->num_rows; ?></b></td>
        <td><b class="text-danger"><?php echo $adetdizi['SUM(stok)']; ?></b></td>		
      </tr>		
          </tbody>
  </table>
    
           <div class="col-md-4">
           
                <table class="table  text-center table-hover">	
    <thead>
      <tr>
        <th colspan="2">Kategori Adet</th>              
      </tr>
    </thead>
    <thead>
      <tr>
        <th>Kategori Adı</th> 
         <th>Toplam Adet</th>                    
      </tr>
    </thead>
    
    <tbody>		
    <?php
		while ($katdizi = $topson->fetch_assoc()):




			$iceri = "select SUM(stok) from urunler where katid=" . $katdizi["id"] . "";

			$iceri1 = $v->prepare($iceri);
			$iceri1->execute();
			$iceri2 = $iceri1->get_result();


			$adetdizi2 = $iceri2->fetch_assoc();

			echo '	<tr>
				
      	 		 <td><b class="text-danger"> ' . $katdizi["ad"] . '</b></td> 
				 <td><b class="text-info"> ' . $adetdizi2['SUM(stok)'] . '</b></td>      		
     			 </tr>	';
		endwhile;

?>
    
          </tbody>
  </table>
           
           </div>   
              <div class="col-md-4">     
              
     <table class="table  text-center">	
     
      <thead>
      <tr>
        <th colspan="2" clasa="table-info">Stoğu -Azalan</th>              
      </tr>
    </thead>
    <thead>
      <tr>
        <th>Ürün Adı</th> 
         <th>Stok Adeti</th>                    
      </tr>
    </thead>   
    
    <tbody>		
		 <?php

		$icerim = "select * from urunler order by stok desc LIMIT 4";

		$iceri1m = $v->prepare($icerim);
		$iceri1m->execute();
		$iceri2m = $iceri1m->get_result();


		while ($adetdizi2m = $iceri2m->fetch_assoc()):

			echo '	<tr>
				
      	 		 <td><b class="text-danger"> ' . $adetdizi2m["ad"] . '</b></td> 
				 <td><b class="text-info"> ' . $adetdizi2m["stok"] . '</b></td>      		
     			 </tr>	';


		endwhile;
?>
          </tbody>
  </table>
  
  
  </div> 
  
    
                 <div class="col-md-4">
                  <table class="table  text-center">	
     
      <thead>
      <tr>
        <th colspan="2">Stoğu +Artan</th>              
      </tr>
    </thead>
    <thead>
      <tr>
        <th>Ürün Adı</th> 
         <th>Stok Adeti</th>                    
      </tr>
    </thead>   
    
    <tbody>		
		 <?php
		$icerim = "select * from urunler order by stok asc LIMIT 4";

		$iceri1m = $v->prepare($icerim);
		$iceri1m->execute();
		$iceri2m = $iceri1m->get_result();


		while ($adetdizi2m = $iceri2m->fetch_assoc()):

			echo '	<tr>
				
      	 		 <td><b class="text-danger"> ' . $adetdizi2m["ad"] . '</b></td> 
				 <td><b class="text-info"> ' . $adetdizi2m["stok"] . '</b></td>      		
     			 </tr>	';
		endwhile;

?>
          </tbody>
  </table></div>   
  
        <?php
	} // rapor

	function kullaniciekle($t)
	{

		@$buton = $_POST["kulekle"];
		@$kulad = $_POST["kulad"];
		@$sifre = $_POST["sifre"];
		@$yetki = $_POST["yetki"];

		if (!$buton):

?>
        
        
         <div class="col-md-4"></div>                    
                    	<div class="col-md-4 table-bordered table-light" style="margin-top:50px; border-radius:10px;"> 
                               
        <div class="row" style="text-align:center">
        
          <div class="col-md-12"><b class="text-danger"> 
           <form action="index.php?hareket=kullaniciekle" method="post"> 
          Kullanıcı Adı : <input name="kulad" type="text" required="required"  /> </b></div> 
          
               
               
                         <div class="col-md-12"><b class="text-danger">  
          Kullanıcı Şifre : <input name="sifre" type="text" required="required"  /> </b></div> 
               
               
                 <div class="col-md-12"><b class="text-danger">  
       Kullanıcı Yetki : 
       
       <select name="yetki">
       <option value="1">Depo</option>
        <option value="2">Satın alma</option>
         <option value="3">Yönetici</option>
       
       </select>
        </b></div>
          
          
        <div class="col-md-12">
        
          
       
        <input name="kulekle" type="submit" class="btn btn-outline-success" value="OLUŞTUR" style="margin-bottom:3px; " /></form></div> 
               
        </div>
        </div>     
         <div class="col-md-4"></div>    
        <?php
		else:
			if ($kulad != "" && $sifre != ""& $yetki != ""):

				$kulad = md5(sha1(md5($kulad)));
				$sifre = md5(sha1(md5($sifre)));
		
$ekle = "insert into kullanici (kulad,kulsifre,yetki,tercih) VALUES ('$kulad','$sifre',$yetki,1)";		
$ekleson = $t->prepare($ekle);
				$ekleson->execute();
				$son = $ekleson->get_result();

				echo '<div class="col-md-12 text-center" style="margin-top:20px;">
					<div class="alert alert-info">		
						KULLANICI EKLENDİ
					</div>
					</div>';


				header("refresh:2,url=index.php");
			else:
				echo '<div class="col-md-12 text-center" style="margin-top:20px;">
					<div class="alert alert-danger">		
						TÜM BİLGİLER DOLU OLMALI
					</div>
					</div>';
				header("refresh:2,url=index.php?hareket=kullaniciekle");
			endif;
		endif;
	} // kullanıcı ekle

	function ayarlar($b)
	{
		@$buton = $_POST["kulekle"];
		@$oto = $_POST["oto"];
		if (!$buton):

?>
        <div class="col-md-4"></div>                    
        <div class="col-md-4 table-bordered table-light" style="margin-top:50px; border-radius:10px;">                      
           <div class="row" style="text-align:center">
              <div class="col-md-12"><b class="text-danger"> 
                   <form action="index.php?hareket=ayar" method="post"> 
                    Otomatik Tercih : 
                      <select name="oto">
                       <?php
			$ayar = "select * from ayarlar";
			$so2 = $b->prepare($ayar);
			$so2->execute();
			$s2 = $so2->get_result();
			$sonartik2 = $s2->fetch_assoc();
			if ($sonartik2["durum"] == 1):
				echo '
					<option value="1" selected="selected">Manuel</option>
     			   <option value="2">Otomatik</option>';

			else:
				echo ' 
					<option value="2" selected="selected">Otomatik</option>
					<option value="1" ">Manuel</option>
     			  ';

			endif;


?>
       
       </select><br />
        </b></div>
           
        <div class="col-md-12">
        <input name="kulekle" type="submit" class="btn btn-outline-success" value="DEĞİŞTİR" style="margin-bottom:3px; " /></form></div>     
        </div>
        </div>    
         <div class="col-md-4"></div>    
        <?php
		else:
			$ekle = "update ayarlar set durum=$oto where id=1";
			$ekleson = $b->prepare($ekle);
			$ekleson->execute();
			echo '<div class="col-md-12 text-center" style="margin-top:20px;">
					<div class="alert alert-info">		
						AYAR GÜNCELLENDİ
					</div>
					</div>';
			header("refresh:2,url=index.php");
		endif;
	} // ayalar
}
?>