<?php
/**
 * Events class
 *
 * Menangani event-event yang ada pada modul article.
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 14 May 2019, 10:13 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

namespace ommu\article;

use Yii;
use yii\helpers\Inflector;
use app\models\CoreTags;
use ommu\article\models\ArticleTag;

class Events extends \yii\base\BaseObject
{
	/**
	 * {@inheritdoc}
	 */
	public static function BeforeSaveArticle($event)
	{
		$article = $event->sender;

		self::setArticleTag($article);
	}

	/**
	 * {@inheritdoc}
	 */
	public static function setArticleTag($article)
	{
		$oldTag = $article->getTags(true, 'title');
        if ($article->tag) {
            $tag = explode(',', $article->tag);
        }

		// insert difference tag
        if (is_array($tag)) {
			foreach ($tag as $val) {
                if (in_array($val, $oldTag)) {
					unset($oldTag[array_keys($oldTag, $val)[0]]);
					continue;
				}

				$tagFind = CoreTags::find()
					->select(['tag_id'])
					->andWhere(['body' => Inflector::camelize($val)])
					->one();

                if ($tagFind != null) {
                    $tag_id = $tagFind->tag_id;
                } else {
					$model = new CoreTags();
					$model->body = $val;
                    if ($model->save()) {
                        $tag_id = $model->tag_id;
                    }
				}

				$model = new ArticleTag();
				$model->article_id = $article->id;
				$model->tag_id = $tag_id;
				$model->save();
			}
		}

		// drop difference tag
        if (!empty($oldTag)) {
			foreach ($oldTag as $key => $val) {
				ArticleTag::find()
					->select(['id'])
					->where(['article_id' => $article->id, 'tag_id' => $key])
					->one()
					->delete();
			}
		}
	}
}
