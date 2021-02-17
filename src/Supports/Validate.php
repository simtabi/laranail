<?php

namespace Simtabi\Laranail\Supports;

use Respect\Validation\Validator as Respect;
use libphonenumber\geocoding\PhoneNumberOfflineGeocoder;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberToCarrierMapper;
use libphonenumber\PhoneNumberToTimeZonesMapper;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\ShortNumberInfo;
use Simtabi\Laranail\Exception\CatchThisException;
use Simtabi\Laranail\Supports\Helpers;
use Simtabi\Laranail\Supports\TypeConverter;


class Validate
{

    /**
     * Default region for telephone utilities
     */
    const DEFAULT_REGION = 'KE';

    private static function _e($val){
        return $val;
    }

    public static function isValid($value, $defaultRegion = self::DEFAULT_REGION){

        // output variables
        $status = false;
        $errors = null;

        try {

            // strip and remove white spaces from number
            $value    = str_replace(" ", "", trim($value));

            // initialize class and assign variable
            $phoneNumberUtility = PhoneNumberUtil::getInstance();
            $phoneNumberObject  = $phoneNumberUtility->parse($value, $defaultRegion);

            // validate
            $validNumber    = $phoneNumberUtility->isValidNumber($phoneNumberObject);
            $possibleNumber = $phoneNumberUtility->isPossibleNumber($phoneNumberObject);

            // if valid and possible number
            if($validNumber && $possibleNumber){
                $status = true;
            }

            // if not a possible number
            else if (!$possibleNumber){
                throw new CatchThisException(self::_e('NOT_A_POSSIBLE_PHONE_NUMBER'));
            }

            // if not a valid number
            else if (!$validNumber){
                throw new CatchThisException(self::_e('INVALID_PHONE_NUMBER'));
            }

        } catch (CatchThisException $e) {
            $errors = $e->getMessage();
        } catch (NumberParseException $e) {
            $errors = $e->getMessage();
        }

        return TypeConverter::toObject(array(
            'status' => $status,
            'errors' => $errors,
        ));
    }

