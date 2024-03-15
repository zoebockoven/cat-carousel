<?php
session_start(); /* this allows you to save data in $_SESSION */
/* https://www.w3schools.com/php/php_sessions.asp */
include '../config/config.php';
$doc = file_get_contents('../index.php');

// GET cats
function getCats() {
    $apiUrl = "https://api.thecatapi.com/v1/breeds";
    // Make HTTP request and wait for the response
    $response = file_get_contents("$apiUrl?api_key=APIKEY");
    $_SESSION["cats"] = json_decode($response);
}

// populate select element option text
function populateOptions() {
    $numCats = count($_SESSION["cats"]);
    $i = 0;
    while ($i < $numCats) {
        $catName = $_SESSION["cats"][$i]->name;
        $catId = $_SESSION["cats"][$i]->id;
        $str = "<option value='" . $catId . "'>" . $catName . "</option>";
        echo $str;
        $i++;
    }
    
}

// associative array
function associateArray() {
    $numCats = count($_SESSION["cats"]);
    $myArray = [];
    $i = 0;

    while ($i < $numCats) {
        $catName = $_SESSION["cats"][$i]->name;
        $catId = $_SESSION["cats"][$i]->id;
        $myArray["$catId"] = "$catName";
        $i++;
    }

    $_SESSION["arr"] = $myArray;

}

// set cat name for header
function setName($catId) {

    foreach ($_SESSION["arr"] as $x => $y) {
        
        if ($x == $catId) {
            return $y;
        }
      }

}

// get cat pictures
function getImages($catId) {

    $apiUrl = "https://api.thecatapi.com/v1/images/search";
    $queryString = "limit=10&breed_ids=" . $catId . "&api_key=" . APIKEY;

    // Make HTTP request and wait for the response
    $response = file_get_contents("$apiUrl?$queryString");
    
    if ($response === FALSE) {
        die("Error contacting the web API");
    } else {
        // Convert JSON response into PHP object
        $_SESSION["img"] = json_decode($response);
    
    }
}

function setImages() {
    $length = count($_SESSION["img"]);
    $i = 1;
    while ($i < $length) {
        echo "<div class='carousel-item'><img src='" . $_SESSION["img"][$i]->url . "'class='d-block w-100'></div>";
        $i++;
    }
}

function setIndicators() {
    $length = count($_SESSION["img"]);
    $i = 1;
    while ($i < $length) {
        $j = $i + 1;
        echo "<button type='button' data-bs-target='#carouselExampleIndicators' data-bs-slide-to='".$i."' aria-label='Slide ".$j."'></button>";
        
        $i++;
    }
}


?>