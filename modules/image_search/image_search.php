<?php
/**
 * Image search module
 * User: murat
 * Date: 12.08.2017
 */
$imageSearchDb = new SQLite3("modules/image_search/imageSearch.db");

include "Entity/ItemData.php";
include "Entity/ImageData.php";
include "Entity/FeatureExtractor.php";
include "Entity/ColorFeatureExtractor.php";
include "Entity/PatternFeatureExtractor.php";
include "Entity/TextureFeatureExtractor.php";

const COLOR = 1;
const TEXTURE = 2;
const PATTERN = 3;
function searchSimilars($sex, $category, $fileurl, $method)
{
    $featureExtractor = null;
    switch ($method) {
        case COLOR:
            $featureExtractor = new ColorFeatureExtractor();
            break;
        case TEXTURE:
            $featureExtractor = new TextureFeatureExtractor();
            break;
        case PATTERN:
            $featureExtractor = new PatternFeatureExtractor();
            break;
        default:
            return null;
    }
    $searchingImageString = file_get_contents($fileurl);
    $searchingImageData = $featureExtractor->calculateHistogram($searchingImageString);
    $items = ItemData::getAllImageData($sex, $category);
    $itemsData = array();
    foreach ($items as $item) {
        $distance = $featureExtractor->calculateDistance($searchingImageData, $item->image_data);
        $itemsData[] = array(
            "item" => $item,
            "distance" => $distance
        );
    }
    quickSort($itemsData, 0, count($itemsData) - 1);
    return array_map(function ($element) {
        return $element["item"];
    }, $itemsData);
}

function partition(&$arr, $leftIndex, $rightIndex)
{
    $pivot = $arr[($leftIndex + $rightIndex) / 2]["distance"];

    while ($leftIndex <= $rightIndex) {
        while ($arr[$leftIndex]["distance"] < $pivot)
            $leftIndex++;
        while ($arr[$rightIndex]["distance"] > $pivot)
            $rightIndex--;
        if ($leftIndex <= $rightIndex) {
            $tmp = $arr[$leftIndex];
            $arr[$leftIndex] = $arr[$rightIndex];
            $arr[$rightIndex] = $tmp;
            $leftIndex++;
            $rightIndex--;
        }
    }
    return $leftIndex;
}

function quickSort(&$itemDataArray, $leftIndex, $rightIndex)
{
    $index = partition($itemDataArray, $leftIndex, $rightIndex);
    if ($leftIndex < $index - 1)
        quickSort($itemDataArray, $leftIndex, $index - 1);
    if ($index < $rightIndex)
        quickSort($itemDataArray, $index, $rightIndex);
}

function getAtDeep($deep, $extraFilter = null)
{
    global $imageSearchDb;
    $query = "SELECT path FROM directories WHERE deep=:deep";
    if ($extraFilter) {
        foreach ($extraFilter as $filter) {
            $query .= " AND " . $filter;
        }
    }
    $statement = $imageSearchDb->prepare($query);
    $statement->bindValue(":deep", $deep, SQLITE3_INTEGER);
    $result = $statement->execute();
    $resultArray = array();
    while ($atDeep = $result->fetchArray()) {
        $pathExploded = explode("/", $atDeep["path"]);
        $resultArray[] = end($pathExploded);
    }
    return $resultArray;
}

?>
