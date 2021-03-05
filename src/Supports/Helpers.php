<?php

namespace Simtabi\Laranail\Supports;

use Cocur\Slugify\Slugify;
use Simtabi\Patch\Support\Exception\CatchThis;


class Helpers
{

    /**
     * Create class instance
     *
     * @version      1.0
     * @since        1.0
     */
    private static $instance;
    public static function getInstance() {
        if (isset(self::$instance)) {
            return self::$instance;
        } else {
            self::$instance = new static();
            return self::$instance;
        }
    }

    private function __construct() {}
    private function __clone() {}


    private static $slugConfig = [
        'lowercase' => true
    ];

    public static function slug($string){
        $config  = self::$slugConfig;
        $init = new Slugify($config);
        return $init->slugify($string);
    }


    public static function ifIsSet($var, $getKey = false, $default = null){

        // some logic
        if (!isset($_REQUEST[$var])){
            $request = null;
        }else{
            if (Validate::isEmpty($var)){
                $request = null;
            }else{
                $request = $_REQUEST[$var];
            }
        }

        // if return key
        if(true === $getKey)
        {
            return !Validate::isEmpty($var) ? $var : null;
        }

        // return default value if empty request
        return !Validate::isEmpty($request) ? $request : $default;
    }


    public static function getCheckboxStatus($name){
        return isset($_REQUEST[$name]) ? ' checked="true" ' : '';
    }








    public static function base64JsonEncode($str, $base64 = true){

        if(true === $base64){
            return base64_encode(json_encode($str));
        }
        return json_encode($str);
    }

    public static function base64JsonDecode($str, $base64 = true){

        if(true === $base64){
            return json_decode(base64_decode($str), true);
        }
        return json_encode($str, true);
    }

