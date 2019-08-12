<?php
/**
 * ArticleDownloads
 *
 * This is the ActiveQuery class for [[\ommu\article\models\ArticleDownloads]].
 * @see \ommu\article\models\ArticleDownloads
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 12 May 2019, 18:26 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

namespace ommu\article\models\query;

class ArticleDownloads extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\article\models\ArticleDownloads[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\article\models\ArticleDownloads|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
