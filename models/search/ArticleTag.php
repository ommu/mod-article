<?php
/**
 * ArticleTag
 *
 * ArticleTag represents the model behind the search form about `ommu\article\models\ArticleTag`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 20 October 2017, 11:00 WIB
 * @modified date 1 July 2021, 11:24 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

namespace ommu\article\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\article\models\ArticleTag as ArticleTagModel;
use yii\helpers\ArrayHelper;

class ArticleTag extends ArticleTagModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'article_id', 'tag_id', 'creation_id'], 'integer'],
			[['creation_date', 'tagBody', 'articleTitle', 'creationDisplayname'], 'safe'],
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
            $query = ArticleTagModel::find()
                ->alias('t')
                ->select(['*', 'count(t.id) as articles']);
        } else {
            $column = ArrayHelper::merge($column, ['count(t.id) as articles']);
            $query = ArticleTagModel::find()
                ->alias('t')
                ->select($column);
        }
		$query->joinWith([
			// 'tag tag', 
			// 'article article', 
			// 'creation creation'
		]);
        if ((isset($params['sort']) && in_array($params['sort'], ['tagBody', '-tagBody'])) || (isset($params['tagBody']) && $params['tagBody'] != '')) {
            $query->joinWith(['tag tag']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['articleTitle', '-articleTitle'])) || (isset($params['articleTitle']) && $params['articleTitle'] != '')) {
            $query->joinWith(['article article']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['creationDisplayname', '-creationDisplayname'])) || (isset($params['creationDisplayname']) && $params['creationDisplayname'] != '')) {
            $query->joinWith(['creation creation']);
        }

        $query->groupBy(['tag_id']);
        if (Yii::$app->request->get('tag') || Yii::$app->request->get('article')) {
            $query->groupBy(['id']);
        }

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
		$attributes['tagBody'] = [
			'asc' => ['tag.body' => SORT_ASC],
			'desc' => ['tag.body' => SORT_DESC],
		];
		$attributes['articleTitle'] = [
			'asc' => ['article.title' => SORT_ASC],
			'desc' => ['article.title' => SORT_DESC],
		];
		$attributes['creationDisplayname'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
		];
		$attributes['articles'] = [
			'asc' => ['articles' => SORT_ASC],
			'desc' => ['articles' => SORT_DESC],
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
			't.article_id' => isset($params['article']) ? $params['article'] : $this->article_id,
			't.tag_id' => isset($params['tag']) ? $params['tag'] : $this->tag_id,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
		]);

		$query->andFilterWhere(['like', 'tag.body', $this->tagBody])
			->andFilterWhere(['like', 'article.title', $this->articleTitle])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname]);

		return $dataProvider;
	}
}
