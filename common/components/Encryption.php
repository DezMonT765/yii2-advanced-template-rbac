<?php
namespace common\components;
/**
 * Created by JetBrains PhpStorm.
 * User: DezMonT
 * Date: 28.11.13
 * Time: 15:12
 * To change this template use File | Settings | File Templates.
 *
 * Contains various encryption/decryption methods
 */
class Encryption {
    const skey  = "cumbaja2013"; // you can change it

    public static function safe_b64encode($string) {

        $data = base64_encode($string);
        $data = str_replace(array('+','/','='),array('-','_',''),$data);
        return $data;
    }

    public static function safe_b64decode($string) {
        $data = str_replace(array('-','_'),array('+','/'),$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    public static  function encode($value){

        if(!$value){return false;}
        $text = $value;
        $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $crypttext = mcrypt_encrypt(MCRYPT_BLOWFISH, self::skey, $text, MCRYPT_MODE_ECB, $iv);
        return trim(self::safe_b64encode($crypttext));
    }

    public static function decode($value){

        if(!$value){return false;}
        $crypttext = self::safe_b64decode($value);
        $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypttext = mcrypt_decrypt(MCRYPT_BLOWFISH, self::skey, $crypttext, MCRYPT_MODE_ECB, $iv);
        return trim($decrypttext);
    }

    private static function encryptInit() {
        $key = Yii::app()->params['dataEncryptionKey'];
        $td = mcrypt_module_open('des', '', 'ecb', '');
        $key = substr($key, 0, mcrypt_enc_get_key_size($td));
        $iv_size = mcrypt_enc_get_iv_size($td);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        return ['td'=>$td,'key'=>$key,'iv'=>$iv];
    }

    public static function secureEncode($data) {
        $encrypt = self::encryptInit();
        $encoded = null;
        if (mcrypt_generic_init($encrypt['td'], $encrypt['key'], $encrypt['iv']) != -1) {
            /* Encrypt data */
            $encoded = mcrypt_generic($encrypt['td'], trim($data));

            mcrypt_generic_deinit($encrypt['td']);
            mcrypt_module_close($encrypt['td']);

        }
        return $encoded;
    }

    public static function secureDecode($data) {

        $encrypt = self::encryptInit();
        $decoded = null;
        /* Initialize encryption handle */
        if (mcrypt_generic_init($encrypt['td'], $encrypt['key'], $encrypt['iv']) != -1) {

            $decoded = trim(mdecrypt_generic($encrypt['td'], $data));
            mcrypt_generic_deinit($encrypt['td']);
            mcrypt_module_close($encrypt['td']);
        }
        return $decoded;
    }

    public static function secureDecodeTest($data) {
        echo $data;
        $encoded = self::secureEncode($data);
        echo $encoded;
        $decoded = self::secureDecode($encoded);
        echo $decoded;
        if(strncmp($decoded,$data,strlen($data)) == 0)
            echo ' OK!';
        else echo ' FAILED!';
        return $encoded;
    }


    /**
     * Returns the number of bytes in the given string.
     * This method ensures the string is treated as a byte array by using `mb_strlen()`.
     * @param string $string the string being measured for length
     * @return integer the number of bytes in the given string.
     */
    public static function byteLength($string)
    {
        return mb_strlen($string, '8bit');
    }

    /**
     * Returns the portion of string specified by the start and length parameters.
     * This method ensures the string is treated as a byte array by using `mb_substr()`.
     * @param string $string the input string. Must be one character or longer.
     * @param integer $start the starting position
     * @param integer $length the desired portion length. If not specified or `null`, there will be
     * no limit on length i.e. the output will be until the end of the string.
     * @return string the extracted part of string, or FALSE on failure or an empty string.
     * @see http://www.php.net/manual/en/function.substr.php
     */
    public static function byteSubstr($string, $start, $length = null)
    {
        return mb_substr($string, $start, $length === null ? mb_strlen($string, '8bit') : $length, '8bit');
    }


    public static  function generateRandomKey($length = 32)
    {
        /*
         * Strategy
         *
         * The most common platform is Linux, on which /dev/urandom is the best choice. Many other OSs
         * implement a device called /dev/urandom for Linux compat and it is good too. So if there is
         * a /dev/urandom then it is our first choice regardless of OS.
         *
         * Nearly all other modern Unix-like systems (the BSDs, Unixes and OS X) have a /dev/random
         * that is a good choice. If we didn't get bytes from /dev/urandom then we try this next but
         * only if the system is not Linux. Do not try to read /dev/random on Linux.
         *
         * Finally, OpenSSL can supply CSPR bytes. It is our last resort. On Windows this reads from
         * CryptGenRandom, which is the right thing to do. On other systems that don't have a Unix-like
         * /dev/urandom, it will deliver bytes from its own CSPRNG that is seeded from kernel sources
         * of randomness. Even though it is fast, we don't generally prefer OpenSSL over /dev/urandom
         * because an RNG in user space memory is undesirable.
         *
         * For background, see http://sockpuppet.org/blog/2014/02/25/safely-generate-random-numbers/
         */

        $bytes = '';

        // If we are on Linux or any OS that mimics the Linux /dev/urandom device, e.g. FreeBSD or OS X,
        // then read from /dev/urandom.
        if (@file_exists('/dev/urandom')) {
            $handle = fopen('/dev/urandom', 'r');
            if ($handle !== false) {
                $bytes .= fread($handle, $length);
                fclose($handle);
            }
        }

        if (self::byteLength($bytes) >= $length) {
            return self::byteSubstr($bytes, 0, $length);
        }

        // If we are not on Linux and there is a /dev/random device then we have a BSD or Unix device
        // that won't block. It's not safe to read from /dev/random on Linux.
        if (PHP_OS !== 'Linux' && @file_exists('/dev/random')) {
            $handle = fopen('/dev/random', 'r');
            if ($handle !== false) {
                $bytes .= fread($handle, $length);
                fclose($handle);
            }
        }

        if (self::byteLength($bytes) >= $length) {
            return self::byteSubstr($bytes, 0, $length);
        }

        if (!extension_loaded('openssl')) {
            throw new Exception('The OpenSSL PHP extension is not installed.');
        }

        $bytes .= openssl_random_pseudo_bytes($length, $cryptoStrong);

        if (self::byteLength($bytes) < $length || !$cryptoStrong) {
            throw new Exception('Unable to generate random bytes.');
        }

        return self::byteSubstr($bytes, 0, $length);
    }

    /**
     * Generates a random string of specified length.
     * The string generated matches [A-Za-z0-9_-]+ and is transparent to URL-encoding.
     *
     * @param integer $length the length of the key in characters
     * @return string the generated random key
     * @throws Exception on failure.
     */
    public static  function generateRandomString($length = 32)
    {
        $bytes = self::generateRandomKey($length);
        // '=' character(s) returned by base64_encode() are always discarded because
        // they are guaranteed to be after position $length in the base64_encode() output.
        return strtr(substr(base64_encode($bytes), 0, $length), '+/', '_-');
    }
}