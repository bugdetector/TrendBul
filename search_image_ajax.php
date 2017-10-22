<?php
require "functions.php";
$uploadby = $_POST["search-method"];
if ($uploadby == "list") {
    $movebool = true;
} else if ($uploadby == "linkupload") {
    $fileurl = $_POST["link"];
    $movebool = true;
} else if ($uploadby == "fileupload") {
    $fileurl = "uploaded/" . $_FILES["file"]["name"];
    if (is_uploaded_file($_FILES["file"]["tmp_name"])) {
        $movebool = move_uploaded_file($_FILES["file"]["tmp_name"], $fileurl);
        if (!$movebool) {
            mkdir("uploaded");
            $movebool = move_uploaded_file($_FILES["file"]["tmp_name"], $fileurl);
        }
    }
}
if ($movebool) {
    $sex = $_POST["sex"];
    $category = $_POST["category"];
    $method = $_POST['method'];
    $searching_file_url = "";
    if ($uploadby != "list") {
        $searching_file_url = "<div class='border'>Aranan resim <br clear = 'all'>
                                <img src= '$fileurl' class='img-responsive imgrounded' id='id-0'/>
                                <button type='button' id='0' class='btn-info btn-block' data-toggle='modal' data-target='#SearchModal'
                                onclick='modalClicked(this)' >Bu resmi tekrar ara</button>
                              </div>";
    }
    if ($uploadby != "list") {
        if ($category == "Tümü") {
            $category = "";
        }
        $items = searchSimilars($sex, $category, $fileurl, $method);
    } else {
        if ($category == "Tümü") {
            $results["result"][0] = "<h1 class='col-12' style='text-align:center'> Bir kategori seçiniz.</h1> ";
            $results["searched"] = "";
        } else {
            $items = listFiles($sex, $category);
        }
    }
    if (isset($items)) {
        $counter = 1;
        foreach ($items as $item) {//for server $item->image_link, localhost $item->file_name
            $result_arr[] = "<div class='col-md-3 col-sm-4 col-6'>
                 <a href='$item->item_link' target='_blank' class='popup' onmouseenter='onHover(this)' onmouseout='onOut(this)'> 
                 <img src= '$item->image_link' id='id-$counter' class='img-responsive imgrounded'>
                    <span class='popuptext col-12' id='popup'><img class='img-responsive' id='popupimage' src=''></span>
                 </a>
                 <br>
                 <strong> $item->brand </strong><br>
                 $item->price
                 <br>
                 <button type='button' id='$counter' class='btn-info' data-toggle='modal' data-target='#SearchModal'
                 onclick='modalClicked(this)'>Bu resmi ara</button>
                 <br clear='all'>
              </div>";
            $counter += 1;
        }
        $results["result"] = $result_arr;
        $results["searched"] = $searching_file_url;
    }
}
echo json_encode($results);
?>
