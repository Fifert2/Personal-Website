USE mySite;

CREATE TABLE `siteComments` (
   `commentid` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
   `visitor_name` VARCHAR(100) NOT NULL,
   `email` VARCHAR(200) NOT NULL,
   `comment_text` TEXT NOT NULL,
   `feature_suggestion` TEXT,
   `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
   `status` VARCHAR(20) NOT NULL DEFAULT 'approved',
   PRIMARY KEY (`commentid`)
);
