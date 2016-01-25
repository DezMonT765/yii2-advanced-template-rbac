<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 *
 *
 */
class SourceMessageSearch extends SourceMessage

{
    const PAGE_COUNT = 10;




    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
          [['message','category','language','messageTranslation'],'safe']
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
                                                        'message' => [
                                                            'asc'=>['message'=>SORT_ASC],
                                                            'desc'=>['message'=>SORT_DESC],
                                                            ],
                                                        'category' => [
                                                            'asc'=>['category'=>SORT_ASC],
                                                            'desc'=>['category'=>SORT_DESC],
                                                            ],
                                                        'messageTranslation' => [
                                                            'asc'=>['messages.translation'=>SORT_ASC],
                                                            'desc'=>['messages.translation'=>SORT_DESC],
                                                        ]
                                                      ]
                                                  ]
                                              ]);


       if (!$this->validate()) {
           // uncomment the following line if you do not want to any records when validation fails
           // $query->where('0=1');
           return $dataProvider;
       }


       $query->joinWith(['messages'=>function(ActiveQuery $query) {
           $query->andFilterWhere(['like','messages.translation', $this->messageTranslation])
               ->andFilterWhere(['messages.language'=>$this->language]);
       }]);

       $query->andFilterWhere(['like', 'category', $this->category])
           ->andFilterWhere(['like', 'message', $this->message]);

       return $dataProvider;
   }


}
