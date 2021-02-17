<?php

use voku\Html2Text\Html2Text;
use VincoWeb\FileInfo\FileInfo;
use Simtabi\Laranail\Supports\YouTube;
use Cocur\Slugify\Slugify;


if (!function_exists('slugify')) {

    function slugify(string $string, $sep = '_', array $args = []): string
    {
        return (new Slugify($args))->slugify($string, $sep);
    }

}

if (!function_exists('nl2br')) {

    function nl2br($str)
    {
        return str_replace("\n", '<br />', $str);
    }
}

if (!function_exists('html2text')) {
    /**
     * @param $content
     * @return mixed
     */
    function html2text($content){
        $html = new Html2Text($content);
        return $html->getText();
    }
}

if (!function_exists('drop_cap')) {
    /**
     * @param $string
     * @return null|string|string[]
     */
    function drop_cap($string){
        return preg_replace('/^([\<\sa-z\d\/\>]*)(([a-z\&\;]+)|([\"\'\w]))/', '$1<b>$2</b>', $string);
    }
}

if (!function_exists('truncate')) {
    /**
     * @param $string
     * @param int $length
     * @return null|string|string[]
     */
    function truncate($string, $length = 150) {
        $limit = abs((int)$length);
        if(strlen($string) > $limit) {
            $string = preg_replace("/^(.{1,$limit})(\s.*|$)/s", '\1...', $string);
        }
        return $string;
    }
}

if (!function_exists('clean_it')) {

    function clean_it($string): string
    {
        return strtolower(trim($string));
    }

}

if (!function_exists('json_encode_prettify')) {
    function json_encode_prettify($data)
    {
        return json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}

if (!function_exists('youtube_iframe')) {
    function youtube_iframe($url, $width, $height, $class = 'clearfix')
    {
        $youtube = new YouTube($url);
        if ($youtube->getId()) {
            return '
        <div class="'.$class.'">
          <iframe width="'.$width.'" height="'.$height.'" src="'.$youtube->getEmbedUrl().'?rel=0&amp;controls=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>
        </div>
        ';
        }
        return false;
    }
}

if (!function_exists('social_links')) {
    /**
     * @param $social
     * @param $title
     * @return string
     */
    function social_links($social, $title)
    {
        $link = "";
        $URL  = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        switch ($social) {
            case "facebook":
                $link = "https://www.facebook.com/sharer/sharer.php?u=" . urlencode($URL);
                break;
            case "twitter":
                $link = "https://twitter.com/intent/tweet?text=$title&url=" . urlencode($URL);
                break;
            case "google":
                $link = "https://plus.google.com/share?url=" . urlencode($URL);
                break;
            case "linkedin":
                $link = "http://www.linkedin.com/shareArticle?mini=true&url=" . urlencode($URL) . "&title=$title";
                break;
            case "tumblr":
                $link = "http://www.tumblr.com/share/link?url=" . urlencode($URL);
                break;
        }

        return $link;
    }
}

