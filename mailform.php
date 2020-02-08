<?php
    session_start();
    require_once 'includes/header.php';
?>
<div class="container">
    <form method="post" action="mail.php" enctype="multipart/form-data">
        <div class="form-group">
            <input type="text" name="to" class="form-control" placeholder="Gavėjas">
        </div>
        <div class="form-group">
            <input type="text" name="subject" class="form-control" placeholder="Tema">
        </div>
        <div class="form-group">
            <textarea id="body" name="body" id="textarea">
                <?php if(isset($_SESSION['table'])){
                    echo $_SESSION['table'];
                } ?>
            </textarea>
        </div>
        <div class="form-group">
        <input name="userfile[]" type="file" multiple="multiple">
        </div>
        <button type="submit"name="submit" id="submit">Submit</button>
    </form>
</div>
<?php
    require_once 'includes/footer.php';
?>