    public static function getPhoneNumberByCountryInfo($number, $countryISOCode = self::DEFAULT_REGION){

        // output variables
        $status = false;
        $errors = null;

        //initialization variables
        $geoLocation                = null;
        $countryCode                = null;
        $phoneNumberRegion          = null;
        $numberType                 = null;
        $carrierName                = null;
        $timezone                   = null;
        $isValidNumber              = false;
        $nationalNumber             = null;
        $isPossibleNumber           = false;
        $isPossibleNumberWithReason = false;
        $isValidNumberForRegion     = false;

        $formattedE164              = null;
        $formattedOriginal          = null;
        $formattedNational          = null;
        $formattedInternational     = null;
        $formattedRFC3966           = null;
        $formattedFromUS            = null;
        $formattedFromCH            = null;
        $formattedFromGB            = null;

        $connectsToEmergencyNumber  = false;
        $isEmergencyNumber          = false;
        $getSupportedRegions        = null;

        try{

            // sanitize values
            $countryISOCode = trim($countryISOCode);
            $number         = trim($number);

            // validate country iso code
            if(!empty($countryISOCode)){
                if(!Validate::isCountry($countryISOCode)){
                    throw new CatchThisException('Invalid country ISO code');
                }
            }


            // validate number
            if((!Validate::isInteger($number)) && (!Validate::isString($number))){
                throw new CatchThisException(self::_e('INVALID_PHONE_NUMBER_FORMAT'));
            }

            //initialize classes
            $phoneNumberUtil            = PhoneNumberUtil::getInstance();
            $shortNumberUtil            = ShortNumberInfo::getInstance();
            $phoneNumberGeocoder        = PhoneNumberOfflineGeocoder::getInstance();

            $phoneNumber                = $phoneNumberUtil->parse($number, $countryISOCode, null, true);
            $isPossibleNumber           = $phoneNumberUtil->isPossibleNumber($phoneNumber);
            $isPossibleNumberWithReason = $phoneNumberUtil->isPossibleNumberWithReason($phoneNumber);
            $isValidNumber              = $phoneNumberUtil->isValidNumber($phoneNumber);
            $isValidNumberForRegion     = $phoneNumberUtil->isValidNumberForRegion($phoneNumber, $countryISOCode);
            $phoneNumberRegion          = $phoneNumberUtil->getRegionCodeForNumber($phoneNumber);
            $countryCode                = $phoneNumber->getCountryCode();
            $nationalNumber             = $phoneNumber->getNationalNumber();

            $formattedE164              = $phoneNumberUtil->format($phoneNumber, PhoneNumberFormat::E164);
            $formattedOriginal          = $phoneNumberUtil->formatInOriginalFormat($phoneNumber, $countryISOCode);
            $formattedNational          = $phoneNumberUtil->format($phoneNumber, PhoneNumberFormat::NATIONAL);
            $formattedInternational     = $phoneNumberUtil->format($phoneNumber, PhoneNumberFormat::INTERNATIONAL);
            $formattedRFC3966           = $phoneNumberUtil->format($phoneNumber, PhoneNumberFormat::RFC3966);
            $formattedFromUS            = $phoneNumberUtil->formatOutOfCountryCallingNumber($phoneNumber, "US");
            $formattedFromCH            = $phoneNumberUtil->formatOutOfCountryCallingNumber($phoneNumber, "CH");
            $formattedFromGB            = $phoneNumberUtil->formatOutOfCountryCallingNumber($phoneNumber, "GB");

            $connectsToEmergencyNumber  = $shortNumberUtil->connectsToEmergencyNumber($number, $countryISOCode);
            $getSupportedRegions        = $shortNumberUtil->getSupportedRegions();
            $isEmergencyNumber          = $shortNumberUtil->isEmergencyNumber($number, $countryISOCode);

            //get the number type
            $getNumberType = $phoneNumberUtil->getNumberType($phoneNumber);
            switch ($getNumberType){
                case 0  : $numberType = self::_e('FIXED_LINE_NUMBER');                  break;
                case 1  : $numberType = self::_e('MOBILE_NUMBER');                      break;
                case 2  : $numberType = self::_e('FIXED_LINE_NUMBER_OR_MOBILE_NUMBER'); break;
                case 3  : $numberType = self::_e('TOLL_FREE_NUMBER');                   break;
                case 4  : $numberType = self::_e('PREMIUM_RATE_NUMBER');                break;
                case 5  : $numberType = self::_e('SHARED_COST_NUMBER');                 break;
                case 6  : $numberType = self::_e('VOIP_SERVICE_NUMBER');                break;
                case 7  : $numberType = self::_e('PERSONAL_NUMBER');                    break;
                case 8  : $numberType = self::_e('PAGER_NUMBER');                       break;
                case 9  : $numberType = self::_e('UAN_NUMBER');                         break;
                case 10 : $numberType = self::_e('UNKNOWN_NUMBER');                     break;
                case 27 : $numberType = self::_e('EMERGENCY_NUMBER');                   break;
                case 28 : $numberType = self::_e('VOICE_MAIL_NUMBER');                  break;
                case 29 : $numberType = self::_e('SHORT_CODE_NUMBER');                  break;
                case 30 : $numberType = self::_e('STANDARD_RATE_NUMBER');               break;
                default : $numberType = self::_e('UNKNOWN_NUMBER_TYPE');                break;
            }
            $numberType  = ucwords(strtolower($numberType));

            //get geo data information
            $geoLocation = $phoneNumberGeocoder->getDescriptionForNumber($phoneNumber, $phoneNumberRegion, $phoneNumberRegion);

            //get special number information and timezone info
            $carrierName = PhoneNumberToCarrierMapper::getInstance()->getNameForNumber($phoneNumber, $phoneNumberRegion);
            $timezone    = PhoneNumberToTimeZonesMapper::getInstance()->getTimeZonesForNumber($phoneNumber);
            $timezone    = !is_array($timezone) && (Validate::isString($timezone)) ? implode(',', $timezone) : $timezone;

            //validate if carrier name is available
            if(empty($carrierName)){
                $carrierName = self::_e('CARRIER_NAME_NOT_SET');
            }

            // set status
            $status = true;

        }catch (CatchThisException $e){
            $errors = $e->getMessage();
        } catch (NumberParseException $e) {
            $errors = $e->getMessage();
        }

        return TypeConverter::toObject(array(
            'status' => $status,
            'errors' => Helpers::filterArray($errors),
            'get'    => array(
                'geoLocation'                => empty($geoLocation) ? null : $geoLocation,
                'countryCode'                => $countryCode,
                'phoneNumberRegion'          => $phoneNumberRegion,
                'numberType'                 => $numberType,
                'carrierName'                => $carrierName,
                'timezone'                   => $timezone,
                'isValidNumber'              => $isValidNumber,
                'nationalNumber'             => $nationalNumber,
                'isPossibleNumber'           => $isPossibleNumber,
                'isPossibleNumberWithReason' => $isPossibleNumberWithReason,
                'isValidNumberForRegion'     => $isValidNumberForRegion,

                'formattedE164'              => $formattedE164,
                'formattedOriginal'          => $formattedOriginal,
                'formattedNational'          => $formattedNational,
                'formattedInternational'     => $formattedInternational,
                'formattedRFC3966'           => $formattedRFC3966,
                'formattedFromUS'            => $formattedFromUS,
                'formattedFromCH'            => $formattedFromCH,
                'formattedFromGB'            => $formattedFromGB,

                'connectsToEmergencyNumber'  => $connectsToEmergencyNumber,
                'isEmergencyNumber'          => $isEmergencyNumber,
                'supportedRegions'           => $getSupportedRegions,
            ),
        ));

    }


    /**
     * Initializes Respect Validation Class
     *
     * @return Respect
     *
     * Handcrafted with LOVE & PRIDE in NAIROBI, KENYA
     * @author Imani Manyara <www.imanimanyara.com>
     */
    public static function initRespect(){
        return new Respect();
    }

    public static function minLength($value = null, $minimum = 0)
    {
        if(Respect::stringType()->length($minimum, null)->validate($value)){
            return true;
        }
        return false;
    }

    public static function maxLength($value = null, $maximum = 5)
    {
        if(Respect::stringType()->length(null, $maximum)->validate($value)){
            return true;
        }
        return false;
    }

    public static function exactLength($value = null, $compareTo = 0)
    {
        if(Respect::equals($compareTo)->validate($value)){
            return true;
        }
        return false;
    }

    public static function greaterThan($value = null, $min = 0, $inclusive = true)
    {
        if (true === $inclusive){
            if(Respect::intVal()->max($min, true)->validate($value)){
                return true;
            }
        }else{
            if(Respect::intVal()->max($min)->validate($value)){
                return true;
            }
        }
        return false;
    }

    public static function lessThan($value = null, $max = 0, $inclusive = true)
    {
        if (true === $inclusive){
            if(Respect::intVal()->min($max, true)->validate($value)){
                return true;
            }
        }else{
            if(Respect::intVal()->min($max)->validate($value)){
                return true;
            }
        }

        return false;
    }

    public static function alpha($value = null)
    {
        if(Respect::alpha()->validate($value)){
            return true;
        }
        return false;
    }

    public static function alphanumeric($value = null)
    {
        if(Respect::alnum()->validate($value)){
            return true;
        }
        return false;
    }

