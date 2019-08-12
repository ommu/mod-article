<?php
/**
 * ArticleSetting
 *
 * This is the ActiveQuery class for [[\ommu\article\models\ArticleSetting]].
 * @see \ommu\article\models\ArticleSetting
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 11 May 2019, 22:46 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

namespace ommu\article\models\query;

class ArticleSetting extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\article\models\ArticleSetting[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\article\models\ArticleSetting|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
