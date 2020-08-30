ALTER TABLE `tblemployee` ADD `dob` DATE NULL AFTER `gender`;
ALTER TABLE `tblemployee` ADD `portfolio` VARCHAR(255) NOT NULL AFTER `resume`;
ALTER TABLE `tblemployee` ADD `interview` TINYINT NOT NULL COMMENT '1- For Sheduled' AFTER `portfolio`;