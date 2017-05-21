<?php
/**
 * ArticleKeyword
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/mod-article
 * @contact (+62)856-299-4114
 *
 */

class ArticleKeyword extends CWidget
{

	public function init() {
	}

	public function run() {
		$this->renderContent();
	}

	protected function renderContent() {
		
		$this->render('article_keyword');	
	}
}
