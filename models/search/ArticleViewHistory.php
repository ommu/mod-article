<?php
/**
 * ArticleViewHistory
 *
 * ArticleViewHistory represents the model behind the search form about `ommu\article\models\ArticleViewHistory`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 20 October 2017, 11:02 WIB
 * @modified date 13 May 2019, 18:27 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

namespace ommu\article\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\article\models\ArticleViewHistory as ArticleViewHistoryModel;

class ArticleViewHistory extends ArticleViewHistoryModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'view_id', 'articleId'], 'integer'],
			[['view_date', 'view_ip', 'articleTitle'], 'safe'],
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
            $query = ArticleViewHistoryModel::find()->alias('t');
        } else {
            $query = ArticleViewHistoryModel::find()->alias('t')
                ->select($column);
        }
		$query->joinWith([
			// 'view view',
			// 'view.article viewRltn'
		]);
        if ((isset($params['sort']) && in_array($params['sort'], ['articleTitle', '-articleTitle'])) ||
            (isset($params['articleTitle']) && $params['articleTitle'] != '')
        ) {
            $query->joinWith(['article article']);
        }
        if (isset($params['article']) && $params['article'] != '') {
            $query->joinWith(['view view']);
        }

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
		$attributes['articleTitle'] = [
			'asc' => ['article.title' => SORT_ASC],
			'desc' => ['article.title' => SORT_DESC],
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
			't.view_id' => isset($params['view']) ? $params['view'] : $this->view_id,
			'cast(t.view_date as date)' => $this->view_date,
			'view.article_id' => $this->articleId,
		]);

		$query->andFilterWhere(['like', 't.view_ip', $this->view_ip])
			->andFilterWhere(['like', 'article.title', $this->articleTitle]);

		return $dataProvider;
	}
}
