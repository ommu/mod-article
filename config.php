<?php
/**
 * archive module config
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 15 September 2017, 19:05 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

return [
	'id' => 'article',
	'class' => ommu\article\Module::className(),
	// seting url manager untuk api di sini.
	'urlManagerRules' => [
		[
			'class' => 'yii\rest\UrlRule', 
			'controller' => [
				'article/v1/article',
				'article/v1/category',
			],
			'pluralize' => false,
		],
	],
];