<?php
session_start();

include 'managePictures.php';
include 'request.php';
include 'table.php';

$path = $_SERVER['DOCUMENT_ROOT']."/autosave/cars/";

$errors = [];
$files = scandir($path);
$manage = new ManagePictures();

if (!empty($_GET['carUrl'])) {

    $content = new Request();
    $content = $content->get($_GET['carUrl'], $path);

    //MAKES ARRAY OF EXISTING FILES
    $newFiles = [];
    $oldFiles = [];
    $handle = opendir($path);

    //GRABS TABLE
    $table = new Table($content);
    $_SESSION['table'] = $table->get();

    //FIND ALL NEEDED IMAGES
    if (preg_match_all("/(http:\/\/media.balticautoshipping.com.s3.amazonaws.com\/......\/............_......\....)/", $content, $output)) {
        $matches = $output[0];
    }

    if(!empty($matches)){

        //adds then to old files array to replace them with new pictures
        while (false !== ($file = readdir($handle))) {
            if (preg_match('/\.(jpg|png)$/', $file)) {
                array_push($oldFiles, $file);
            }
        }

        //Saves Pictures To Pc
        foreach ($matches as $pic) {
            $largePic = str_replace("medium", "large", $pic);
            $picNames = explode('/', $largePic);
            array_push($newFiles, $picNames[4]);
            //SKIPS CODE BELLOW IF PICTURES ALREADY ARE SAVED
            if(in_array($picNames[4], $oldFiles)){
                continue;
            } else {
                $manage->savePicturesToPc($path.$picNames[4], $largePic);
            }
        }

        //SORTS ARRAYS FOR COMPARISON
        sort($oldFiles);
        sort($newFiles);

        //DELETES OLD PICTURES WHEN NEW ONES ARE DOWNLOADED
        if($oldFiles != $newFiles){
            $manage->deleteOldPictures($oldFiles, $path);
        }

    } else {
        //DELETS ALL PICTURES IF INPUT IS WRONG SINCE IT'S BETTER TO NOT KEEP THEM
        while (false !== ($file = readdir($handle))) {
            $manage->deleteOldPictures();
        }
        array_push($errors, "Wrong car URL");
    }
}

//Deletes pictures with a button click
if (!empty($_GET['remove'])) {
    $oldFiles = [];
    $manage->deleteOnClick($path);
}
