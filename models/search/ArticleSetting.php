<?php
/**
 * ArticleSetting
 *
 * ArticleSetting represents the model behind the search form about `ommu\article\models\ArticleSetting`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 20 October 2017, 09:34 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

namespace ommu\article\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\article\models\ArticleSetting as ArticleSettingModel;

class ArticleSetting extends ArticleSettingModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'permission', 'headline', 'headline_limit', 'media_image_limit', 'media_image_resize', 'modified_id'], 'integer'],
			[['license', 'meta_keyword', 'meta_description', 'headline_category', 'media_image_resize_size', 'media_image_view_size', 'media_image_type', 'media_file_type', 'modified_date', 'modifiedDisplayname'], 'safe'],
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
			$query = ArticleSettingModel::find()->alias('t');
		else
			$query = ArticleSettingModel::find()->alias('t')->select($column);
		$query->joinWith([
			'modified modified',
		])
		->groupBy(['id']);

		// add conditions that should always apply here
		$dataParams = [
			'query' => $query,
		];
		// disable pagination agar data pada api tampil semua
		if(isset($params['pagination']) && $params['pagination'] == 0)
			$dataParams['pagination'] = false;
		$dataProvider = new ActiveDataProvider($dataParams);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['modifiedDisplayname'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['id' => SORT_DESC],
		]);

		if(Yii::$app->request->get('id'))
			unset($params['id']);
		$this->load($params);

		if(!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.id' => isset($params['id']) ? $params['id'] : $this->id,
			't.permission' => $this->permission,
			't.headline' => $this->headline,
			't.headline_limit' => $this->headline_limit,
			't.media_image_limit' => $this->media_image_limit,
			't.media_image_resize' => $this->media_image_resize,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
		]);

		$query->andFilterWhere(['like', 't.license', $this->license])
			->andFilterWhere(['like', 't.meta_keyword', $this->meta_keyword])
			->andFilterWhere(['like', 't.meta_description', $this->meta_description])
			->andFilterWhere(['like', 't.headline_category', $this->headline_category])
			->andFilterWhere(['like', 't.media_image_resize_size', $this->media_image_resize_size])
			->andFilterWhere(['like', 't.media_image_view_size', $this->media_image_view_size])
			->andFilterWhere(['like', 't.media_image_type', $this->media_image_type])
			->andFilterWhere(['like', 't.media_file_type', $this->media_file_type])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname]);

		return $dataProvider;
	}
}
