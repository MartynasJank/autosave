<?php
session_start();
// echo $_SERVER['DOCUMENT_ROOT'];
$errors = [];
$path = $_SERVER['DOCUMENT_ROOT']."/autosave/cars/";
$files = scandir($path);

function deletePictures($file, $path){
    if (preg_match('/.(png|jpg)$/', $file)) {
        unlink($path.$file);
    }
}

if (!empty($_GET['remove'])) {
    $oldFiles = [];
    $handle = opendir($path);
    while (false !== ($file = readdir($handle))) {
        deletePictures($file, $path);
    }
    header('Location: /autosave');
}

if (empty($_GET['carUrl'])) {

} else {
    exec('start "" "'.$path.'"');
     //The username or email address of the account.
     define('USERNAME', ''); // ENTER USERNAME

     //The password of the account.
     define('PASSWORD', ''); // ENTER PASSWORD

     //Set a user agent. This basically tells the server that we are using Chrome ;)
     define('USER_AGENT', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.2309.372 Safari/537.36');

     //Where our cookie information will be stored (needed for authentication).
     define('COOKIE_FILE', realpath("./cookie.txt"));

     //URL of the login form.
     define('LOGIN_FORM_URL', 'http://balticautoshipping.com/app/index.php/auth');

     //Login action URL. Sometimes, this is the same URL as the login form.
     define('LOGIN_ACTION_URL', 'http://balticautoshipping.com/app/index.php/auth/login');

     //An associative array that represents the required form fields.
     //You will need to change the keys / index names to match the name of the form
     //fields.
     $postValues = [
        'username' => USERNAME,
        'password' => PASSWORD,
        'submit' => "Login"
    ];

    //MAKES ARRAY OF EXISTING FILES
    $newFiles = [];
    $oldFiles = [];
    $handle = opendir($path);

    //Initiate cURL.
    $curl = curl_init();

    //Set the URL that we want to send our POST request to. In this
    //case, it's the action URL of the login form.
    curl_setopt($curl, CURLOPT_URL, LOGIN_ACTION_URL);

    //Tell cURL that we want to carry out a POST request.
    curl_setopt($curl, CURLOPT_POST, true);

    //Set our post fields / date (from the array above).
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postValues));

    //We don't want any HTTPS errors.
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    //Where our cookie details are saved. This is typically required
    //for authentication, as the session ID is usually saved in the cookie file.
    curl_setopt($curl, CURLOPT_COOKIEJAR, COOKIE_FILE);

    //Sets the user agent. Some websites will attempt to block bot user agents.
    //Hence the reason I gave it a Chrome user agent.
    curl_setopt($curl, CURLOPT_USERAGENT, USER_AGENT);

    //Tells cURL to return the output once the request has been executed.
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    //Allows us to set the referer header. In this particular case, we are
    //fooling the server into thinking that we were referred by the login form.
    curl_setopt($curl, CURLOPT_REFERER, LOGIN_FORM_URL);

    //Do we want to follow any redirects?
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);

    //Execute the login request.
    curl_exec($curl);

    //Check for errors!
    if (curl_errno($curl)) {
     throw new Exception(curl_error($curl));
    }

    //We should be logged in by now. Let's attempt to access a password protected page
    curl_setopt($curl, CURLOPT_URL, $_GET['carUrl']);

    //Use the same cookie file.
    curl_setopt($curl, CURLOPT_COOKIEJAR, COOKIE_FILE);

    //Use the same user agent, just in case it is used by the server for session validation.
    curl_setopt($curl, CURLOPT_USERAGENT, USER_AGENT);

    //We don't want any HTTPS / SSL errors.
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    //Execute the GET request and print out the result.
    $content = curl_exec($curl);
    // GRABS TABLE
    if(preg_match_all('/<h3 style.*?<\/table>/s', $content, $tableArray)){
        $table = $tableArray[0][0];
    }

    preg_match_all('/<img.+?src=[\"\'](.+?)[\"\'].+>/', $table, $src);
    $replacement = array();
    $replacement[0] = "http://balticautoshipping.com/app/assets/images/lt.png";
    $replacement[1] = "http://balticautoshipping.com/app/assets/images/key.png";
    $replacement[2] = "http://balticautoshipping.com/app/assets/images/page.png";

    $newMessage = str_replace($src[1], $replacement, $table);

    $_SESSION['table'] = $newMessage;

    if (preg_match_all("/(http:\/\/media.balticautoshipping.com.s3.amazonaws.com\/......\/............_......\....)/", $content, $output)) {
        $matches = $output[0];
    }

    if(!empty($matches)){
        if ($handle) {
            while (false !== ($file = readdir($handle))) {
                if ((time()-filemtime($path.$file)) > 600) {
                    deletePictures($file, $path);
                } else {
                    if (preg_match('/\.(jpg|png)$/', $file)) {
                        array_push($oldFiles, $file);
                    }
                }
            }
        }

        foreach ($matches as $pic) {
        $fileUrl = str_replace("medium", "large", $pic);
        $names = explode('/', $fileUrl);
        array_push($newFiles, $names[4]);
        //SKIPS CODE BELLOW IF PICTURES ALREADY ARE SAVED
        if(in_array($names[4], $oldFiles)){
            continue;
        } else {
            //CODE THAT GETS SKIPPED IF PICTURES ARE SAVED
            $saveTo = $path.$names[4];
            $fp = fopen($saveTo, 'w+');
            if ($fp === false) {
                throw new Exception('Could not open: ' . $saveTo);
            }
            $ch = curl_init($fileUrl);
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);
            curl_exec($ch);
            if (curl_errno($ch)) {
                throw new Exception(curl_error($ch));
            }
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            fclose($fp);
            if ($statusCode == 200) {
                //echo 'Downloaded!';
            } else {
                echo "Status Code: " . $statusCode;
            }
            header('Location: /autosave');
        }
     }
     //SORTS ARRAYS FOR COMPARISON
    sort($oldFiles);
    sort($newFiles);

        //DELETES OLD PICTURES WHEN NEW ONES ARE DOWNLOADED
        if($oldFiles != $newFiles){
            foreach ($oldFiles as $oldFile) {
                deletePictures($oldFile, $path);
            }
        }
        //DELETS ALL PICTURES IF INPUT IS WRONG SINCE IT'S BETTER TO NOT KEEP THEM
    } else {
        if ($handle) {
            while (false !== ($file = readdir($handle))) {
                deletePictures($file, $path);
            }
        }
        array_push($errors, "Wrong car URL");
    }
    // header('Location: /autosave');
}