    public static function startsWith($value = null, $match = null)
    {
        if(Respect::startsWith($value)->validate($match)){
            return true;
        }
        return false;
    }

    public static function endsWith($value = null, $match = null)
    {
        if(Respect::endsWith($value)->validate($match)){
            return true;
        }
        return false;
    }

    public static function contains($value = null, $match = null)
    {
        if(Respect::contains($value)->validate($match)){
            return true;
        }
        return false;
    }

    public static function regex($value = null, $regex = null)
    {
        if(Respect::regex($regex)->validate($value)){
            return true;
        }
        return false;
    }

    public static function inArray($value = null, $list = [])
    {
        if (in_array($value, $list)){
            return true;
        }
        return false;
    }















    /**
     * Validate required parameter
     *
     * @param null $value
     * @return bool
     *
     * Handcrafted with LOVE & PRIDE in NAIROBI, KENYA
     * @author Imani Manyara <www.imanimanyara.com>
     */
    public static function required($value = null)
    {
        if (self::isEmpty($value)){
            return false;
        }
        return true;
    }

    /**
     * Validate optional parameter
     *
     * @param null $value
     * @return bool
     *
     * Handcrafted with LOVE & PRIDE in NAIROBI, KENYA
     * @author Imani Manyara <www.imanimanyara.com>
     */
    public static function optional($value = null)
    {
        return true;
    }


    /**
     * Checks if given value is a valid date format
     *
     * @param null $value
     * @param string $format
     * @return bool
     *
     * Handcrafted with LOVE & PRIDE in NAIROBI, KENYA
     * @author Imani Manyara <www.imanimanyara.com>
     */

    public static function isDateFormat($value = null, $format = 'MM/DD/YYYY')
    {
        // Datetime validation from http://www.phpro.org/examples/Validate-Date-Using-PHP.html
        if (empty($value)) {
            return false;
        }

        switch($format) {
            case 'YYYY/MM/DD':
            case 'YYYY-MM-DD':
                list($y, $m, $d) = preg_split('/[-\.\/ ]/', $value);
                break;

            case 'YYYY/DD/MM':
            case 'YYYY-DD-MM':
                list($y, $d, $m) = preg_split('/[-\.\/ ]/', $value);
                break;

            case 'DD-MM-YYYY':
            case 'DD/MM/YYYY':
                list($d, $m, $y) = preg_split('/[-\.\/ ]/', $value);
                break;

            case 'MM-DD-YYYY':
            case 'MM/DD/YYYY':
                list($m, $d, $y) = preg_split('/[-\.\/ ]/', $value);
                break;

            case 'YYYYMMDD':
                $y = substr($value, 0, 4);
                $m = substr($value, 4, 2);
                $d = substr($value, 6, 2);
                break;

            case 'YYYYDDMM':
                $y = substr($value, 0, 4);
                $d = substr($value, 4, 2);
                $m = substr($value, 6, 2);
                break;

            default:
                return false;
                break;
        }
        if (checkdate($m, $d, $y)){
            return true;
        }

        return false;
    }

    public static function isEmpty($value) {

        // if is an array or an object
        if (self::isArrayOrObject($value)){
            if (self::isUsableArrayObject($value)){
                return true;
            }
        }
        else{
            if ( empty($value) && strlen($value) == 0 ) {
                return true;
            }
        }
        return false;
    }

    public static function isInteger($value) {
        if(Respect::intVal()->validate($value)){
            return true;
        }
        return false;
    }

    public static function isNumeric($value) {
        if(Respect::numeric()->validate($value)){
            return true;
        }
        return false;
    }

    public static function isBool($value){
        return self::isBoolean($value);
    }

    public static function isBoolean($value){
        if(Respect::boolVal()->validate($value)){
            return true;
        }elseif(Respect::boolType()->validate($value)){
            return true;
        }
        return false;
    }

    public static function isTrue($value){
        if(Respect::trueVal()->validate($value) == true){
            return true;
        }
        return false;
    }

    public static function isFloat($value){
        if (is_float($value)){
            return true;
        }
        return false;
    }

    public static function isFalse($value){
        if(Respect::trueVal()->validate($value) == false){
            return true;
        }
        return false;
    }

    public static function isUrl($value){
        if(Respect::url()->validate($value)){
            return true;
        }
        return false;
    }

    public static function isUsername($username, $minLength = 5, $maxLength = 32, $startWithAlphabets = false) {

        // trim username
        $username = trim($username);

        $minLength = true !== self::isInteger($minLength) ? 5 : $minLength;

        // validate username maximum length
        $maxLength = true !== self::isInteger($maxLength) ? 32 : $maxLength;

        // validate username length
        $isLengthy = Respect::stringType()->length($minLength, $maxLength, true)->validate($username);
        if(!$isLengthy){
            return false;
        }

        // if we are to strictly start with alphabets
        $regex    = true === $startWithAlphabets ? '[A-Za-z]' : '';
        if(preg_match('/^'.$regex.'[A-Za-z0-9\d_]{5,'.$maxLength.'}$/', $username)){
            return true;
        }
        return false;
    }

