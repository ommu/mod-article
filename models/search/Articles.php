<?php
/**
 * Articles
 *
 * Articles represents the model behind the search form about `ommu\article\models\Articles`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 20 October 2017, 09:33 WIB
 * @modified date 13 May 2019, 21:24 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

namespace ommu\article\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\article\models\Articles as ArticlesModel;

class Articles extends ArticlesModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'publish', 'cat_id', 'headline', 'creation_id', 'modified_id'], 'integer'],
			[['title', 'body', 'published_date', 'headline_date', 'creation_date', 'modified_date', 'updated_date', 'categoryName', 'creationDisplayname', 'modifiedDisplayname', 'tag'], 'safe'],
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
        if (!($column && is_array($column))) {
            $query = ArticlesModel::find()->alias('t');
        } else {
            $query = ArticlesModel::find()->alias('t')->select($column);
        }
		$query->joinWith([
			'category.title category', 
			'creation creation', 
			'modified modified',
			'tags tags',
			'tags.tag tagsRltn',
			'view view',
		]);

		$query->groupBy(['id']);

        // add conditions that should always apply here
		$dataParams = [
			'query' => $query,
		];
        // disable pagination agar data pada api tampil semua
        if (isset($params['pagination']) && $params['pagination'] == 0) {
            $dataParams['pagination'] = false;
        }
		$dataProvider = new ActiveDataProvider($dataParams);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['cat_id'] = [
			'asc' => ['category.message' => SORT_ASC],
			'desc' => ['category.message' => SORT_DESC],
		];
		$attributes['categoryName'] = [
			'asc' => ['category.message' => SORT_ASC],
			'desc' => ['category.message' => SORT_DESC],
		];
		$attributes['creationDisplayname'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
		];
		$attributes['modifiedDisplayname'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$attributes['medias'] = [
			'asc' => ['view.images' => SORT_ASC],
			'desc' => ['view.images' => SORT_DESC],
		];
		$attributes['files'] = [
			'asc' => ['view.files' => SORT_ASC],
			'desc' => ['view.files' => SORT_DESC],
		];
		$attributes['views'] = [
			'asc' => ['view.views' => SORT_ASC],
			'desc' => ['view.views' => SORT_DESC],
		];
		$attributes['downloads'] = [
			'asc' => ['view.downloads' => SORT_ASC],
			'desc' => ['view.downloads' => SORT_DESC],
		];
		$attributes['likes'] = [
			'asc' => ['view.likes' => SORT_ASC],
			'desc' => ['view.likes' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['id' => SORT_DESC],
		]);

        if (Yii::$app->request->get('id')) {
            unset($params['id']);
        }
		$this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

		// grid filtering conditions
		$query->andFilterWhere([
			't.id' => $this->id,
			't.cat_id' => isset($params['category']) ? $params['category'] : $this->cat_id,
			'cast(t.published_date as date)' => $this->published_date,
			't.headline' => $this->headline,
			'cast(t.headline_date as date)' => $this->headline_date,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.updated_date as date)' => $this->updated_date,
		]);

        if (isset($params['status'])) {
			$query->andFilterCompare('t.publish', 1);
            if ($params['status'] == 'publish') {
                $query->andFilterWhere(['<=', 'cast(published_date as date)', Yii::$app->formatter->asDate('now', 'php:Y-m-d')]);
            } else if ($params['status'] == 'pending') {
                $query->andFilterWhere(['>', 'cast(published_date as date)', Yii::$app->formatter->asDate('now', 'php:Y-m-d')]);
            }
        } else {
            if (isset($params['trash'])) {
                $query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
            } else {
                if (!isset($params['publish']) || (isset($params['publish']) && $params['publish'] == '')) {
                    $query->andFilterWhere(['IN', 't.publish', [0,1]]);
                } else {
                    $query->andFilterWhere(['t.publish' => $this->publish]);
                }
            }
        }

        if (isset($params['tagId']) && $params['tagId']) {
            $query->andFilterWhere(['tags.tag_id' => $params['tagId']]);
        }

		$query->andFilterWhere(['like', 't.title', $this->title])
			->andFilterWhere(['like', 't.body', $this->body])
			->andFilterWhere(['like', 'category.message', $this->categoryName])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname])
			->andFilterWhere(['like', 'tagsRltn.body', $this->tag]);

		return $dataProvider;
	}
}
