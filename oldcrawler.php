<?php
  set_time_limit(0);
  ini_set('max_execution_time', 360000);//10 saat
  require("image_search.php");

  $url = "http://www.markafoni.com/";
  $atStart = "NewDatabase";
  $atNow = "Database";
  $atEnd = "OldDatabase";
  $categoryHeaders = [[2,3,4,13,15,18],[8,9,10,11]];//kadın -> tesettür 2, elbise 3, mont 4, gömlek 13, tulum 15, triko 18
  //erkek -> triko 8, mont 9, gömlek 10, pantolon 11
  if(!is_dir($atStart)){
      mkdir($atStart);
  }
  $data = file_get_contents($url);
  $mainpage = new DOMDocument;
  @$mainpage->loadHTML($data);
  $categories = findAll($mainpage,"li","class","category");
  foreach ([0,1] as $index){// 0 -> kadın , 1-> erkek
      $sex = trim(find($categories[$index],"a","","")->textContent);

      $check = $atStart.DIRECTORY_SEPARATOR.$sex;
      if(!is_dir($check)){
          mkdir($check);
      }
      if(!is_dir("NewXMLFiles")){
          mkdir("NewXMLFiles");
      }
      $subcategorymenu = find($categories[$index],"ul","class",'headermenu-categories');
      $subcategories = findAll($subcategorymenu,"a","","");
      foreach ($categoryHeaders[$index] as $jndex){
          $subcategoryName = $subcategories[$jndex]->getAttribute("title");

          $check = $atStart.DIRECTORY_SEPARATOR.$sex.DIRECTORY_SEPARATOR.$subcategoryName.DIRECTORY_SEPARATOR;
          if(!is_dir($check)){
              mkdir($check);
          }
          $subcategoryLink = $subcategories[$jndex]->getAttribute("href");
          $subPage =  new DOMDocument;
          @$subPage->loadHTML(file_get_contents($subcategoryLink));
          $title = find(find($subPage,"section","class","title-content"),"sup","","")->textContent;

          $xmlwriter = new XMLWriter();
          $xmlFileName = "$sex.$subcategoryName.xml";
          $xmlFiles[] = $xmlFileName;
          $xmlwriter->openURI('NewXMLFiles/'.$xmlFileName);
          $xmlwriter->startElement("items");//items start

          $downloaded = 0;
          $itemCount = intval(preg_replace("/[^0-9]/","",$title));
          echo  "<br>".$sex."   ".$subcategoryName."  Öge sayısı : ".$itemCount."<br>";
          $contiune = true;
          while ($downloaded<$itemCount-1 && $contiune){
              $templink = $subcategoryLink."?sz=12&start=".$downloaded."&format=page-element";
              @$subPage->loadHTML(file_get_contents($templink));
              $items = findAll($subPage,"div","class","item-container");
              if(count($items)==0){
              	$contiune=false;
              }else{
              	
              foreach ($items as $item){
                  $imageLink = find($item,"img","","")->getAttribute("data-original");
                  $price = trim(find($item,"div","class","new-price")->textContent);
                  $iteminfo = find($item,"a","class","ee-product");
                  $itemLink = $url.$iteminfo->getAttribute("href");
                  $brand = trim(find($iteminfo,"div","","")->textContent);

                  $filename = $downloaded.".jpg";

                  $xmlwriter->startElement("item");//item start
                      $xmlwriter->writeElement("ImageLink",$imageLink);
                      $xmlwriter->writeElement("ItemLink",$itemLink);
                      $xmlwriter->writeElement("Brand",$brand);
                      $xmlwriter->writeElement("Price",$price);
                      $xmlwriter->writeElement("File",$filename);

	                  file_put_contents($check.$filename,file_get_contents($imageLink));
	                  $downloaded+=1;

	                  $xmlwriter->startElement("LBP");//LBP start
	                  $imageString = file_get_contents($check.$filename);
	                  $histogram = LBPhistogram($imageString);
	                  for ($i=0;$i<256;$i++){
	                      $xmlwriter->writeElement("i",strval($histogram[$i]));
	                  }
	                  $xmlwriter->endElement();//LBP end

	                  $xmlwriter->startElement("ColorHist");//ColorHist start
	                  $histogram = _64BinHistogram($imageString);
	                  for ($i=0;$i<64;$i++){
	                      $xmlwriter->writeElement("i",strval($histogram[$i]));
	                  }
	                  $xmlwriter->endElement();//ColorHist end
	                  
	                  $xmlwriter->startElement("HOG");
	                  $histogram = HOG($imageString);
	                  for ($i=0;$i<9;$i++){
	                      $xmlwriter->writeElement("i",strval($histogram[$i]));
	                  }
	                  $xmlwriter->endElement();//HOG end

                  $xmlwriter->endElement();//item end
              }
              }
              echo $downloaded."  ";
              ob_flush();
              flush();
          }
          $xmlwriter->endElement();//items end
          $xmlwriter->endDocument();//document end
          $xmlwriter->flush();
      }

  }
  if(is_dir("XMLFiles")){
      rrmdir("XMLFiles");
  }
  rename("NewXMLFiles","XMLFiles");
  if(is_dir($atEnd)) {
      rrmdir($atend);
  }
  rename($atNow,$atEnd);
  rename($atStart,$atNow);

function rrmdir($path) {
    $i = new DirectoryIterator($path);
    foreach($i as $f) {
        if($f->isFile()) {
            unlink($f->getRealPath());
        } else if(!$f->isDot() && $f->isDir()) {
            rrmdir($f->getRealPath());
        }
    }
    rmdir($path);
}
function findAll(&$dom,$tag,$attribute,$constant){
    $resList = $dom->getElementsByTagName($tag);
    $retarr = array();
    foreach ($resList as $element){
        if($element->getAttribute($attribute) == $constant){
            $retarr[] = $element;
        }
    }
    return $retarr;
}
function find(&$dom,$tag,$attribute,$constant){
    $resList = $dom->getElementsByTagName($tag);
    foreach ($resList as $element){
        if($element->getAttribute($attribute) == $constant){
            return $element;
        }
    }
}
 ?>
