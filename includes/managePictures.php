<?php

class ManagePictures{

    protected function deletePictures($file, $path){
        if (preg_match('/.(png|jpg)$/', $file)) {
            unlink($path.$file);
            return true;
        }
        return false;
    }

    public function deleteOnClick($path){
        $handle = opendir($path);
        while (false !== ($file = readdir($handle))) {
            $this->deletePictures($file, $path);
        }
        header('Location: /autosave');
    }

    public function deleteOldPictures($oldFiles, $path){
        foreach ($oldFiles as $oldFile) {
            $this->deletePictures($oldFile, $path);
        }
    }

    public function savePicturesToPc($savePath, $pic){
        $fp = fopen($savePath, 'w+');
        if ($fp === false) {
            throw new Exception('Could not open: ' . $saveTo);
        }
        $ch = curl_init($pic);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        fclose($fp);
        // header('Location: /autosave');
    }
}


