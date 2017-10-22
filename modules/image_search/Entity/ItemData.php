<?php

/**
 * Class ItemData
 * User: murat
 * Date: 12.02.2017
 */
class ItemData
{
    public $image_link;
    public $item_link;
    public $brand;
    public $price;
    public $file_name;
    public $distance;
    public $image_data;

    public static function withQueryResult($queryResult)
    {
        $instance = new self();
        $instance->setValues($queryResult);
        return $instance;
    }

    public function setValues($queryResult)
    {
        $this->image_link = strval($queryResult["image_link"]);
        $this->item_link = strval($queryResult["item_link"]);
        $this->brand = strval($queryResult["brand"]);
        $this->price = strval($queryResult["price"]);
        $this->file_name = $queryResult["file_name"];
        $this->image_data = new ImageData();
        $this->image_data->setColorHistogram($queryResult["color_histogram"]);
        $this->image_data->setLBPHistogram($queryResult["lbp_histogram"]);
        $this->image_data->setHog($queryResult["hog"]);
    }

    public function addToDatabase()
    {
        global $imageSearchDb;
        $colorHistogram = implode(",", $this->color_histogram);
        $LBPHistogram = implode(",", $this->lbp_histogram);
        $Hog = implode(",", $this->hog);
        $preparedStatement = $imageSearchDb->prepare("INSERT INTO images (item_link,image_link,brand,price,file_name,color_histogram,lbp_histogram,hog) " .
            "VALUES (:item_link,:image_link,:brand,:price,:file_name,:color_histogram,:lbp_histogram,:hog)");
        $preparedStatement->bindValue(':item_link', $this->item_link, SQLITE3_TEXT);
        $preparedStatement->bindValue(':image_link', $this->image_link, SQLITE3_TEXT);
        $preparedStatement->bindValue(':brand', $this->brand, SQLITE3_TEXT);
        $preparedStatement->bindValue(':price', $this->price, SQLITE3_TEXT);
        $preparedStatement->bindValue(':file_name', $this->file_name, SQLITE3_TEXT);
        $preparedStatement->bindValue(':color_histogram', $colorHistogram, SQLITE3_TEXT);
        $preparedStatement->bindValue(':lbp_histogram', $LBPHistogram, SQLITE3_TEXT);
        $preparedStatement->bindValue(':hog', $Hog, SQLITE3_TEXT);
        $preparedStatement->execute();
    }

    /**
     * @param $imageId
     * @return ItemData
     */
    public static function getImageData($imageId)
    {
        global $imageSearchDb;
        $preparedStatement = $imageSearchDb->prepare("SELECT * FROM images WHERE id=:id");
        $preparedStatement->bindValue(":id", $imageId, SQLITE3_INTEGER);
        $result = $preparedStatement->execute()->fetchArray();

        return $result ? self::withQueryResult($result) : null;
    }

    /**
     * @param string $sex
     * @param string $category
     * @return array
     */
    public static function getAllImageData($sex = "", $category = "")
    {
        global $imageSearchDb;
        $preparedStatement = $imageSearchDb->prepare("SELECT * FROM images WHERE file_name LIKE '%$sex/%$category%'");
        $results = $preparedStatement->execute();
        $images = array();
        while ($result = $results->fetchArray()) {
            $images[] = self::withQueryResult($result);
        }
        return $images;
    }
}

?>
