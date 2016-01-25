<?php
namespace common\components;
use Yii;

/**
 * Created by PhpStorm.
 * User: Dezmont
 * Date: 15.01.2016
 * Time: 18:58

 */
class ParamsGetter extends Params{
    public static function __callStatic($name,$attributes)
    {
        $param =  Yii::$app->params[parent::__callStatic($name,$attributes)];
        return $param;
    }
}