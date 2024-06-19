<?php
/**
 * ArticleTag
 *
 * This is the ActiveQuery class for [[\ommu\article\models\ArticleTag]].
 * @see \ommu\article\models\ArticleTag
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 12 May 2019, 18:50 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

namespace ommu\article\models\query;

class ArticleTag extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\article\models\ArticleTag[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\article\models\ArticleTag|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
