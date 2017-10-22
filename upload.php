<?php
  /* $_FILES[] -> global
   name -> dosya adı
   size -> dosya boyutu
   type -> mime tipi */

   $type = $_FILES["file"]["type"];//dosyanın mime tipi alınıyor.
   if(preg_match('/image*/',$type)){//mime tipine göre resim dosyası olup olmadığı anlaşılıyor.
     $filename = "uploaded/".$_FILES["file"]["name"];
     if(is_uploaded_file($_FILES["file"]["tmp_name"])){//dosyanın yükelnip yüklenmediği kontrol ediliyor.
         $movebool = @move_uploaded_file($_FILES["file"]["tmp_name"],$filename);//sunucu diskine kaydediliyor.
         if(!$movebool){
           mkdir("uploaded");//dizin oluşturuluyor
           $movebool =  @move_uploaded_file($_FILES["file"]["tmp_name"],$filename);
         }

       if($movebool){
         echo "Dosya başarıyla yüklendi. <br> {$_FILES['file']['name']}";
       }
     }else{
       echo "Dosya yüklenirken bir şeyler yanlış gitti..";
     }

   }else {
     echo "Lütfen bir resim dosyası seçiniz...";
   }
 ?>
