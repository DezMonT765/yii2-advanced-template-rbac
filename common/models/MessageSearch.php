<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\i18n\DbMessageSource;

/**
 * This is the model class for table "message".
 *
 * @property integer $id
 * @property string $language
 * @property string $translation
 * @property string $originMessage
 * @property string $originCategory
 *
 */
class MessageSearch extends Message

{
    const PAGE_COUNT = 10;

    public function getOriginMessage()
    {
        $origin =  $this->sourceMessage->message;
        return $origin;
    }

    public function setOriginMessage($message)
    {
        $this->originMessage = $message;
    }

    public function getOriginCategory()
    {
        return $this->sourceMessage->category;
    }

    public function setOriginCategory($category)
    {
        $this->originCategory = $category;
    }



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
          [['language','translation','originMessage','originCategory'],'safe']
        ];
    }



   public function search()
   {
       $query = self::find();
       $dataProvider = new ActiveDataProvider([
                                                  'query' => $query,
                                                  'pagination' => [
                                                      'pageSize' => self::PAGE_COUNT,
                                                  ],
                                                  'sort' => [

                                                      'attributes'=>[
                                                        'originMessage' => [
                                                            'asc'=>['sourceMessage.message'=>SORT_ASC],
                                                            'desc'=>['sourceMessage.message'=>SORT_DESC],
                                                            ],
                                                        'originCategory' => [
                                                            'asc'=>['sourceMessage.category'=>SORT_ASC],
                                                            'desc'=>['sourceMessage.category'=>SORT_DESC],
                                                            ],
                                                        'translation' => [
                                                            'asc'=>['translation'=>SORT_ASC],
                                                            'desc'=>['translation'=>SORT_DESC],
                                                        ]
                                                      ]
                                                  ]
                                              ]);


       if (!$this->validate()) {
           // uncomment the following line if you do not want to any records when validation fails
           // $query->where('0=1');
           return $dataProvider;
       }


       $query->joinWith(['sourceMessage'=>function(ActiveQuery $query) {
           $query->andFilterWhere(['like','sourceMessage.message', $this->originMessage])
               ->andFilterWhere(['sourceMessage.category'=>$this->originCategory]);
       }]);

       $query->andFilterWhere(['like', 'language', $this->language])
           ->andFilterWhere(['like', 'translation', $this->translation]);

       return $dataProvider;
   }


}
