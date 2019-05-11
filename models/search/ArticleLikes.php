<?php
/**
 * ArticleLikes
 *
 * ArticleLikes represents the model behind the search form about `ommu\article\models\ArticleLikes`.
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 11:11 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

namespace ommu\article\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\article\models\ArticleLikes as ArticleLikesModel;
//use ommu\article\models\Articles;

class ArticleLikes extends ArticleLikesModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['like_id', 'publish', 'article_id', 'user_id'], 'integer'],
			[['likes_date', 'likes_ip', 'updated_date', 'articleTitle', 'userDisplayname'], 'safe'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	/**
	 * Tambahkan fungsi beforeValidate ini pada model search untuk menumpuk validasi pd model induk. 
	 * dan "jangan" tambahkan parent::beforeValidate, cukup "return true" saja.
	 * maka validasi yg akan dipakai hanya pd model ini, semua script yg ditaruh di beforeValidate pada model induk
	 * tidak akan dijalankan.
	 */
	public function beforeValidate() {
		return true;
	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params, $column=null)
	{
		if(!($column && is_array($column)))
			$query = ArticleLikesModel::find()->alias('t');
		else
			$query = ArticleLikesModel::find()->alias('t')->select($column);
		$query->joinWith(['article article', 'user user']);

		// add conditions that should always apply here
		$dataParams = [
			'query' => $query,
		];
		// disable pagination agar data pada api tampil semua
		if(isset($params['pagination']) && $params['pagination'] == 0)
			$dataParams['pagination'] = false;
		$dataProvider = new ActiveDataProvider($dataParams);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['articleTitle'] = [
			'asc' => ['article.title' => SORT_ASC],
			'desc' => ['article.title' => SORT_DESC],
		];
		$attributes['userDisplayname'] = [
			'asc' => ['user.displayname' => SORT_ASC],
			'desc' => ['user.displayname' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['like_id' => SORT_DESC],
		]);

		$this->load($params);

		if(!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.like_id' => isset($params['id']) ? $params['id'] : $this->like_id,
			't.article_id' => isset($params['article']) ? $params['article'] : $this->article_id,
			't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
			'cast(t.likes_date as date)' => $this->likes_date,
			'cast(t.updated_date as date)' => $this->updated_date,
		]);

		if(isset($params['trash']))
			$query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
		else {
			if(!isset($params['publish']) || (isset($params['publish']) && $params['publish'] == ''))
				$query->andFilterWhere(['IN', 't.publish', [0,1]]);
			else
				$query->andFilterWhere(['t.publish' => $this->publish]);
		}

		$query->andFilterWhere(['like', 't.likes_ip', $this->likes_ip])
			->andFilterWhere(['like', 'article.title', $this->articleTitle])
			->andFilterWhere(['like', 'user.displayname', $this->userDisplayname]);

		return $dataProvider;
	}
}
