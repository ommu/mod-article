<?php
/**
 * ArticleDownloadHistory
 *
 * This is the ActiveQuery class for [[\ommu\article\models\ArticleDownloadHistory]].
 * @see \ommu\article\models\ArticleDownloadHistory
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 12 May 2019, 18:26 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

namespace ommu\article\models\query;

class ArticleDownloadHistory extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\article\models\ArticleDownloadHistory[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\article\models\ArticleDownloadHistory|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
