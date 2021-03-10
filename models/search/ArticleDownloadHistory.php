<?php
/**
 * ArticleDownloadHistory
 *
 * ArticleDownloadHistory represents the model behind the search form about `ommu\article\models\ArticleDownloadHistory`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 20 October 2017, 10:38 WIB
 * @modified date 13 May 2019, 09:42 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

namespace ommu\article\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\article\models\ArticleDownloadHistory as ArticleDownloadHistoryModel;

class ArticleDownloadHistory extends ArticleDownloadHistoryModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'download_id'], 'integer'],
			[['download_date', 'download_ip', 'fileName', 'articleTitle'], 'safe'],
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
            $query = ArticleDownloadHistoryModel::find()->alias('t');
        } else {
            $query = ArticleDownloadHistoryModel::find()->alias('t')->select($column);
        }
		$query->joinWith([
			'download.file file',
			'download.file.article article'
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
		$attributes['fileName'] = [
			'asc' => ['file.file_filename' => SORT_ASC],
			'desc' => ['file.file_filename' => SORT_DESC],
		];
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
			't.download_id' => isset($params['download']) ? $params['download'] : $this->download_id,
			'cast(t.download_date as date)' => $this->download_date,
		]);

		$query->andFilterWhere(['like', 't.download_ip', $this->download_ip])
			->andFilterWhere(['like', 'file.file_filename', $this->fileName])
			->andFilterWhere(['like', 'article.title', $this->articleTitle]);

		return $dataProvider;
	}
}
