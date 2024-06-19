<?php
/**
 * m221010_204144_article_module_dropView_tag
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 10 October 2022, 08:04 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use Yii;
use yii\db\Schema;

class m221010_204144_article_module_dropView_tag extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP VIEW IF EXISTS `_article_statistic_tag`');
		$this->execute('DROP VIEW IF EXISTS `_article_tag`');
		$this->execute('DROP VIEW IF EXISTS `_articles`');

		// add view _articles
		$addViewArchives = <<< SQL
CREATE VIEW `_articles` AS
select
  `a`.`id`        AS `id`,
  `b`.`images`    AS `images`,
  `b`.`all`       AS `image_all`,
  `c`.`files`     AS `files`,
  `c`.`all`       AS `file_all`,
  `d`.`views`     AS `views`,
  `d`.`all`       AS `view_all`,
  `e`.`downloads` AS `downloads`,
  `f`.`likes`     AS `likes`,
  `f`.`all`       AS `like_all`
from (((((`ommu_articles` `a`
       left join `_article_statistic_media` `b`
         on (`a`.`id` = `b`.`article_id`))
      left join `_article_statistic_file` `c`
        on (`a`.`id` = `c`.`article_id`))
     left join `_article_statistic_view` `d`
       on (`a`.`id` = `d`.`article_id`))
    left join `_article_statistic_download` `e`
      on (`a`.`id` = `e`.`article_id`))
   left join `_article_statistic_like` `f`
     on (`a`.`id` = `f`.`article_id`))
group by `a`.`id`;
SQL;
		$this->execute($addViewArchives);
	}

	public function down()
	{
		$this->execute('DROP VIEW IF EXISTS `_article_statistic_tag`');
		$this->execute('DROP VIEW IF EXISTS `_article_tag`');
		$this->execute('DROP VIEW IF EXISTS `_articles`');

		// add view _article_statistic_tag
		$addViewArchiveStatisticTag = <<< SQL
CREATE VIEW `_article_statistic_tag` AS
select
  `a`.`article_id` AS `article_id`,
  count(`a`.`article_id`) AS `tags`
from `ommu_article_tag` `a`
group by `a`.`article_id`;
SQL;
		$this->execute($addViewArchiveStatisticTag);

		// add view _article_tag
		$addViewArchiveTag = <<< SQL
CREATE VIEW `_article_tag` AS
select
  `a`.`tag_id` AS `tag_id`,
  sum(case when `b`.`publish` = '1' then 1 else 0 end) AS `articles`,
  count(`a`.`article_id`) AS `all`
from (`ommu_article_tag` `a`
   left join `ommu_articles` `b`
     on (`a`.`article_id` = `b`.`id`))
group by `a`.`tag_id`;
SQL;
		$this->execute($addViewArchiveTag);

		// add view _articles
		$addViewArchives = <<< SQL
CREATE VIEW `_articles` AS
select
  `a`.`id`        AS `id`,
  `b`.`images`    AS `images`,
  `b`.`all`       AS `image_all`,
  `c`.`files`     AS `files`,
  `c`.`all`       AS `file_all`,
  `d`.`views`     AS `views`,
  `d`.`all`       AS `view_all`,
  `e`.`downloads` AS `downloads`,
  `f`.`tags`      AS `tags`,
  `g`.`likes`     AS `likes`,
  `g`.`all`       AS `like_all`
from ((((((`ommu_articles` `a`
        left join `_article_statistic_media` `b`
          on (`a`.`id` = `b`.`article_id`))
       left join `_article_statistic_file` `c`
         on (`a`.`id` = `c`.`article_id`))
      left join `_article_statistic_view` `d`
        on (`a`.`id` = `d`.`article_id`))
     left join `_article_statistic_download` `e`
       on (`a`.`id` = `e`.`article_id`))
    left join `_article_statistic_tag` `f`
      on (`a`.`id` = `f`.`article_id`))
   left join `_article_statistic_like` `g`
     on (`a`.`id` = `g`.`article_id`))
group by `a`.`id`;
SQL;
		$this->execute($addViewArchives);
    }
}
