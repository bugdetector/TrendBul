<?php
require 'functions.php';
if ($_POST) {
    $categories = getcategories($_POST["sex"]);
    $retstr = "<option value='Tümü'>Tümü</option>";
    foreach ($categories as $category) {
        $retstr .= "<option value='$category'>$category</option>";
    }
    echo $retstr;
}
?>
