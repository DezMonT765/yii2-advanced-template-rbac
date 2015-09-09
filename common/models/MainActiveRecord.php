<?php
namespace common\models;
use yii\db\ActiveRecord;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 13.04.2015
 * Time: 22:16
 */

class MainActiveRecord extends ActiveRecord
{
    public function searchByAttribute($attribute,$value)
    {
        $query = self::find();
        $query->filterWhere(['like',$attribute, $value]);
        return $query->all();
    }

    public function searchByIds(array $ids)
    {
        $query = self::find();
        $query->filterWhere(['id'=>$ids]);
        return $query->all();
    }
}