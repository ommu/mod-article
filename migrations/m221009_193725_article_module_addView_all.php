<?php
/**
 * m221009_193725_article_module_addView_all
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 9 October 2022, 19:39 WIB
 * @link https://github.com/ommu/mod-article
 *
 */

use Yii;
use yii\db\Schema;

class m221009_193725_article_module_addView_all extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP VIEW IF EXISTS `_article_category`');
		$this->execute('DROP VIEW IF EXISTS `_article_files`');
		$this->execute('DROP VIEW IF EXISTS `_article_likes`');
		$this->execute('DROP VIEW IF EXISTS `_article_media`');
		$this->execute('DROP VIEW IF EXISTS `_article_statistic_download`');
		$this->execute('DROP VIEW IF EXISTS `_article_statistic_file`');
		$this->execute('DROP VIEW IF EXISTS `_article_statistic_like`');
		$this->execute('DROP VIEW IF EXISTS `_article_statistic_media`');
		$this->execute('DROP VIEW IF EXISTS `_article_statistic_tag`');
		$this->execute('DROP VIEW IF EXISTS `_article_statistic_view`');
		$this->execute('DROP VIEW IF EXISTS `_article_tag`');
		$this->execute('DROP VIEW IF EXISTS `_articles`');

		// add view _article_category
		$addViewArticleCategory = <<< SQL
CREATE VIEW `_article_category` AS
select
  `a`.`id` AS `id`,
  sum(case when `b`.`publish` = '1' and `b`.`published_date` <= curdate() then 1 else 0 end) AS `publish`,
  sum(case when `b`.`publish` = '1' and `b`.`published_date` > curdate() then 1 else 0 end) AS `pending`,
  sum(case when `b`.`publish` = '0' then 1 else 0 end) AS `unpublish`,
  count(`b`.`cat_id`) AS `all`,
  max(case when `b`.`publish` = '1' and `b`.`published_date` <= curdate() then `b`.`id` end) AS `article_id`
from (`ommu_article_category` `a`
   left join `ommu_articles` `b`
     on (`a`.`id` = `b`.`cat_id`))
group by `a`.`id`;
SQL;
		$this->execute($addViewArticleCategory);

		// add view _article_files
		$addViewArchiveFiles = <<< SQL
CREATE VIEW `_article_files` AS
select
  `a`.`id`         AS `id`,
  `a`.`article_id` AS `article_id`,
  sum(`b`.`downloads`) AS `downloads`
from (`ommu_article_files` `a`
   left join `ommu_article_downloads` `b`
     on (`a`.`id` = `b`.`file_id`))
group by `a`.`id`;
SQL;
		$this->execute($addViewArchiveFiles);

		// add view _article_likes
		$addViewArchiveLikes = <<< SQL
CREATE VIEW `_article_likes` AS
select
  `a`.`id` AS `id`,
  sum(case when `b`.`publish` = '1' then 1 else 0 end) AS `likes`,
  count(`b`.`like_id`) AS `all`
from (`ommu_article_likes` `a`
   left join `ommu_article_like_history` `b`
     on (`a`.`id` = `b`.`like_id`))
group by `a`.`id`;
SQL;
		$this->execute($addViewArchiveLikes);

		// add view _article_media
		$addViewArchiveMedia = <<< SQL
CREATE VIEW `_article_media` AS
select
  `a`.`id` AS `id`,
  (case when `a`.`caption` <> '' then 1 else 0 end) AS `caption`,
  (case when `a`.`description` <> '' then 1 else 0 end) AS `description`
from `ommu_article_media` `a`
group by `a`.`id`;
SQL;
		$this->execute($addViewArchiveMedia);

		// add view _article_statistic_download
		$addViewArchiveStatisticDownload = <<< SQL
CREATE VIEW `_article_statistic_download` AS
select
  `a`.`article_id` AS `article_id`,
  sum(`a`.`downloads`) AS `downloads`
from `_article_files` `a`
group by `a`.`article_id`;
SQL;
		$this->execute($addViewArchiveStatisticDownload);

		// add view _article_statistic_file
		$addViewArchiveStatisticFile = <<< SQL
CREATE VIEW `_article_statistic_file` AS
select
  `a`.`article_id` AS `article_id`,
  sum(case when `a`.`publish` = '1' then 1 else 0 end) AS `files`,
  count(`a`.`id`)  AS `all`
from `ommu_article_files` `a`
group by `a`.`id`;
SQL;
		$this->execute($addViewArchiveStatisticFile);

		// add view _article_statistic_like
		$addViewArchiveStatisticLike = <<< SQL
CREATE VIEW `_article_statistic_like` AS
select
  `a`.`article_id` AS `article_id`,
  sum(case when `a`.`publish` = '1' then 1 else 0 end) AS `likes`,
  count(`a`.`article_id`) AS `all`
from `ommu_article_likes` `a`
group by `a`.`article_id`;
SQL;
		$this->execute($addViewArchiveStatisticLike);

		// add view _article_statistic_media
		$addViewArchiveStatisticMedia = <<< SQL
CREATE VIEW `_article_statistic_media` AS
select
  `a`.`article_id` AS `article_id`,
  sum(case when `a`.`publish` = '1' then 1 else 0 end) AS `images`,
  count(`a`.`id`)  AS `all`
from `ommu_article_media` `a`
group by `a`.`article_id`;
SQL;
		$this->execute($addViewArchiveStatisticMedia);

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

		// add view _article_statistic_view
		$addViewArchiveStatisticView = <<< SQL
CREATE VIEW `_article_statistic_view` AS
select
  `a`.`article_id` AS `article_id`,
  sum(case when `a`.`publish` = '1' then `a`.`views` else 0 end) AS `views`,
  sum(`a`.`views`) AS `all`
from `ommu_article_views` `a`
group by `a`.`article_id`;
SQL;
		$this->execute($addViewArchiveStatisticView);

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

	public function down()
	{
		$this->execute('DROP VIEW IF EXISTS `_article_category`');
		$this->execute('DROP VIEW IF EXISTS `_article_files`');
		$this->execute('DROP VIEW IF EXISTS `_article_likes`');
		$this->execute('DROP VIEW IF EXISTS `_article_media`');
		$this->execute('DROP VIEW IF EXISTS `_article_statistic_download`');
		$this->execute('DROP VIEW IF EXISTS `_article_statistic_file`');
		$this->execute('DROP VIEW IF EXISTS `_article_statistic_like`');
		$this->execute('DROP VIEW IF EXISTS `_article_statistic_media`');
		$this->execute('DROP VIEW IF EXISTS `_article_statistic_tag`');
		$this->execute('DROP VIEW IF EXISTS `_article_statistic_view`');
		$this->execute('DROP VIEW IF EXISTS `_article_tag`');
		$this->execute('DROP VIEW IF EXISTS `_articles`');
    }
}
