<?php
namespace common\models;
use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
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
    public $is_saved = null;
    public $transaction = null;
    public function searchByAttribute($attribute,$value,array $additional_criteria = [])
    {
        $query = self::find();
        $query->filterWhere(['like',$attribute, $value]);
        if(count($additional_criteria)) {
            foreach($additional_criteria as $criteria) {
                $query->andFilterWhere($criteria);
            }
        }
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

    /** this looks unnecessary but it disables useless and annoying typecasting from ActiveRecord class
     * @param BaseActiveRecord $record
     * @param array $row
     */
    public static function populateRecord($record,$row) {
        BaseActiveRecord::populateRecord($record, $row);
    }


    /** sometimes save result needs to be marked as failed on a later stages, e.g. on afterSave
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool|null
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        $is_saved = parent::save($runValidation,$attributeNames);
        if($this->is_saved === null)
            $this->is_saved = $is_saved;
        return $this->is_saved;
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