    public static function isEmail($value){
        if((Respect::email()->validate($value)) && (preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $value))){
            return true;
        }
        return false;
    }

    public static function isBannedEmail($value) {

        if(true !== self::isEmail($value)){
            return false;
        }

        $json = '["0-mail.com","0815.ru","0815.su","0clickemail.com","0wnd.net","0wnd.org","10minutemail.co.za","10minutemail.com","10minutemail.de","123-m.com","1chuan.com","1fsdfdsfsdf.tk","1pad.de","1zhuan.com","20mail.it","20minutemail.com","21cn.com","2fdgdfgdfgdf.tk","2prong.com","30minutemail.com","33mail.com","3d-painting.com","3trtretgfrfe.tk","4gfdsgfdgfd.tk","4warding.com","4warding.net","4warding.org","5ghgfhfghfgh.tk","60minutemail.com","675hosting.com","675hosting.net","675hosting.org","6hjgjhgkilkj.tk","6ip.us","6paq.com","6url.com","75hosting.com","75hosting.net","75hosting.org","7days-printing.com","7tags.com","99experts.com","9ox.net","a-bc.net","a45.in","abcmail.email","acentri.com","advantimo.com","afrobacon.com","ag.us.to","agedmail.com","ahk.jp","ajaxapp.net","alivance.com","ama-trade.de","amail.com","amilegit.com","amiri.net","amiriindustries.com","anappthat.com","ano-mail.net","anonbox.net","anonmails.de","anonymail.dk","anonymbox.com","antichef.com","antichef.net","antireg.ru","antispam.de","antispammail.de","appixie.com","armyspy.com","artman-conception.com","aver.com","azmeil.tk","baxomale.ht.cx","beddly.com","beefmilk.com","bigprofessor.so","bigstring.com","binkmail.com","bio-muesli.net","blogmyway.org","bobmail.info","bodhi.lawlita.com","bofthew.com","bootybay.de","boun.cr","bouncr.com","boxformail.in","breakthru.com","brefmail.com","brennendesreich.de","broadbandninja.com","bsnow.net","bspamfree.org","bu.mintemail.com","buffemail.com","bugmenot.com","bumpymail.com","bund.us","bundes-li.ga","burnthespam.info","burstmail.info","buymoreplays.com","buyusedlibrarybooks.org","byom.de","c2.hu","cachedot.net","card.zp.ua","casualdx.com","cbair.com","cek.pm","cellurl.com","centermail.com","centermail.net","chammy.info","cheatmail.de","childsavetrust.org","chogmail.com","choicemail1.com","chong-mail.com","chong-mail.net","chong-mail.org","clixser.com","cmail.com","cmail.net","cmail.org","coldemail.info","consumerriot.com","cool.fr.nf","correo.blogos.net","cosmorph.com","courriel.fr.nf","courrieltemporaire.com","crapmail.org","crazymailing.com","cubiclink.com","curryworld.de","cust.in","cuvox.de","d3p.dk","dacoolest.com","daintly.com","dandikmail.com","dayrep.com","dbunker.com","dcemail.com","deadaddress.com","deadspam.com","deagot.com","dealja.com","delikkt.de","despam.it","despammed.com","devnullmail.com","dfgh.net","digitalsanctuary.com","dingbone.com","discard.email","discardmail.com","discardmail.de","disposableaddress.com","disposableemailaddresses.com","disposableemailaddresses.emailmiser.com","disposableinbox.com","dispose.it","disposeamail.com","disposemail.com","dispostable.com","dm.w3internet.co.uk","dm.w3internet.co.ukexample.com","dodgeit.com","dodgit.com","dodgit.org","doiea.com","domozmail.com","donemail.ru","dontreg.com","dontsendmespam.de","dotmsg.com","drdrb.com","drdrb.net","droplar.com","duam.net","dudmail.com","dump-email.info","dumpandjunk.com","dumpmail.de","dumpyemail.com","duskmail.com","e-mail.com","e-mail.org","e4ward.com","easytrashmail.com","einmalmail.de","einrot.com","einrot.de","eintagsmail.de","email60.com","emaildienst.de","emailgo.de","emailias.com","emailigo.de","emailinfive.com","emaillime.com","emailmiser.com","emailproxsy.com","emailsensei.com","emailtemporanea.com","emailtemporanea.net","emailtemporar.ro","emailtemporario.com.br","emailthe.net","emailtmp.com","emailto.de","emailwarden.com","emailx.at.hm","emailxfer.com","emeil.in","emeil.ir","emil.com","emz.net","enterto.com","ephemail.net","ero-tube.org","etranquil.com","etranquil.net","etranquil.org","evopo.com","explodemail.com","express.net.ua","eyepaste.com","fakeinbox.com","fakeinformation.com","fakemail.fr","fakemailz.com","fammix.com","fansworldwide.de","fantasymail.de","fastacura.com","fastchevy.com","fastchrysler.com","fastkawasaki.com","fastmazda.com","fastmitsubishi.com","fastnissan.com","fastsubaru.com","fastsuzuki.com","fasttoyota.com","fastyamaha.com","fatflap.com","fdfdsfds.com","fightallspam.com","fiifke.de","filzmail.com","fivemail.de","fixmail.tk","fizmail.com","fleckens.hu","flyspam.com","footard.com","forgetmail.com","fr33mail.info","frapmail.com","freundin.ru","friendlymail.co.uk","front14.org","fuckingduh.com","fudgerub.com","fux0ringduh.com","fyii.de","garliclife.com","gehensiemirnichtaufdensack.de","gelitik.in","get1mail.com","get2mail.fr","getairmail.com","getmails.eu","getonemail.com","getonemail.net","ghosttexter.de","giantmail.de","girlsundertheinfluence.com","gishpuppy.com","gmial.com","goemailgo.com","gorillaswithdirtyarmpits.com","gotmail.com","gotmail.net","gotmail.org","gotti.otherinbox.com","gowikibooks.com","gowikicampus.com","gowikicars.com","gowikifilms.com","gowikigames.com","gowikimusic.com","gowikinetwork.com","gowikitravel.com","gowikitv.com","grandmamail.com","grandmasmail.com","great-host.in","greensloth.com","grr.la","gsrv.co.uk","guerillamail.biz","guerillamail.com","guerillamail.net","guerillamail.org","guerrillamail.biz","guerrillamail.com","guerrillamail.de","guerrillamail.info","guerrillamail.net","guerrillamail.org","guerrillamailblock.com","gustr.com","h.mintemail.com","h8s.org","hacccc.com","haltospam.com","harakirimail.com","hartbot.de","hat-geld.de","hatespam.org","hellodream.mobi","herp.in","hidemail.de","hidzz.com","hmamail.com","hochsitze.com","hopemail.biz","hotpop.com","hulapla.de","ieatspam.eu","ieatspam.info","ieh-mail.de","ihateyoualot.info","iheartspam.org","ikbenspamvrij.nl","imails.info","imgof.com","imstations.com","inbax.tk","inbox.si","inboxalias.com","inboxclean.com","inboxclean.org","inboxproxy.com","incognitomail.com","incognitomail.net","incognitomail.org","infocom.zp.ua","inoutmail.de","inoutmail.eu","inoutmail.info","inoutmail.net","insorg-mail.info","instant-mail.de","ip6.li","ipoo.org","irish2me.com","iwi.net","jetable.com","jetable.fr.nf","jetable.net","jetable.org","jnxjn.com","jourrapide.com","jsrsolutions.com","junk1e.com","kasmail.com","kaspop.com","keepmymail.com","killmail.com","killmail.net","kimsdisk.com","kingsq.ga","kir.ch.tc","klassmaster.com","klassmaster.net","klzlk.com","kook.ml","koszmail.pl","kulturbetrieb.info","kurzepost.de","l33r.eu","lackmail.net","lags.us","lawlita.com","lazyinbox.com","letthemeatspam.com","lhsdv.com","lifebyfood.com","link2mail.net","litedrop.com","loadby.us","login-email.ml","lol.ovpn.to","lolfreak.net","lookugly.com","lopl.co.cc","lortemail.dk","lovemeleaveme.com","lr78.com","lroid.com","lukop.dk","m21.cc","m4ilweb.info","maboard.com","mail-filter.com","mail-temporaire.fr","mail.by","mail.mezimages.net","mail.zp.ua","mail114.net","mail1a.de","mail21.cc","mail2rss.org","mail333.com","mail4trash.com","mailbidon.com","mailbiz.biz","mailblocks.com","mailbucket.org","mailcat.biz","mailcatch.com","mailde.de","mailde.info","maildrop.cc","maildx.com","maileater.com","maileimer.de","mailexpire.com","mailfa.tk","mailforspam.com","mailfreeonline.com","mailfs.com","mailguard.me","mailimate.com","mailin8r.com","mailinater.com","mailinator.com","mailinator.net","mailinator.org","mailinator.us","mailinator2.com","mailincubator.com","mailismagic.com","mailmate.com","mailme.ir","mailme.lv","mailme24.com","mailmetrash.com","mailmoat.com","mailms.com","mailnator.com","mailnesia.com","mailnull.com","mailorg.org","mailpick.biz","mailproxsy.com","mailquack.com","mailrock.biz","mailscrap.com","mailshell.com","mailsiphon.com","mailslapping.com","mailslite.com","mailtemp.info","mailtome.de","mailtothis.com","mailtrash.net","mailtv.net","mailtv.tv","mailzilla.com","mailzilla.org","mailzilla.orgmbx.cc","makemetheking.com","manifestgenerator.com","manybrain.com","mbx.cc","mega.zik.dj","meinspamschutz.de","meltmail.com","messagebeamer.de","mezimages.net","mierdamail.com","migumail.com","ministry-of-silly-walks.de","mintemail.com","misterpinball.de","mjukglass.nu","moakt.com","mobi.web.id","mobileninja.co.uk","moburl.com","moncourrier.fr.nf","monemail.fr.nf","monmail.fr.nf","monumentmail.com","msa.minsmail.com","mt2009.com","mt2014.com","mx0.wwwnew.eu","my10minutemail.com","mycard.net.ua","mycleaninbox.net","myemailboxy.com","mymail-in.net","mymailoasis.com","mynetstore.de","mypacks.net","mypartyclip.de","myphantomemail.com","mysamp.de","myspaceinc.com","myspaceinc.net","myspaceinc.org","myspacepimpedup.com","myspamless.com","mytemp.email","mytempemail.com","mytempmail.com","mytrashmail.com","nabuma.com","neomailbox.com","nepwk.com","nervmich.net","nervtmich.net","netmails.com","netmails.net","netzidiot.de","neverbox.com","nice-4u.com","nincsmail.hu","nnh.com","no-spam.ws","noblepioneer.com","nobulk.com","noclickemail.com","nogmailspam.info","nomail.pw","nomail.xl.cx","nomail2me.com","nomorespamemails.com","nonspam.eu","nonspammer.de","noref.in","nospam.ze.tc","nospam4.us","nospamfor.us","nospammail.net","nospamthanks.info","notmailinator.com","notsharingmy.info","nowhere.org","nowmymail.com","nurfuerspam.de","nus.edu.sg","nwldx.com","objectmail.com","obobbo.com","odaymail.com","odnorazovoe.ru","one-time.email","oneoffemail.com","oneoffmail.com","onewaymail.com","onlatedotcom.info","online.ms","oopi.org","opayq.com","ordinaryamerican.net","otherinbox.com","ourklips.com","outlawspam.com","ovpn.to","owlpic.com","pancakemail.com","paplease.com","pcusers.otherinbox.com","pepbot.com","pfui.ru","pimpedupmyspace.com","pjjkp.com","plexolan.de","poczta.onet.pl","politikerclub.de","poofy.org","pookmail.com","privacy.net","privatdemail.net","privy-mail.com","privymail.de","proxymail.eu","prtnx.com","prtz.eu","punkass.com","putthisinyourspamdatabase.com","pwrby.com","quickinbox.com","quickmail.nl","rcpt.at","reallymymail.com","realtyalerts.ca","recode.me","recursor.net","recyclemail.dk","regbypass.com","regbypass.comsafe-mail.net","rejectmail.com","reliable-mail.com","rhyta.com","rklips.com","rmqkr.net","royal.net","rppkn.com","rtrtr.com","s0ny.net","safe-mail.net","safersignup.de","safetymail.info","safetypost.de","sandelf.de","saynotospams.com","schafmail.de","schrott-email.de","secretemail.de","secure-mail.biz","selfdestructingmail.com","selfdestructingmail.org","sendspamhere.com","senseless-entertainment.com","services391.com","sharedmailbox.org","sharklasers.com","shieldedmail.com","shieldemail.com","shiftmail.com","shitmail.me","shitmail.org","shitware.nl","shmeriously.com","shortmail.net","showslow.de","sibmail.com","sinnlos-mail.de","siteposter.net","skeefmail.com","slapsfromlastnight.com","slaskpost.se","slopsbox.com","slushmail.com","smashmail.de","smellfear.com","smellrear.com","snakemail.com","sneakemail.com","sneakmail.de","snkmail.com","sofimail.com","sofort-mail.de","softpls.asia","sogetthis.com","sohu.com","solvemail.info","soodonims.com","spam.la","spam.su","spam4.me","spamail.de","spamarrest.com","spamavert.com","spambob.com","spambob.net","spambob.org","spambog.com","spambog.de","spambog.net","spambog.ru","spambox.info","spambox.irishspringrealty.com","spambox.us","spamcannon.com","spamcannon.net","spamcero.com","spamcon.org","spamcorptastic.com","spamcowboy.com","spamcowboy.net","spamcowboy.org","spamday.com","spamex.com","spamfree.eu","spamfree24.com","spamfree24.de","spamfree24.eu","spamfree24.info","spamfree24.net","spamfree24.org","spamgoes.in","spamgourmet.com","spamgourmet.net","spamgourmet.org","spamherelots.com","spamhereplease.com","spamhole.com","spamify.com","spaminator.de","spamkill.info","spaml.com","spaml.de","spammotel.com","spamobox.com","spamoff.de","spamsalad.in","spamslicer.com","spamspot.com","spamstack.net","spamthis.co.uk","spamthisplease.com","spamtrail.com","spamtroll.net","speed.1s.fr","spikio.com","spoofmail.de","squizzy.de","ssoia.com","startkeys.com","stinkefinger.net","stop-my-spam.com","stuffmail.de","super-auswahl.de","supergreatmail.com","supermailer.jp","superrito.com","superstachel.de","suremail.info","svk.jp","sweetxxx.de","tagyourself.com","talkinator.com","tapchicuoihoi.com","teewars.org","teleworm.com","teleworm.us","temp-mail.org","temp-mail.ru","temp.emeraldwebmail.com","temp.headstrong.de","tempalias.com","tempe-mail.com","tempemail.biz","tempemail.co.za","tempemail.com","tempemail.net","tempinbox.co.uk","tempinbox.com","tempmail.eu","tempmail.it","tempmail2.com","tempmaildemo.com","tempmailer.com","tempmailer.de","tempomail.fr","temporarily.de","temporarioemail.com.br","temporaryemail.net","temporaryemail.us","temporaryforwarding.com","temporaryinbox.com","temporarymailaddress.com","tempsky.com","tempthe.net","tempymail.com","thanksnospam.info","thankyou2010.com","thc.st","thecloudindex.com","thelimestones.com","thisisnotmyrealemail.com","thismail.net","throwawayemailaddress.com","tilien.com","tittbit.in","tizi.com","tmail.ws","tmailinator.com","toiea.com","toomail.biz","topranklist.de","tradermail.info","trash-amil.com","trash-mail.at","trash-mail.com","trash-mail.de","trash2009.com","trash2010.com","trash2011.com","trashdevil.com","trashdevil.de","trashemail.de","trashmail.at","trashmail.com","trashmail.de","trashmail.me","trashmail.net","trashmail.org","trashmail.ws","trashmailer.com","trashymail.com","trashymail.net","trbvm.com","trialmail.de","trillianpro.com","tryalert.com","turual.com","twinmail.de","twoweirdtricks.com","tyldd.com","uggsrock.com","umail.net","upliftnow.com","uplipht.com","uroid.com","us.af","username.e4ward.com","venompen.com","veryrealemail.com","vidchart.com","viditag.com","viewcastmedia.com","viewcastmedia.net","viewcastmedia.org","viralplays.com","vomoto.com","vpn.st","vsimcard.com","vubby.com","walala.org","walkmail.net","wasteland.rfc822.org","webemail.me","webm4il.info","webuser.in","wee.my","weg-werf-email.de","wegwerf-email-addressen.de","wegwerf-emails.de","wegwerfadresse.de","wegwerfemail.com","wegwerfemail.de","wegwerfmail.de","wegwerfmail.info","wegwerfmail.net","wegwerfmail.org","wetrainbayarea.com","wetrainbayarea.org","wh4f.org","whatiaas.com","whatpaas.com","whatsaas.com","whopy.com","whtjddn.33mail.com","whyspam.me","wilemail.com","willhackforfood.biz","willselfdestruct.com","winemaven.info","wronghead.com","wuzup.net","wuzupmail.net","www.e4ward.com","www.gishpuppy.com","www.mailinator.com","wwwnew.eu","x.ip6.li","xagloo.com","xemaps.com","xents.com","xmaily.com","xoxy.net","xyzfree.net","yapped.net","yeah.net","yep.it","yogamaven.com","yopmail.com","yopmail.fr","yopmail.net","yourdomain.com","ypmail.webarnak.fr.eu.org","yuurok.com","z1p.biz","za.com","zehnminuten.de","zehnminutenmail.de","zetmail.com","zippymail.info","zoaxe.com","zoemail.com","zoemail.net","zoemail.org","zomg.info","zxcv.com","zxcvbnm.com","zzz.com"]';

        $bannedDomains = json_decode($json);
        for($i = 0; $i < count($bannedDomains); $i++){
            if($bannedDomains[$i] == strtolower(explode('@', $value)[1])){
                return false;
            }
        }
        return false;
    }

    public static function isString($value) {

        // ensure it's of a string type value and !empty
        if( Respect::StringType()->validate($value) && !(empty($value) && strlen($value) == 0  || is_null($value))){
            return true;
        }
        return false;
    }

    public static function isSlug($value){
        if(Respect::slug()->validate($value)){
            return true;
        }
        return false;
    }

    public static function isTimestamp($timestamp)
    {
        $check = (is_int($timestamp) OR is_float($timestamp))
            ? $timestamp
            : (string) (int) $timestamp;
        $status =  ($check === $timestamp)
        AND ( (int) $timestamp <=  PHP_INT_MAX)
        AND ( (int) $timestamp >= ~PHP_INT_MAX);

        if ($status){
            return true;
        }
        return false;
    }

    public static function isZeroDate($value){
        // http://stackoverflow.com/questions/8853956/check-if-date-is-equal-to-0000-00-00-000000
        if(empty($value) || (is_null($value))){
            $value = '0000-00-00';
        }
        switch (trim($value))
        {
            case '0000-00-00 00:00:00' : $status = true; break;
            case '0000-00-00'          : $status = true; break;
            default                    : $status = false; break;
        }

        if ($status){
            return true;
        }
        return false;
    }

    public static function isGreaterThan($value, $length = 5) {
        if(Respect::stringType()->length(null, $length)->validate($value)){
            return true;
        }
        return false;
    }

    public static function isLessThan($value, $length = 5) {
        if(Respect::stringType()->length($length, null)->validate($value)){
            return true;
        }
        return false;
    }

    public static function isIdentical($val1, $val2) {
        if(Respect::equals($val1)->validate($val2)){
            return true;
        }
        return false;
    }

    public static function isInRange($value, $minimum, $maximum) {
        if(Respect::stringType()->length($minimum, $maximum)->validate($value)){
            return true;
        }
        return false;
    }

    /***
     * Function is_title
     *
     * http://stackoverflow.com/questions/3623719/php-regex-to-check-a-english-name
     * http://stackoverflow.com/questions/1261338/php-regex-for-human-names
     * http://stackoverflow.com/questions/275160/regex-for-names
     * http://www.regextester.com/
     * http://www.regexr.com/
     *
     * @param $value
     * @param int $length
     * @return bool
     *
     * Handcrafted with LOVE & PRIDE in NAIROBI, KENYA
     * @author Imani Manyara <www.imanimanyara.com>
     */
    public static function isTitle($value, $length = 80) {

        // for later reference
        # $illegal = "#$%^&*()+=-[]';,./{}|:<>?~";
        $allowed = '~\-!@#$%\^&\*\(\)';

        $value = trim($value);
        if(Respect::stringType()->validate($value)){
            return (!preg_match("/^[\w\s$allowed]{1,$length}$/", trim($value))) ? false : true;
        }
        return false;
    }

    public static function isDateTime($value, $format = 'Y-m-d H:i:s'){
        if (self::isDate($value, $format)){
            return true;
        }
        return false;
    }

    public static function isDate($value, $format = 'Y-m-d') {
        if(!empty($format)){
            if(Respect::date($format)->validate($value)){
                return true;
            }
        }else{
            if(Respect::date()->validate($value)){
                return true;
            }
        }
        return false;
    }

    public static function isYear($value) {
        if(Respect::date('Y')->validate($value)){
            return true;
        }
        return false;
    }

    public static function isTimezone($value) {
        if(true === in_array($value, timezone_identifiers_list())){
            return true;
        }
        return false;
    }


    public static function isIP($value) {
        if (self::isLocalhost()){
            return true;
        }
        else{
            if(Respect::ip()->validate($value)){
                return true;
            }
        }
        return false;
    }


    public static function isLocalhost() {
        $whiteList = array( '127.0.0.1', '::1' );
        $value = $_SERVER['REMOTE_ADDR'];
        if( in_array( $value, $whiteList) ){
            return true;
        }
        return false;
    }

    public static function isCountry($value){
        // if we can validate it and Respect can't either
        $code  = Countries::getCountryName($value);
        $respect = Respect::countryCode()->validate($value);
        if((false === $code) && (false === $respect)){
            return false;
        }
        return true;
    }

    public static function isCallingCode($value){
        // if we can validate
        if((false === Countries::getCountryNameByCallingCode($value))){
            return false;
        }
        return true;
    }

    public static function isCurrency($value){

        // if we can validate it in both alpha2 & alpha3 and Respect can't either
        if((!Countries::getCountryCurrencyCodeByCode($value)) && (!Respect::currencyCode()->validate($value))){
            return false;
        }
        return true;
    }

    public static function isLanguage($value){
        if(Respect::languageCode()->validate($value)){
            return true;
        }else if(Respect::languageCode('alpha-3')->validate($value)){
            return true;
        }
        return false;
    }

    public static function isPostalCode($value, $locale = 'US'){
        if(Respect::numeric()->postalCode($locale)->validate($value)){
            return true;
        }
        return false;
    }


    public static function isLegalAge($value, $limit = 18) {

        if(empty($limit)){
            $limit = 18;
        }

        if(Respect::age($limit)->validate($value)){
            return true;
        }
        return false;
    }

    public static function hasNoRepeatingChars($value, $minimumCount = 3){
        if (!preg_match('/([a-z]{'.$minimumCount.',}|[0-9]{'.$minimumCount.',})/i', $value)) {
            return true;
        }
        return false;
    }

    public static function isArray($value) {
        if(Respect::arrayType()->validate($value)){
            return true;
        }
        return false;
    }

    public static function isObject($value) {
        if(Respect::arrayType()->validate($value)){
            return true;
        }
        return false;
    }

    public static function isArrayOrObject($value) {

        if(Respect::arrayVal()->validate($value)){
            return true;
        }
        elseif(Respect::arrayType()->validate($value)){
            return true;
        }
        return false;
    }

    public static function isEmptyDir($value) {
        if (!is_readable($value))
            return null;
        $handle = opendir($value);
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                return false;
            }
        }
        return true;
    }

    public static function fileExists($value) {
        if (file_exists($value) && is_readable($value)){
            return true;
        }
        return false;
    }

    public static function isJSON($value, $alt = false){

        if(!$alt){
            return is_string($value) && is_object(json_decode($value)) ? true : false;
        }

        // checks for calculating if the string given to it is JSON.
        // So, it is the most perfect one, but it's slower than the other.
        # Requires PHP 5.4 and above
        $status = is_string($value)
        && is_object(json_decode($value))
        && (json_last_error() == JSON_ERROR_NONE) ? true : false;
        if (!$status){
            return false;
        }
        return true;
    }


    public static function isAssociativeArray( $array = array() ) {
        if (array_keys( $array ) !== range( 0, count( $array ) - 1 )){
            return true;
        }
        return false;
    }

    public static function isHexColor($value){
        if (!preg_match("/^(#)?([0-9a-fA-F]{1,2}){6}$/i", trim($value))){
            return true;
        }
        return false;
    }

    public static function isSetId($value){
        if (self::isEmpty($value)){
            return false;
        }
        return true;
    }


    /**
     * Version control regex validation
     *
     * Will validate "version numbers" using regex and preg_match.
     * Examples (With or without 'v') v1 v1.1 v1.132.1
     * Allows "unlimited" length MAJOR version Followed by (but not required)
     * up to 2 addition "." - MINOR, PATCH Followed by (vut not required) "-" followed
     * by "pre|beta|b|RC|alpha|a|pl|p" followed by -#
     *
     * @param $value
     * @return int
     *
     * @author https://github.com/nicholas-c/version-regex
     * Notes: http://stackoverflow.com/questions/8100717/using-regular-expressions-in-php-to-return-part-of-a-string
     */
    public static function isVersionNumber($value) {
        if(preg_match('#^v?(\d{1,3}+(?:\.(?:\d{1,3})){0,2})(-(?:pre|beta|b|RC|alpha|a|pl|p)(?:\.?(?:\d+))?)?$#i', $value)){
            return true;
        }
        return false;
    }

    public static function isVersionReleaseNumber($value) {

        // 1.23.456 = version 1, release 23, modification 456
        #  1.23     = version 1, release 23, any modification
        #  1.23.*   = version 1, release 23, any modification
        #  1.*      = version 1, any release, any modification
        #  1        = version 1, any release, any modification
        #  *        = any version, any release, any modification

        if(preg_match('/^(\d+\.)?(\d+\.)?(\*|\d+)$/', $value)){
            return true;
        }
        return false;
    }

    /****
     * Check for complex password using regular expressions
     *
     * Checks if provided password meets the following conditions:
     * - of at least a minimum length of 3
     * - containing at least one lowercase letter
     * - and at least one uppercase letter
     * - and at least one number
     * - and at least a special character (non-word characters)
     * http://www.catswhocode.com/blog/15-php-regular-expressions-for-web-developers
     * http://shabeebk.com/blog/regular-expression-for-at-least-one-number-capital-letter-and-a-special-character/
     * http://stackoverflow.com/questions/5142103/regex-for-password-strength
     * http://runnable.com/UmrnTejI6Q4_AAIM/how-to-validate-complex-passwords-using-regular-expressions-for-php-and-pcre
     *
     * @param $value
     * @param int $minLength
     * @param int $maxLength
     * @return bool|mixed
     *
     * Handcrafted with LOVE & PRIDE in NAIROBI, KENYA
     * @author Imani Manyara <www.imanimanyara.com>
     */

    public static function isUsablePassword($value, $minLength = 6, $maxLength = 150) {
        if (strlen($value) < $minLength) {
            return false;
        }
        elseif (strlen($value) > $maxLength) {
            return false;
        }
        elseif (!preg_match('@[A-Z]@', $value) || !preg_match('@[a-z]@', $value) || !preg_match('@[0-9]@', $value)) {
            return false;
        }
        elseif (!preg_match_all("$\S*(?=\S{'.$minLength.',})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$", $value)){
            return false;
        }
        return true;
    }

    public static function isValidPasswordScore($value, $minimumScore = 3) {

        // check password score
        $init  = new Zxcvbn();
        $score = $init->passwordStrength($value)['score'];
        if( $score < $minimumScore ) {
            return false;
        }

        if(preg_match(
            '#^v?(\d{1,3}+(?:\.(?:\d{1,3})){0,2})(-(?:pre|beta|b|RC|alpha|a|pl|p)(?:\.?(?:\d+))?)?$#i', $value)
        ){
            return true;
        }
        return false;
    }

    public static function isUsableArrayObject($value, $filter = true){
        if (!self::isArrayOrObject($value)){
            return false;
        }

        // remove empty values
        $value = true === $filter ? Helpers::filterArray($value) : $value;

        // if array is not empty
        if (Respect::arrayVal()->notEmpty()->validate($value)){
            return true;
        }
        return false;
    }

    public static function isPhoneNumber($value, $defaultRegion = "KE"){
        if (self::isValid($value, $defaultRegion)->status)
            return true;
        return false;
    }
}
