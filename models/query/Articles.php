<?php
/**
 * Articles
 *
 * This is the ActiveQuery class for [[\ommu\article\models\Articles]].
 * @see \ommu\article\models\Articles
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 12 May 2019, 18:51 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

namespace ommu\article\models\query;

class Articles extends \yii\db\ActiveQuery
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
		return $this->andWhere(['publish' => 1]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function unpublish() 
	{
		return $this->andWhere(['publish' => 0]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function deleted() 
	{
		return $this->andWhere(['publish' => 2]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function headlined() 
	{
		return $this->andWhere(['publish' => 1])
			->andWhere(['headline' => 1]);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\article\models\Articles[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\article\models\Articles|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
