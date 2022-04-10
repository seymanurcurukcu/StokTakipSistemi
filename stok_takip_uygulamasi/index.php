<?php require_once("dahili.php");
$dahilet = new stok; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<link rel="stylesheet" href="dosya/tasarim.css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>STOK TAKİP SİSTEMİ</title>
</head>
<body>
<div class="container table-bordered text-center" id="cont">
<?php
//echo md5(sha1(md5("asli")));
if (@$_COOKIE["kulad"] == "" && @$_COOKIE["sifre"] == ""):
?>
 <br /> <br /> <br />

     <div class="row border-bottom border-top"  style="text-align:center; background-color:#F9F9F9" >
      
        <div class="col-md-12"> <form action="logok.php" method="post"><strong>Kullanıcı Adı</strong> <br /><input name="ad" type="text"  placeholder="Adınızı Yazını" required="required" /></div> 
        
          <div class="col-md-12"><strong>Şifre</strong><br /><input name="sifre" type="password" placeholder="Şifrenizi Yazınız" required="required" /></div> 
          
        <div class="col-md-12"><br />
        <input name="girisbuton" type="submit" class="btn btn-outline-success" value="Giriş" style="margin-bottom:3px; " />
        </form>
        </div>  
              
        </div>

<?php
else: ?> 

<!-- ÜST BÖLÜM -->
<div class="row border-bottom border-light text-left table-dark" style="min-height:60px;">
		<div class="col-md-3 text-danger" id="ustbolum">
        
        <h4 class="text-warning">Kullanıcı: <kbd><?php echo $_COOKIE["kulad"]; ?></kbd></h4> 
        </div>        
		<div class="col-md-9" id="ustbolum">        
        <ul class="nav justify-content-end" >     
        
<?php $dahilet->linkkontrol($db); ?> 
  

</ul>

</div>
</div>
<!-- ÜST BÖLÜM -->

<!-- KATEGORİ BÖLÜM -->
<div class="row border-bottom border-light text-left" >
		<div class="col-md-9">        
        <div class="row">
              <?php $dahilet->kategoricek($db); ?>        
        </div>  
</div>

		<div class="col-md-3">
        
        <?php $dahilet->tercihkontrol($db); ?>
        

</div>

</div>
<!-- KATEGORİ BÖLÜM -->

<!-- ORTA BÖLÜM -->
<div class="row text-center">


<?php


  
@$hareket = $_GET["hareket"];
  switch ($hareket):

    case "kategori":      @$katid = $_GET["id"];      $dahilet->kategoriyegore($db, $katid);

      break;

    case "urunguncelle":
      $dahilet->urunguncellegit($db);

      break;

    case "urunguncelleson":
      $dahilet->guncelson($db);

      break;

    case "sifredegistir":
      $dahilet->sifredegistir($db);

      break;

    case "cikis":

      $dahilet->cikis();
      break;

    case "islemler":
      $dahilet->islemler($db);

      break;

    case "tercih":
      $dahilet->tercihguncelle($db);

      break;

    case "talepler":
      $dahilet->talepler($db);

      break;

    case "kategoriekle":
      $dahilet->kategoriekle($db);
      break;

    case "urunlistesi":
      $dahilet->urunlistesi($db);
      break;
    case "urunsil":
      $dahilet->urunsil($db);
      break;


    case "urungson":
      $dahilet->urunguncelsonst($db);
      break;

    case "urunguncellest":
      $dahilet->urunguncellest($db);
      break;

    case "urunekle":

      $dahilet->urunekle($db);

      break;

    case "talepguncel":
      $dahilet->talepguncel($db);

      break;

    case "talepguncelson":
      $dahilet->talepguncelson($db);

      break;

    case "talepsil":
      $dahilet->talepsil($db);

      break;

    case "rapor":
      $dahilet->rapor($db);
      break;

    case "kullaniciekle":
      $dahilet->kullaniciekle($db);
      break;

    case "ayar":
      $dahilet->ayarlar($db);

      break;

    case "talepgonder":
      $dahilet->talepgonder($db);

      break;

    default:      // ürünleri en son eklenene göre sıralayalım  gerekirse stok durumuna göre

      // BURADA ÇOK İŞ VAR       $kuldeger = $dahilet->kulal($db);
      if ($kuldeger["yetki"] == 1):        $dahilet->varsayilan($db);      elseif ($kuldeger["yetki"] == 2):
        $dahilet->talepler($db);      elseif ($kuldeger["yetki"] == 3):
        $dahilet->urunlistesi($db);
      
endif;
  endswitch;

?>
	
		
</div>

</div>

<?php
endif; ?>
</div>

</body>
</html>