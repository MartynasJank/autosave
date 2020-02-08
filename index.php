<?php
    require_once 'includes/header.php';
    include('includes/saving.php');
    $files = scandir($path);
?>

<div class="container">
    <form method="GET" action="/autosave">
        <div class="form-group">
            <label for="carUrl">Car URL</label>
            <input type="text" name="carUrl" class="form-control" placeholder="Enter Car URL">
        </div>
    <input type="hidden" name="table" value="<?php echo $matches ?>">
    <button type="submit" class="btn btn-primary search">Submit</button>
    <input type="submit" class="btn btn-danger" name="remove" value="Delete">
    </form>
</div>

<!-- DISPLAYS ERRORS -->
<?php
if(!empty($errors)){
    ?>
        <ul>
            <?php foreach ($errors as $error) {
                echo("<li>".$error."</li>");
            } ?>
        </ul>
    <?php
}
// MAKES A GALLERY OF PICTURES FOUND WITH DOWNLOAD LINKS
if (count($files) > 2) {
    ?>
    <div class="result"><a href="#" class="martynasdownload">Download all pictures</a></div>
    <div class="result">Found <?php echo count($files)-2; ?> pictures.</div>
    <div class="popup" style="display: none">
        <div class="arrow left"><img src="img/arrowL.png"></div>
        <div class="arrow right"><img src="img/arrowR.png"></div>
        <div id="container" class="container">
            <div class="close"><img src="img/close.png"></div>
            <img class="main" src="">
            <div class="number"></div>
        </div>
    </div>
    <div class="gallery" id="gallery">
        <div class="inner">
            <?php
                foreach ($files as $file) {
                    if ($file != '.' && $file != '..') { ?>
                    <div class="bigpic">
                        <div class="picture">
                            <img src="<?php echo "/autosave/cars/".$file; ?>">
                        </div>
                        <div class="download"><a class="piclink" download href="cars/<?php echo $file; ?>" _blank>Download</a></div>
                    </div>
                    <?php }
                }
             ?>
        </div>
    </div>
<?php
}
require_once 'includes/footer.php';
?>