    public static function base64ImageEncode($path){
        if(!empty($path) && (file_exists($path))){
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            return 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
        return false;
    }

    public static function jsonPrettyPrint($data, $html = false, $raw_array = true, $config = false) {

        if($raw_array){
            $json = json_encode($data, $config);
        }elseif($raw_array == false && (Validate::isJSON($data))){
            $json = $data;
        }else{
            return false;
        }

        $out = ''; $nl = "\n"; $cnt = 0; $tab = 4; $len = strlen($json); $space = ' ';
        if($html) {
            $space = '&nbsp;';
            $nl    = '<br/>';
        }

        $k = strlen($space)?strlen($space):1;
        for ($i=0; $i<=$len; $i++) {
            $char = substr($json, $i, 1);
            if($char == '}' || $char == ']') {
                $cnt --;
                $out .= $nl . str_pad('', ($tab * $cnt * $k), $space);
            } else if($char == '{' || $char == '[') {
                $cnt ++;
            }
            $out .= $char;
            if($char == ',' || $char == '{' || $char == '[') {
                $out .= $nl . str_pad('', ($tab * $cnt * $k), $space);
            }
            if($char == ':') {
                $out .= ' ';
            }
        }

        return $out;
    }



    public static function generateNumber($length = '12', $power = null){
        $output  = null;
        $pattern = "0123456789";

        if($power !== null){
            srand((double)microtime()*1000000*$power);
        }else{
            srand((double)microtime()*1000000);
        }

        for($i = 0; $i <$length; $i++) {
            $output.= $pattern[rand()%strlen($pattern)];
        }
        return $output;
    }

    public static function generateString($length = '12', $power = null, $addSpecialCharacters = false){

        $characters = "#$%^&*()+=-[]';,./{}|:<>?~";
        $pattern    = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789".(true === $addSpecialCharacters ? $characters : '');
        $pattern    = trim($pattern);
        $output     = null;

        if(!empty($power)){
            srand((double)microtime()*1000000*$power);
        }
        else{
            srand((double)microtime()*1000000);
        }

        for($i = 0; $i <$length; $i++) {
            $output.= $pattern[rand()%strlen($pattern)];
        }
        return $output;
    }



    public static function getGravatar($email, $size = 32 , $is_https = false ){
        if ( $is_https ) {
            $url = 'https://secure.gravatar.com/';
        } else {
            $url = 'http://www.gravatar.com/';
        }
        $url .= 'avatar/' . md5( $email ) . '?s=' . (int) abs( $size );
        return $url;
    }





    public static function wordCountUTF8($sentence) {
        return count(preg_split('~[^\p{L}\p{N}\']+~u', strip_tags( $sentence ) ));
    }

    public static function approximateReadingTime($story, $spacing = null, $short_form = false) {

        // if empty story
        if(empty($story) && !is_string($story)){
            return false;
        }

        // escape
        $story = trim( htmlspecialchars($story, ENT_QUOTES, 'UTF-8') );

        // set variables
        $word_count = self::wordCountUTF8($story);
        $minutes    = floor( $word_count / 120 );
        $seconds    = floor( $word_count % 120 / ( 120 / 60 ) );

        $min_str    = true === $short_form ? 'min' :'minute';
        $sec_str    = true === $short_form ? 'sec' :'second';
        $var_str    = 's';

        if(is_null($spacing)){
            $spacing = '';
        }else{
            $spacing = (!empty($spacing) ? $spacing : ' ');
        }

        $min_var = (($minutes == 1) ? false : true);
        $sec_var = (($seconds == 1) ? false : true);

        if ( 1 <= $minutes ) {
            $set_reading_minutes  = $minutes . $spacing . ucwords(strtolower($min_str . ((true === $min_var ? $var_str : null))));
            $set_reading_seconds  = $seconds . $spacing . ucwords(strtolower($sec_str . ((true === $sec_var ? $var_str : null))));
        } else {
            $set_reading_minutes  = $minutes . $spacing . ucwords(strtolower($min_str . "'" . ((true === $min_var ? $var_str : null))));
            $set_reading_seconds  = $seconds . $spacing . ucwords(strtolower($sec_str . "'" . ((true === $sec_var ? $var_str : null))));
        }

        $data['time'] = TypeConverter::toObject(array(
            'formatted' => array(
                'minutes' => html_entity_decode($set_reading_minutes),
                'seconds' => html_entity_decode($set_reading_seconds),
            ),
            'raw'      => array(
                'minutes' => $minutes,
                'seconds' => $seconds,
            ),

        ));

        $data['words']   = TypeConverter::toObject(array(
            'total'   => $word_count,
            'chars'   => strlen(strip_tags($story)),
            'article' => $story,
        ));
        return $data;
    }

    public static function formatArticleReadCount($readCounts, $str = 'Read', $multiple = 's', $spacing = null) {

        // validate
        if(true !== Validate::isInteger($readCounts)){
            return false;
        }

        if(is_null($spacing)){
            $spacing = '';
        }else{
            $spacing = (!empty($spacing) ? $spacing : "&nbsp;");
        }

        if($readCounts !== 0){
            if($readCounts > 1){
                $data['formatted'] = $readCounts . $spacing . ucfirst($str.$multiple);
            }elseif($readCounts === 1){
                $data['formatted'] = $readCounts . $spacing . ucfirst($str);
            }else{
                $data['formatted'] = $readCounts . $spacing . ucfirst($str);
            }
        }else{

            if(false === $readCounts || ($readCounts === 0)){
                $data['formatted'] = $readCounts . $spacing . ucfirst($str.$multiple);
            }else{
                $data['formatted'] = $readCounts . $spacing . ucfirst($str);
            }

        }
        $data['raw'] = $readCounts;

    }

    public static function summarizeText($text, $length = 400, $append = '...', $splitOnWholeWords = true){
        // https://www.drupal.org/node/46415
        if (strlen($text) <= $length) return $text;
        $split = 0;
        if ($splitOnWholeWords) {
            $i = 0; $lplus1 = $length + 1;
            while (($i = strpos($text, ' ', $i + 1)) < $lplus1) {
                if ($i === false) break;
                $split = $i;
            }
        }
        else{
            $split = $length;
        }

        return substr($text, 0, $split).$append;
    }

    public static function slugify($text, $replace=array(), $delimiter='-') {
        $str = $text;
        if( !empty($replace) ) {
            $str = str_replace((array)$replace, ' ', $str);
        }

        $text = $str;
        static $translit = array(
            'a'    => '/[ÀÁÂẦẤẪẨÃĀĂẰẮẴȦẲǠẢÅÅǺǍȀȂẠẬẶḀĄẚàáâầấẫẩãāăằắẵẳȧǡảåǻǎȁȃạậặḁą]/u',
            'b'    => '/[ḂḄḆḃḅḇ]/u',     'c' => '/[ÇĆĈĊČḈçćĉċčḉ]/u',
            'd'    => '/[ÐĎḊḌḎḐḒďḋḍḏḑḓð]/u',
            'e'    => '/[ÈËÉĒĔĖĘĚȄȆȨḔḖḘḚḜẸẺẼẾỀỂỄỆèéëēĕėęěȅȇȩḕḗḙḛḝẹẻẽếềểễệ]/u',
            'f'    => '/[Ḟḟ]/u',         'g' => '/[ĜĞĠĢǦǴḠĝğġģǧǵḡ]/u',
            'h'    => '/[ĤȞḢḤḦḨḪĥȟḣḥḧḩḫẖ]/u',    'i' => '/[ÌÏĨĪĬĮİǏȈȊḬḮỈỊiìïĩīĭįǐȉȋḭḯỉị]/u',
            'j'    => '/[Ĵĵǰ]/u',        'k' => '/[ĶǨḰḲḴKķǩḱḳḵ]/u',
            'l'    => '/[ĹĻĽĿḶḸḺḼĺļľŀḷḹḻḽ]/u',   'm' => '/[ḾṀṂḿṁṃ]/u',
            'n'    => '/[ÑŃŅŇǸṄṆṈṊñńņňǹṅṇṉṋ]/u',
            'o'    => '/[ÒÖŌŎŐƠǑǪǬȌȎȪȬȮȰṌṎṐṒỌỎỐỒỔỖỘỚỜỞỠỢØǾòöōŏőơǒǫǭȍȏȫȭȯȱṍṏṑṓọỏốồổỗộớờởỡợøǿ]/u',
            'p'    => '/[ṔṖṕṗ]/u',       'r' => '/[ŔŖŘȐȒṘṚṜṞŕŗřȑȓṙṛṝṟ]/u',
            's'    => '/[ŚŜŞŠȘṠṢṤṦṨſśŝşšșṡṣṥṧṩ]/u',  'ss'  => '/[ß]/u',
            't'    => '/[ŢŤȚṪṬṮṰţťțṫṭṯṱẗ]/u',        'th'  => '/[Þþ]/u',
            'u'    => '/[ÙŨŪŬŮŰŲƯǓȔȖṲṴṶṸṺỤỦỨỪỬỮỰùũūŭůűųưǔȕȗṳṵṷṹṻụủứừửữựµ]/u',
            'v'    => '/[ṼṾṽṿ]/u',       'w' => '/[ŴẀẂẄẆẈŵẁẃẅẇẉẘ]/u',
            'x'    => '/[ẊẌẋẍ×]/u',      'y' => '/[ÝŶŸȲẎỲỴỶỸýÿŷȳẏẙỳỵỷỹ]/u',
            'z'    => '/[ŹŻŽẐẒẔźżžẑẓẕ]/u',
            //combined letters and ligatures:
            'ae'   => '/[ÄǞÆǼǢäǟæǽǣ]/u',                'oe'  => '/[Œœ]/u',
            'dz'   => '/[ǄǅǱǲǆǳ]/u',
            'ff'   => '/[ﬀ]/u',  'fi'  => '/[ﬃﬁ]/u',   'ffl' => '/[ﬄﬂ]/u',
            'ij'   => '/[Ĳĳ]/u', 'lj'  => '/[Ǉǈǉ]/u',  'nj'  => '/[Ǌǋǌ]/u',
            'st'   => '/[ﬅﬆ]/u', 'ue'  => '/[ÜǕǗǙǛüǖǘǚǜ]/u',
            //currencies:
            'eur'  => '/[€]/u',  'cents' => '/[¢]/u',  'lira'  => '/[₤]/u',  'dollars' => '/[$]/u',
            'won'  => '/[₩]/u',    'rs'  => '/[₨]/u',  'yen'   => '/[¥]/u',  'pounds'  => '/[£]/u',
            'pts'  => '/[₧]/u',
            //misc:
            'degc' => '/[℃]/u', 'degf'  => '/[℉]/u',
            'no'   => '/[№]/u',    'tm'  => '/[™]/u'
        );
        //do the manual transliteration first
        $str = preg_replace (array_values ($translit), array_keys ($translit), $str);

        //flatten the text down to just a-z0-9 and dash, with underscores instead of spaces
        $str = preg_replace (
        //remove punctuation  //replace non a-z //deduplicate //trim underscores from start & end
            array('/\p{P}/u',  '/[^A-Za-z0-9]/', '/-{2,}/', '/^-|-$/'),
            array('-',           '-',              '-',       '-'),

            //attempt transliteration with PHP5.4's transliteration engine (best):
            //(this method can handle near anything, including converting chinese and arabic letters to ASCII.
            // requires the 'intl' extension to be enabled)
            function_exists ('transliterator_transliterate') ? transliterator_transliterate (
            //split unicode accents and symbols, e.g. "Å" > "A°":
                'NFKD; '.
                //convert everything to the Latin charset e.g. "ま" > "ma":
                //(splitting the unicode before transliterating catches some complex cases,
                // such as: "㏳" >NFKD> "20日" >Latin> "20ri")
                'Latin; '.
                //because the Latin unicode table still contains a large number of non-pure-A-Z glyphs (e.g. "œ"),
                //convert what remains to an even stricter set of characters, the US-ASCII set:
                //(we must do this because "Latin/US-ASCII" alone is not able to transliterate non-Latin characters
                // such as "ま". this two-stage method also means we catch awkward characters such as:
                // "㏀" >Latin> "kΩ" >Latin/US-ASCII> "kO")
                'Latin/US-ASCII; '.
                //remove the now stand-alone diacritics from the string
                '[:Nonspacing Mark:] Remove; '.
                //change everything to lowercase; anything non A-Z 0-9 that remains will be removed by
                //the letter stripping above
                'Lower',
                $str)

                //attempt transliteration with iconv: <php.net/manual/en/function.iconv.php>
                : strtolower (function_exists ('iconv') ? str_replace (array ("'", '"', '`', '^', '~'), '', strtolower (
            //note: results of this are different depending on iconv version,
            //      sometimes the diacritics are written to the side e.g. "ñ" = "~n", which are removed
                iconv ('UTF-8', 'US-ASCII//IGNORE//TRANSLIT', $str)
            )) : $str)
        );

        //old iconv versions and certain inputs may cause a nullstring. don't allow a blank response
        if(!$str || $str =="_" || $str == "-"){
            $str = preg_replace("/[^A-Za-z0-9 -]/", '', $text);
            $str = preg_replace("/[\/_|+ -]+/", $delimiter, $str);
            $str = str_replace("'","",$str);
            $str = str_replace('"','',$str);
            $str = strtolower(rtrim(trim($str,'-'), '-'));
            if(empty($str) || $str =="_" || $str == "-"){
                return self::generateString(5);
            }else{
                return $str;
            }
        }else{
            return $str;
        }
    }


    public static function formatTags($string, $splitter = ',', $notWanted = null){
        $notWanted = empty($notWanted) ? '\\/:*?"<>;,|' : $notWanted;
        $string    = str_replace(str_split($notWanted), $splitter, $string);
        return preg_replace('{(.)\1+}','$1',$string);
    }

    public static function formatPrice($amount, $currency_iso = 'KES', $locale_iso = 'en_GB'){

        $amount   = floatval($amount);
        $currency = $currency_iso;

        $fmt  = new \NumberFormatter($locale_iso,  \NumberFormatter::CURRENCY);
        $fmt->setTextAttribute(\NumberFormatter::CURRENCY_CODE, 'EUR');
        $fmt->setAttribute(\NumberFormatter::FRACTION_DIGITS, 0);
        return $fmt->formatCurrency($amount, $currency) . PHP_EOL;
    }




    public static function makeItReadable($str, $type = 1){
        switch ($type){
            case 1 :
                $words = ucfirst(strtolower(str_replace('_', ' ', $str)));
                break;

            case 2 :
                $words = strtolower(str_replace('_', ' ', $str));
                break;

            case 3 :
                $words = strtoupper(str_replace('_', ' ', $str));
                break;

            case 4 :
                $str = self::multipleExplode ($str, $delimiters = '-_');
                $_w  = '';
                foreach ($str as $i => $j){ $_w .= ucfirst($j) . ' ';}
                $words = trim($_w);
                break;

            default :
                $words = ucwords(strtolower(str_replace('_', ' ', $str)));
                break;
        }

        return $words;
    }

    public static function bolderHtmlString($str, $type = 1){
        return '<strong>'. self::makeItReadable($str, $type) .'</strong>';
    }

    public static function bolderSprintf($argument, $wrapper){
        $argument = '<strong>'. ucwords(strtolower(str_replace('_', ' ', $argument))) .'</strong>';
        return sprintf($wrapper, $argument);
    }

    public static function debugArray($array, $echo = true ) {
        $output = '<br><pre>' . print_r( $array, true ) . '</pre><br>';
        if(true === $echo){
            echo $output;
        }else{
            return $output;
        }
    }







    public static function zeroFillNumber ($num, $zerofill = '1'){
        return str_pad($num, $zerofill, '0', STR_PAD_LEFT);
    }


    public static function randomizeData(array $array, $counter = 10){
        $output = [];

        if (is_array($array) && (($total = count($array)) > 0)) {

            // shuffle data
            shuffle($array);

            foreach ($array as $key => $data) {
                if(($counter <= $total) && $key <= $counter){
                    $output[$key] = $data;
                }
            }

            return $output;

        }

        return false;
    }

    /**
     * @param $key
     * @param $data
     * @return array|null|mixed
     */
    public static function getFromArray($key, $data){
        if (Validate::isEmpty($key)){
            return $data;
        }
        else{
            $parsed = explode('.', $key);
            $data   = is_object($data) ? TypeConverter::toArray($data) : $data;
            while ($parsed) {
                $next = array_shift($parsed);
                if (isset($data[$next])) {
                    $data = $data[$next];
                } else {
                    return null;
                }
            }
            return $data;
        }
    }

    public static function putToArray($key, $value, $data){
        $parsed =  explode('.', $key);
        $array  =& $data;
        while (count($parsed) > 1) {
            $next = array_shift($parsed);
            if ( ! isset($array[$next]) || ! is_array($array[$next])) {
                $array[$next] = [];
            }
            $array =& $array[$next];
        }
        $array[array_shift($parsed)] = $value;
        return $array;
    }

    public static function pushToTopOfArray($value, $array){
        if(is_array($array) && !empty($array)){
            // push this error to the very beginning
            array_unshift($array, $value);
        } else{
            $array = $value;
        }

        return $array;
    }




    public static function flattenArray($array){
        $array = TypeConverter::toArray($array);
        if (!is_array($array)){
            return array();
        }

        $result = array();
        foreach ($array as $key => $value){
            if (is_array($value)){
                $result = array_merge($result, self::flattenArray($value));
            }
            else{
                $result[$key] = $value;
            }
        }
        return $result;
    }

    public static function shuffleAssociativeArray($list) {

        // @author http://stackoverflow.com/questions/4102777/php-random-shuffle-array-maintaining-key-value

        if (!is_array($list)) return $list;

        $keys = array_keys($list);
        shuffle($keys);
        $random = array();
        foreach ($keys as $key) {
            $random[$key] = $list[$key];
        }
        return $random;
    }


    public static function arrayGroupBy(array $arr, callable $keySelector) {
        // @author http://codereview.stackexchange.com/questions/23919/generic-array-group-by-using-lambda
        $result = array();
        foreach ($arr as $i) {
            $key = call_user_func($keySelector, $i);
            $result[$key][] = $i;
        }
        return $result;
    }

    public static function arrayReOrderAndGroup($order, $array) {

        $newOrder = array ();
        for($i=0;$i<count($array); $i++) {
            foreach($order as $category => $user){
                foreach($order[$category] as $key => $username){
                    if((strtolower($array[$i]->username)) == (strtolower($order[$category][$key]))){
                        $newOrder[$category][$key] = $array[$i];
                        continue;
                    }
                }

                // sort the internal structure
                if((!empty($newOrder[$category])) && (is_array($newOrder[$category]))){
                    ksort($newOrder[$category]);
                }

            }
        }

        ksort($newOrder);
        return array_values($newOrder);
    }


    public static function readFromArray($key, $array){

        // convert from object if it is
        $array = TypeConverter::toArray($array);

        // lets walk through the array
        $parsed = explode('.', $key);
        $data   = TypeConverter::toArray($array);
        while ($parsed) {
            $next = array_shift($parsed);
            if (isset($data[$next])) {
                $data = $data[$next];
            }
            else {
                return null;
            }
        }
        return $data;
    }

    public static function isInArray($needle, $haystack){
        $found = false;
        foreach ($haystack as $key => $item) {
            if ($needle === $key) {
                $found = true;
                break;
            } elseif (is_array($item)) {
                $found = self::isInArray($needle, $item);
                if($found) {
                    break;
                }
            }
        }
        return $found;
    }


    public static function partitionArray($data, $sections = 5){

        $built = array();
        $total = 0;
        $parts = 0;

        if(!empty($data) && is_array($data)){

            $total = count($data);
            if(($sections !== 0 && ($sections < $total)) && (($total !== 0) || ($total !== false))){
                for ( $i = 0; $i < $total; $i++) {
                    if ( !($i % $sections) ) {
                        $parts++;
                    }
                    $built[$parts][] = $data[$i];
                }
            }

        }
        return array(
            'parts' => $parts, // last partition count
            'total' => $total, // total data count
            'data'  => $built, // partitioned data
        );
    }


    /**
     * method masks the username of an email address
     *
     * @param string $number the number address to mask
     * @param int $mask_percentage the percent of the number to mask
     * @param string $mask_char the character to use to mask with
     * @return $result
     *
     * @author   Imani Manyara <info@imanimanyara.com>
     */
    public static function maskTelephone($number, $mask_percentage = 60, $mask_char = '*'){

        //username parts mask
        $number_length       = strlen( $number );
        $number_mask_count   = floor( $number_length * $mask_percentage /100 );
        $number_offset       = floor( ( $number_length - $number_mask_count ) / 2 );
        $masked_number       = substr( $number, 0, $number_offset )
            .str_repeat( $mask_char, $number_mask_count )
            .substr( $number, $number_mask_count+$number_offset );

        //return results
        return( $masked_number );

    }


    public static function unFormatInternationalPhoneNumber($number, $append_plus = true){
        // Strip a Phone number of all non alphanumeric characters
        $clean  = preg_replace('/(\W*)/', '', $number);
        $clean  = preg_replace('/\D+/',  '', $clean);
        return true === $append_plus ? '+' . $clean : $clean;
    }

    /**
     * Function getFirstSentence
     *
     *
     * @param $content
     * @return mixed
     *
     * http://monchito.com/blog/regex-php-snippets-for-seo-purposes
     * Handcrafted with LOVE & PRIDE in NAIROBI, KENYA
     * @author Imani Manyara <www.imanimanyara.com>
     */
    public static function getFirstSentence($content) {
        $content = html_entity_decode(strip_tags($content));
        $pos = strpos($content, '.');
        if ($pos === false) {
            return $content;
        } else {
            return substr($content, 0, $pos + 1);
        }
    }

    /**
     * Function neatTrim
     *
     *
     * @param $str
     * @param int $total
     * @param string $delimiter
     * @return string
     *
     * http://monchito.com/blog/regex-php-snippets-for-seo-purposes
     * Handcrafted with LOVE & PRIDE in NAIROBI, KENYA
     * @author Imani Manyara <www.imanimanyara.com>
     */
    public static function neatTrim($str, $total = 5, $delimiter='...') {
        $len = strlen($str);
        if ($len > $total) {
            preg_match('/(.{' . $total . '}.*?)\b/', $str, $matches);
            return rtrim($matches[1]) . $delimiter;
        }
        else {
            return $str;
        }
    }

    function array_set($key, $value, array &$data) {
        if ($key === null) {
            return null;
        }

        $keys = explode('.', $key);

        while (count($keys) > 1) {
            $key = array_shift($keys);

            if ( ! isset($data[$key]) || ! is_array($data[$key])) {
                $data[$key] = [];
            }

            $data = &$data[$key];
        }

        $data[array_shift($keys)] = $value;

        return $data;
    }

    public static function filterArray($data){
        $data = TypeConverter::buildArray($data);
        if (!is_array($data)){
            return null;
        }
        foreach ($data as $key => $datum){
            if (is_array($datum)){
                self::filterArray($datum);
            }else{
                if ( empty($datum) && strlen($datum) == 0 ) {
                    unset($data[$key]);
                }
            }
        }

        return $data;
    }


    public static function sentenceCase($text) {
        $text = preg_split('/([.?!]+)/', $text, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
        $out = '';
        foreach ($text as $key => $sentence) {
            $out .= ($key & 1) == 0 ? ucfirst(strtolower(trim($sentence))) : $sentence .' ';
        }
        return trim($out);
    }


    public static function sentenceCap($text, $breakpoint = '.') {
        $text = explode($breakpoint, $text);
        $out  = array();
        foreach ($text as $sentence) {
            $out[] = ucfirst(strtolower($sentence));
        }
        return implode($breakpoint, $out);
    }

    public static function removeSpecialChars($text){
        return preg_replace("/[^A-Za-z0-9 -]/", '', $text);
    }

    public static function trimSpacesAndWhiteSpaces($str, $stripSpecials = true){

        // remove spaces
        $str = str_replace(' ', '', $str);
        // remove white spaces
        $str = preg_replace('/\s+/', '', $str);

        return true === $stripSpecials ? self::removeSpecialChars($str) : $str;
    }

    public static function addOrdinalNumberSuffix($number) {

        // output variables
        $status = false;
        $errors = null;
        $ord    = null;

        try{

            if(!Validate::isInteger($number)){
                throw new CatchThis(Validate::getError());
            }else{
                if (!in_array(($number % 100), array(11,12,13))){
                    switch ($number % 10) {
                        case    1 : $ord = 'st'; break;
                        case    2 : $ord = 'nd'; break;
                        case    3 : $ord = 'rd'; break;
                        default;    $ord = 'th'; break;
                    }
                }
                $status = true;
            }

        }catch (CatchThis $e){
            $errors = $e->getMessage();
        }
        return TypeConverter::toObject(array(
            'status' => $status,
            'errors' => self::filterArray($errors),
            'data'   => array(
                'string'  => true === $status ? $number . $ord : null,
                'ordinal' => $ord,
                'number'  => $number,
            )
        ));
    }

    /**
     * Function multipleExplode
     *
     *
     * @param $string - has to be a Str
     * @param array $delimiters - has to be an Array
     * @return mixed
     *
     * Handcrafted with LOVE & PRIDE in NAIROBI, KENYA
     * @author Imani Manyara <www.imanimanyara.com>
     */
    public static function multipleExplode ($string, $delimiters = ',:-_') {
        return preg_split( "/[$delimiters]/", $string );
    }

    public static function emailEncode($email='info@domain.com', $linkText='Contact Us', $attrs ='class="emailencoder"' )
    {
        // remplazar aroba y puntos
        $email = str_replace('@', '&#64;', $email);
        $email = str_replace('.', '&#46;', $email);
        $email = str_split($email, 5);

        $linkText = str_replace('@', '&#64;', $linkText);
        $linkText = str_replace('.', '&#46;', $linkText);
        $linkText = str_split($linkText, 5);

        $part1 = '<a href="ma';
        $part2 = 'ilto&#58;';
        $part3 = '" '. $attrs .' >';
        $part4 = '</a>';

        // generamos el Javascript
        $encoded = '<script type="text/javascript">';
        $encoded .= "document.write('$part1');";
        $encoded .= "document.write('$part2');";
        foreach($email as $e)
        {
            $encoded .= "document.write('$e');";
        }
        $encoded .= "document.write('$part3');";
        foreach($linkText as $l)
        {
            $encoded .= "document.write('$l');";
        }
        $encoded .= "document.write('$part4');";
        $encoded .= '</script>';

        return $encoded;
    }

    public static function getTagCloud($str, $minFontSize = 12, $maxFontSize = 30 )
    {

        // output variables
        $status = false;
        $errors = null;
        $data   = array();

        try{


            $isString = Validate::isString($str);
            if(true !== $isString){
                throw new CatchThis($isString);
            }

            // Store frequency of words in an array
            $frequency = array();

            // Get individual words and build a frequency table
            foreach( str_word_count( $str, 1 ) as $word )
            {
                // For each word found in the frequency table, increment its value by one
                array_key_exists( $word, $frequency ) ? $frequency[ $word ]++ : $frequency[ $word ] = 0;
            }

            $minimumCount = min( array_values( $frequency ) );
            $maximumCount = max( array_values( $frequency ) );
            $spread       = $maximumCount - $minimumCount;
            $arrayTags    = array();

            $spread == 0 && $spread = 1;

            foreach( $frequency as $tag => $count )
            {
                $fontSize = $minFontSize + ( $count - $minimumCount ) * ( $maxFontSize - $minFontSize ) / $spread;
                $arrayTags[] = array(
                    'count'  => floor( $count ),
                    'size'   => floor( $fontSize ),
                    'tag'    => htmlspecialchars( stripslashes( $tag ) ),
                );
            }

            // set data and status
            if(!empty($arrayTags)){
                $status = true;
                $data   = $arrayTags;
            }

        }catch (CatchThis $e){
            $errors = $e->getMessage();
        }

        return TypeConverter::toObject(array(
            'status' => $status,
            'errors' => self::filterArray($errors),
            'data'   => $data,
        ));

    }


    public static function readSQLStateMessage($message){

        // https://gist.github.com/IlanFrumer/7888809

        // pattern one lookup
        $patternOne    = "/^SQLSTATE\[\w+\]:[^:]+:\s*(\d*)\s*(.*)/";
        $matchOneCount = preg_match($patternOne, $message, $matchOne);

        // pattern two lookup
        $patternTwo    = "/SQLSTATE\[(\w+)\] \[(\w+)\] (.*)/";
        $matchTwoCount = preg_match($patternTwo, $message, $matchTwo);

        // default message variables
        $pdoMessage = null;
        $sqlState   = null;
        $code       = null;

        // if match one has something
        if ($matchOneCount) {
            $pdoMessage     = $matchOne[2];
            $sqlState       = $matchOne[0];
            $code           = $matchOne[1];
        }else{
            if ($matchTwoCount) {
                $pdoMessage = $matchTwo[2];
                $sqlState   = $matchTwo[0];
                $code       = $matchTwo[1];
            }
        }

        return TypeConverter::toObject(array(
            'message' => $pdoMessage,
            'state'   => $sqlState,
            'code'    => !empty($code) ? $code : 0,
        ));
    }


    /**
     * Function encrypt
     *
     *
     * @param $input
     * @param string $method
     * @param int $iteration
     * @param bool $salt
     * @return string
     *
     * Handcrafted with LOVE & PRIDE in NAIROBI, KENYA
     * @author Imani Manyara <www.imanimanyara.com>
     */
    public static function encrypt($input, $method = "sha512", $iteration = 100, $salt = false)
    {
        // Concatenate the salt if there is any salt
        $input = ($salt == false) ? $input : $salt . $input;
        // Hashing the value
        $input = hash($method, $input);
        // If iteration is not false then we iterate it by $iteration times
        if($iteration !== false) {
            for($i = 1;$i <= $iteration; $i++) {
                $input = hash($method, $input);
            }
        }
        return $input;
    }













    public static function getReadableString($length = 8) {

        // output variable
        $output = "";

        // build alphabets list
        $alphabets = array(
            // all constants into an array
            'consonant' => array("b","c","d","f","g","h","j","k","l","m","n","p","r","s","t","v","w","x","y","z"),
            // all vowels into an array
            'vowels'    => array("a","e","i","o","u"),
        );

        //start with a consonant or array (0 = consonant, 1 = vowel)
        $start = rand(0, 1);

        // add a consonant and a vowel until the length of the string has been met
        for($i=1; $i<=ceil($length/2); $i++) {

            // if we are to start with a consonant (0==start)
            if($start == 0) {
                $output .= $alphabets['consonant'][rand(0, 19)];
                $output .= $alphabets['vowels'][rand(0, 4)];
            } else {
                $output .= $alphabets['vowels'][rand(0, 4)];
                $output .= $alphabets['consonant'][rand(0, 19)];
            }

        }

        // return output
        return $output;
    }

    /**
     * Function generateUniqueRandomWord
     *
     *
     * @param int $length
     * @param bool $addNumbers
     * @param bool $addSpecialChars
     * @param bool $addExtraSpecialChars
     * @param null $pattern
     * @return mixed
     *
     * Handcrafted with LOVE & PRIDE in NAIROBI, KENYA
     * @author Imani Manyara <www.imanimanyara.com>
     */
    public static function getRandomizedWord($length = 12, $addNumbers = true, $addSpecialChars = true, $addExtraSpecialChars = false, $pattern = null)
    {

        $seed = empty($pattern) ? 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' : $pattern;
        if ( $addNumbers )
            $seed .= '0123456789';
        if ( $addSpecialChars )
            $seed .= '!@#$%^&*()';
        if ( $addExtraSpecialChars )
            $seed .= '-_ []{}<>~`+=,.;:/?|';

        $seed = str_split($seed);
        $word = '';

        for ($i = 0; $i < $length; $i++) {
            $word .= $seed[array_rand($seed)];
        }

        return self::trimSpacesAndWhiteSpaces($word);
    }

    public static function trimRepeatingCharOnTheRight($word, $char = ','){
        return preg_replace("/$char+/", $char, rtrim($word, $char));
    }

    public static function stripRepeatingChars($word){
        return preg_replace('{(.)\1+}','$1',$word);
    }




    // generates a random number
    public static function randomizeNumber($minimum, $maximum)
    {
        return mt_rand($minimum, $maximum);
    }

    public static function numberBreakdown($number, $getUnsigned = false)
    {
        $negative = 1;
        if ($number < 0)
        {
            $negative = -1;
            $number  *= -1;
        }

        // if get unsigned
        if ($getUnsigned){
            $data = array(
                'whole' => floor($number),
                'float' => ($number - floor($number))
            );
        }
        else{
            $data = array(
                'whole' => floor($number) * $negative,
                'float' => ($number - floor($number)) * $negative,
            );
        }

        return TypeConverter::toObject($data);
    }

    public static function addCharIf($count, $word, $char, $space = false){
        return $count.(true === $space ? " " : '').$word.($count >= 2 ? $char : ($count == 0 ? $char : ''));
    }

    public static function naturalLanguageJoin(array $list, $conjunction = 'and') {

        // Join a string with a natural language conjunction at the end.
        //https://gist.github.com/angry-dan/e01b8712d6538510dd9c

        // option 1
        $last  = array_slice($list, -1);
        $first = join(', ', array_slice($list, 0, -1));
        $both  = array_filter(array_merge(array($first), $last), 'strlen');
        $last  = join(" $conjunction ", $both);
        return $last;

        // option 2
        $last = array_pop($list);
        if ($list) {
            return implode(', ', $list) . ' ' . $conjunction . ' ' . $last;
        }
        return $last;

    }


    /**
     * method masks the username of an email address
     *
     * @param string $email the email address to mask
     * @param string $char the character to use to mask with
     * @param int $level the percent of the username to mask
     * @return $result
     *
     * @author   Imani Manyara <info@imanimanyara.com>
     */
    public static function maskEmail($email, $level = 50, $char = '*' ){

        if(!empty($email)){
            list( $user, $domain ) = preg_split("/@/", $email );

            //username parts mask
            $len_user            = strlen( $user );
            $username_mask_count = floor( $len_user * $level /100 );
            $username_offset     = floor( ( $len_user - $username_mask_count ) / 2 );
            $masked_username     = substr( $user, 0, $username_offset )
                .str_repeat( $char, $username_mask_count )
                .substr( $user, $username_mask_count+$username_offset );

            //domain part mask
            $len_domain          = strlen( $user );
            $random              = rand(60,90);
            $domain_mask_count   = floor( $len_domain * $random /100 );
            $domain_offset       = floor( ( $len_domain - $domain_mask_count ) / 2 );
            $masked_domain       = substr( $domain, 0, $domain_offset )
                .str_repeat( $char, $domain_mask_count )
                .substr( $domain, $domain_mask_count+$domain_offset );

            //return results
            return( $masked_username.'@'.$masked_domain );

        }

        return false;
    }


    public static function obfuscateEmail($email)
    {
        $em   = explode("@",$email);
        $name = implode(array_slice($em, 0, count($em)-1), '@');
        $len  = floor(strlen($name)/2);

        return substr($name,0, $len) . str_repeat('*', $len) . "@" . end($em);
    }


    public static function convertToPercentage($number){
        return round((float) $number * 100 );
    }


    public static function percentageBetween2Numbers($number, $total, $precision = 2){

        //  variables
        $number = (float) $number;
        $total  = (float) $total;

        // if number is greater than total
        if ($number > $total){
            return false;
        }

        // calculate
        $out = $number / ($total / 100);
        if (false === $precision){
            $out = round($out,2);
        }
        elseif ($precision > 0){
            $out = round($out,$precision);
        }
        else{
            $out = round($out);
        }

        return $out;
    }


    public static function parseHTMLTags($tags, $enclose = true, $trim = false) {

        // if empty
        if (Validate::isEmpty($tags)){
            return '';
        }

        $out = '';
        if(!is_array($tags)){
            // decode entities
            $tags = html_entity_decode($tags);
            // remove opening tag
            $tags = str_replace('<', '', $tags);
            // remove closing tag
            $tags = str_replace('>', ',', $tags);

            // convert to array
            $tags = explode(',', $tags);
        }

        foreach ($tags as $key => $item){
            if(!is_array($item)){
                // decode entities
                $item = html_entity_decode($item);
                // remove opening tag
                $item = str_replace('<', '', $item);
                // remove closing tag
                $item = str_replace('>', ',', $item);
                // remove spaces
                $item = str_replace(' ', '', $item);
                // remove special characters
                $item = preg_replace('/[^A-Za-z0-9\-]/', '', $item);
                // get prepared tags
                $out .=  true === $enclose ? "<$item>" : (count($tags) > 1 ? "$item," : $item);
            }
        }
        $out = ((true === $trim) ? rtrim("$out,", ',') : $out);
        return !empty($out) ? $out : '';
    }



    /**
     * Function parseJsonArray
     *
     * @param $jsonArray
     * @param int $parentId
     * @return array
     *
     * http://stackoverflow.com/questions/11357981/save-json-or-multidimentional-array-to-DataBase-flat?answertab=active#tab-top
     *
     * Handcrafted with LOVE & PRIDE in NAIROBI, KENYA
     * @author Imani Manyara <www.imanimanyara.com>
     */
    public static function parseJsonArray($jsonArray, $parentId = 0){

        if (empty($jsonArray) || !is_array($jsonArray)){
            return null;
        }

        $return = array();
        foreach ($jsonArray as $order => $subArray) {

            $returnSubSubArray = array();
            if (isset($subArray['children'])) {
                $returnSubSubArray = self::parseJsonArray(
                    $subArray['children'],
                    $subArray['id']
                );
            }

            $return[] = array(
                'parent_id' => $parentId,
                'order'     => $order,
                'id'        => $subArray['id'],
            );

            $return = array_merge($return, $returnSubSubArray);
        }

        return $return;
    }

    /**
     * Function parseJsonArray
     *
     * @param $array
     * @param int $parentId
     * @return array
     *
     * http://stackoverflow.com/questions/11357981/save-json-or-multidimentional-array-to-DataBase-flat?answertab=active#tab-top
     *
     * Handcrafted with LOVE & PRIDE in NAIROBI, KENYA
     * @author Imani Manyara <www.imanimanyara.com>
     */
    public static function parseArrayToJson($array, $parentId = 0){

        if (empty($array) || !is_array($array)){
            return null;
        }

        $return = array();
        foreach ($array as $order => $subArray) {

            $returnSubSubArray = array();
            if (isset($subArray['children'])) {
                $returnSubSubArray = self::parseJsonArray(
                    $subArray['children'],
                    $subArray['id']
                );
            }

            $return[] = array(
                'parent_id' => $parentId,
                'order'     => $order,
                'id'        => $subArray['id'],
            );

            $return = array_merge($return, $returnSubSubArray);
        }

        return $return;
    }

    /**
     * Get checkbox value status
     *
     * @param $input
     * @return bool
     *
     * Handcrafted with LOVE & PRIDE in NAIROBI, KENYA
     * @author Imani Manyara <www.imanimanyara.com>
     */
    public static function checkboxStatus($input){
        return !empty($input) || 1 === $input ? true : false;
    }


    public static function buildName($obj){

        $firstName = ucfirst($obj->first_name);
        $lastName  = ucfirst($obj->last_name);
        $username  = ucfirst($obj->username);
        $email     = $obj->email;
        $name      = null;

        if (!empty($firstName) && !empty($lastName)) {
            $name = sprintf("%s %s", ucwords($obj->first_name), ucwords($obj->last_name));
        }else{
            if (!empty($firstName)) {
                $name = $firstName;
            }elseif (!empty($lastName)) {
                $name = $lastName;
            }elseif (!empty($username)) {
                $name = $username;
            }else{
                $name = $email;
            }
        }
        return $name;

    }

}
