<?php
/**
 * ArticleCategory
 *
 * This is the ActiveQuery class for [[\ommu\article\models\ArticleCategory]].
 * @see \ommu\article\models\ArticleCategory
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 11 May 2019, 21:53 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

namespace ommu\article\models\query;

class ArticleCategory extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 */
	public function published()
	{
		return $this->andWhere(['t.publish' => 1]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function unpublish()
	{
		return $this->andWhere(['t.publish' => 0]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function deleted()
	{
		return $this->andWhere(['t.publish' => 2]);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\article\models\ArticleCategory[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\article\models\ArticleCategory|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
