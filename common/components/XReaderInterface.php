<?php
namespace common\components;
use SplFileInfo;
use yii\base\Component;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 18.03.2015
 * Time: 12:00
 */

abstract class XReaderInterface extends Component
{
    protected  $data = [];
    protected $start_position = 0;
    protected $file_object;
    public function __construct($file_name)
    {
       $this->file_object = new SplFileInfo($file_name);
       $this->init();
    }

    public function getData()
    {
        return $this->data;
    }
    public function getStartPosition()
    {
        return $this->start_position;
    }

}