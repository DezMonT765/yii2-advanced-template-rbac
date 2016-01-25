<?php
namespace common\components;
use yii\base\Exception;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 18.03.2015
 * Time: 10:48
 */

class FieldWrap
{
    protected $wrap;
    protected $params;

    public function __construct(callable $wrap)
    {
        self::setWrap($wrap);

    }

    public function setWrap(callable $wrap)
    {
        $this->wrap = $wrap;
    }

    public function setParams(array $params)
    {
        $this->params = $params;
    }

    public function getWrap()
    {
        return $this->wrap;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function call()
    {
        if(is_array($this->params))
            return call_user_func_array($this->wrap,$this->params);
        else
            throw new Exception('Params not an array');
    }

    public function callWithParams($params)
    {
        $this->setParams($params);
        return $this->call();
    }


}