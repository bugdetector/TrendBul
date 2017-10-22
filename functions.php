<?php
/**
 * Created by PhpStorm.
 * User: murat
 * Date: 13.08.2017
 * Time: 02:14
 */
require "modules/image_search/image_search.php";
function getsex()
{
    return getAtDeep(1);
}

function getcategories($sex)
{
    return getAtDeep(2, array("path LIKE '%" . $sex . "%'"));
}

function listFiles($sex, $category)
{
    return ItemData::getAllImageData($sex, $category);
}