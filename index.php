<<<<<<< HEAD
<?php 
  ini_set("error_reporting",E_ALL);
  require 'functions.php'; 
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Trendbul</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/mycss.css">
    <script src="js/functions.js"></script>
    <script src="js/jquery-3.2.0.min.js"></script>
    <script src="js/bootstrap.js"></script>
</head>
<body onload="onLoad()">
<header style="margin:10px">
    <div class="navbar border">
        <div class="navbar-text" style="text-align:center">
            <a href='https://trendbul.yavuzmacit.com' style="text-decoration: none;"><h1>TrendBul Görsel Arama</h1></a>
        </div>
    </div>
</header>
<div class="container">
    <div class="row">
        <div class="col-md-3 col-sm-12">
            <div class="border">
                <form name="form" id="main-form" method="post" enctype="multipart/form-data">
                    Yöntem Seçiniz
                    <div class="form-group" id="methodcontainer">
                        <div class="form-control">
                            <div class="row">
                                <div class="col-12"><label><input type="radio" name="method" value="<?php echo COLOR ?>"
                                                                  checked>Renk</label></div>
                                <div class="col-12"><label><input type="radio" name="method"
                                                                  value="<?php echo TEXTURE ?>">Doku</label></div>
                                <div class="col-12"><label><input type="radio" name="method"
                                                                  value="<?php echo PATTERN ?>">Desen</label></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="sexcontainer">
                        Cinsiyet Seçiniz
                        <select class="form-control" name="sex" id="sex_id" onchange="listCategory(this.value)">
                            <?php
                            $sexs = getsex();
                            foreach ($sexs as $sex) {
                                echo "<option value='$sex'>$sex</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group" id="categorycontainer">
                        Kategoriler
                        <select class="form-control" name="category" id="category_id">
                        </select>
                    </div>
                    Görüntü Yükleme Yöntemi
                    <div class="form-group">
                        <select class="form-control" name="search-method" id="search-id" onchange="methodChanged()">
                            <option value="list">Listele</option>
                            <option value="linkupload">Link Yapıştırın</option>
                            <option value="fileupload">Dosya Yükleyin</option>
                        </select>
                    </div>

                    <div class="form-group" id="link-id">
                        Görüntü Bağlantısı :
                        <input class="form-control" type="text" name="link">
                    </div>
                    <div class="form-group" id="file-id">
                        <input class="form-control" type="file" name="file" accept="image/*"/>
                    </div>

                    <br clear="all"><br clear="all">
                    <input type="reset" class="btn-block btn-danger" value="Temizle">
                    <input type="button" class="btn-primary btn-block" id="submitbutton" onclick='search("main-form")'
                           value="Listele"/>
                </form>
            </div>
        </div>
        <div class="col-md-2 push-md-7 col-sm-12">
            <div class="col-12" id="searched"></div>
        </div>

        <div class="col-md-7 pull-md-2 col-sm-12 border">
            <div class="row" id="showcase">
            </div>
        </div>
    </div>
    <div class="row border">
        <div class="col-12" id="paging" style="text-align:center"></div>
    </div>
</div>
    <!-- Modal -->
    <div class="modal fade" id="SearchModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Yeni Arama</h4>
                </div>
                <div class="modal-body">
                    <form id="modal-form">
                        <div id="methodmodal"></div>
                        <div id="sexmodal"></div>
                        <div id="categorymodal"></div>
                        <input type="text" style="display:none" id="modalfile" name="link">
                        <input type="text" style="display:none" name="search-method" value="linkupload">
                        <div class="modal-footer">
                            <input type="button" class="btn btn-primary" data-dismiss="modal"
                                   onclick="search('modal-form')" value="Ara">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Vazgeç</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="LoadingModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Yükleniyor...</h4>
                </div>
                <div class="modal-body">
                    <img src="loader.gif" class="img-responsive imgrounded">
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
</body>
</html>
=======
<?php

  $sex = "Kadın";
  $category = "";
  $method = "color";

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(!empty($_POST["sex"])){
      $sex = $_POST["sex"];
    }

    if(!empty($_POST["category"])){
      $category = $_POST["category"];
    }

    if(!empty($_POST["method"])){
      $method = $_POST["method"];
    }
  }
 ?>


<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
  <input type="radio" name="method" <?php if (isset($method) && $method=="color") echo "checked";?> value="color">Renk
  <input type="radio" name="method" <?php if (isset($method) && $method=="texture") echo "checked";?> value="texture">Doku
  <input type="radio" name="method" <?php if (isset($method) && $method=="shape") echo "checked";?> value="shape">Şekil
  <br>
    <h3>Cinsiyet</h3>
    <select name="sex">
      <?php
        $dir = opendir("Database/");
        while($file = readdir($dir)){
          if($file != "." && $file != ".."){
            if($file == $sex){
              $selected = "selected";
            }else{
              $selected = "";
            }
            echo "<option name='".$file."' ".$selected.">".$file."</option>";
          }
        }
      ?>
    </select>

  <h3>Kategori</h3>
  <select name="category">
    <?php
    if(!empty($sex)){
      $dir = opendir("Database/".$sex);
      while($file = readdir($dir)){
        if($file != "." && $file != ".."){
          if($file==$category){
            $selected = "selected";
          }else {
            $selected = "";
          }
          echo "<option name='".$file."' ".$selected.">  ".$file." </option>";
        }
      }
    }
    ?>
  </select>
  <input type="submit" value="Onayla"/>
</form>
<form action="upload.php" method="post" enctype="multipart/form-data">
  <h3> Dosya Yükle </h3>
  <p><input type="file" name="file" /></p>
  <input type="submit" value="Yükle"/>
</form>
>>>>>>> 4b1252a0a1ea14a7b0dd2f56eee89f5dc549fe73
