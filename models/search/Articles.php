<?php
/**
 * Articles
 *
 * Articles represents the model behind the search form about `ommu\article\models\Articles`.
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 October 2017, 09:33 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

namespace ommu\article\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\article\models\Articles as ArticlesModel;
//use ommu\article\models\ArticleCategory;

class Articles extends ArticlesModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['article_id', 'publish', 'cat_id', 'headline', 'comment_code', 'creation_id', 'modified_id'], 'integer'],
			[['title', 'body', 'quote', 'published_date', 'creation_date', 'modified_date', 'updated_date', 'headline_date', 'slug', 'category_search', 'creationDisplayname', 'modifiedDisplayname'], 'safe'],
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
			$query = ArticlesModel::find()->alias('t');
		else
			$query = ArticlesModel::find()->alias('t')->select($column);
		$query->joinWith(['category category', 'creation creation', 'modified modified','view view','category.title category_title']);

		// add conditions that should always apply here
		$dataParams = [
			'query' => $query,
		];
		// disable pagination agar data pada api tampil semua
		if(isset($params['pagination']) && $params['pagination'] == 0)
			$dataParams['pagination'] = false;
		$dataProvider = new ActiveDataProvider($dataParams);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['category_search'] = [
			'asc' => ['category.name' => SORT_ASC],
			'desc' => ['category.name' => SORT_DESC],
		];
		$attributes['creationDisplayname'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
		];
		$attributes['cat_id'] = [
			'asc' => ['category_title.message' => SORT_ASC],
			'desc' => ['category_title.message' => SORT_DESC],
		];
		$attributes['media_search'] = [
			'asc' => ['view.medias' => SORT_ASC],
			'desc' => ['view.medias' => SORT_DESC],
		];
		$attributes['file_search'] = [
			'asc' => ['view.files' => SORT_ASC],
			'desc' => ['view.files' => SORT_DESC],
		];
		$attributes['tag_search'] = [
			'asc' => ['view.tags' => SORT_ASC],
			'desc' => ['view.tags' => SORT_DESC],
		];
		$attributes['view_search'] = [
			'asc' => ['view.views' => SORT_ASC],
			'desc' => ['view.views' => SORT_DESC],
		];
		$attributes['like_search'] = [
			'asc' => ['view.likes' => SORT_ASC],
			'desc' => ['view.likes' => SORT_DESC],
		];
		$attributes['modifiedDisplayname'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['article_id' => SORT_DESC],
		]);

		$this->load($params);

		if(!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.article_id' => isset($params['id']) ? $params['id'] : $this->article_id,
			't.cat_id' => isset($params['category']) ? $params['category'] : $this->cat_id,
			'cast(t.published_date as date)' => $this->published_date,
			't.headline' => $this->headline,
			't.comment_code' => $this->comment_code,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.updated_date as date)' => $this->updated_date,
			'cast(t.headline_date as date)' => $this->headline_date,
		]);

		if(isset($params['trash']))
			$query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
		else {
			if(!isset($params['publish']) || (isset($params['publish']) && $params['publish'] == ''))
				$query->andFilterWhere(['IN', 't.publish', [0,1]]);
			else
				$query->andFilterWhere(['t.publish' => $this->publish]);
		}

		$query->andFilterWhere(['like', 't.title', $this->title])
			->andFilterWhere(['like', 't.body', $this->body])
			->andFilterWhere(['like', 't.quote', $this->quote])
			->andFilterWhere(['like', 't.slug', $this->slug])
			->andFilterWhere(['like', 'category.name', $this->category_search])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname]);

		return $dataProvider;
	}
}