<?php
namespace common\models;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Transaction;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 13.04.2015
 * Time: 22:16
 *
 *
 * @class MainActiveRecord
 * @property Transaction $transaction
 */

class MainActiveRecord extends ActiveRecord
{
    public $transaction = null;
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

    protected  function initLocalTransaction()
    {
        if(!Yii::$app->db->getTransaction())
        {
            $this->transaction = Yii::$app->db->beginTransaction();
        }
    }


    /**
     * Commits current local transaction
     */
    protected  function commitLocalTransaction()
    {
        if(self::isLocalTransactionAccessible())
        {
            $this->transaction->commit();
        }
    }


    /**
     * rollback current local transaction
     */
    protected function rollbackLocalTransaction()
    {
        if(self::isLocalTransactionAccessible())
        {
            $this->transaction->rollback();
        }
    }
    protected function isLocalTransactionAccessible()
    {
        $is_accessible = !is_null($this->transaction) && $this->transaction->isActive;
        return  $is_accessible;
    }
}