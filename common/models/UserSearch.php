<?php
namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form about `common\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['auth_key', 'password_hash', 'password_reset_token', 'email', 'role'], 'safe'],
        ];
    }


    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }


    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search() {
        $query = User::find();
        $dataProvider = new ActiveDataProvider([
                                                   'query' => $query,
                                                   'pagination' => [
                                                       'pageSize' => 20
                                                   ]
                                               ]);
        $query->andFilterWhere([
                                   'status' => $this->status,
                                   'role' => $this->role,
                                   'created_at' => $this->created_at,
                                   'updated_at' => $this->updated_at,
                               ]);
        $query->andFilterWhere(['like', 'email', $this->email])
              ->andFilterWhere(['like', 'role', $this->role]);
        return $dataProvider;
    }
}
