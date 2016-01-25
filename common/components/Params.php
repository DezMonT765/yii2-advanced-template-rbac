<?php
namespace common\components;
/**
 * Created by PhpStorm.
 * User: Dezmont
 * Date: 15.01.2016
 * Time: 18:58
 * @method static is_detailed_alert()
 * @method static admin_email()
 */
class Params {
    public static function __callStatic($name,$attributes)
    {
        return $name;
    }
}