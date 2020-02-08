<?php
class Table{
    protected $content;

    function __construct($content)
    {
        $this->content = $content;
    }

    public function get(){
        if(preg_match_all('/<h3 style.*?<\/table>/s', $this->content, $tableArray)){
            $table = $tableArray[0][0];
        }

        preg_match_all('/<img.+?src=[\"\'](.+?)[\"\'].+>/', $table, $src);
        $replacement = array();
        $replacement[0] = "http://balticautoshipping.com/app/assets/images/lt.png";
        $replacement[1] = "http://balticautoshipping.com/app/assets/images/key.png";
        $replacement[2] = "http://balticautoshipping.com/app/assets/images/page.png";

        return $newMessage = str_replace($src[1], $replacement, $table);
    }
}

