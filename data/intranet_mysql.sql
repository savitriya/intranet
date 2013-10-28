########################################################
Date:8th,March 2013 Maulik Shah

CREATE TABLE `leave` (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT NOT NULL, start_date INT NOT NULL, end_date INT NOT NULL, no_of_days INT NOT NULL, description LONGTEXT NOT NULL, is_sanctioned INT NOT NULL, created_date INT NOT NULL, created_time INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE activity_log (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, project_id INT NOT NULL, milestone_id INT NOT NULL, activity_id INT NOT NULL, category_id INT NOT NULL, description VARCHAR(255) NOT NULL, created_datetime INT NOT NULL, activity_date INT NOT NULL, seconds_spent INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE projecthistory (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, description LONGTEXT NOT NULL, created_date INT NOT NULL, created_time INT NOT NULL, activity_by_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE Holiday (id INT AUTO_INCREMENT NOT NULL, holidayname VARCHAR(255) NOT NULL, date INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, paddress VARCHAR(255) NOT NULL, raddress VARCHAR(255) NOT NULL, altcontactnumber INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE activityhistory (id INT AUTO_INCREMENT NOT NULL, activity_id INT NOT NULL, project_id INT NOT NULL, milestone_id INT DEFAULT NULL, description LONGTEXT NOT NULL, created_date INT NOT NULL, created_time INT NOT NULL, activity_by_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE tmp (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, logintime VARCHAR(255) NOT NULL, logouttime VARCHAR(255) NOT NULL, ipaddress VARCHAR(255) NOT NULL, created_date VARCHAR(255) NOT NULL, INDEX IDX_2CEF7D48A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE test (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE preferences (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, cc LONGTEXT NOT NULL, tomail LONGTEXT NOT NULL, leave_contact LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE Assignee (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, user_id INT NOT NULL, milestone_id INT NOT NULL, activity_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE login (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, logintime INT NOT NULL, logouttime INT DEFAULT NULL, ipaddress VARCHAR(255) DEFAULT NULL, created_date INT NOT NULL, loggedinby INT NOT NULL, loggedoutby INT NOT NULL, created_time INT NOT NULL, INDEX IDX_AA08CB10A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, fname VARCHAR(255) NOT NULL, lname VARCHAR(255) DEFAULT NULL, mobile VARCHAR(255) DEFAULT NULL, isactive INT DEFAULT NULL, isadmin INT DEFAULT NULL, dob VARCHAR(255) DEFAULT NULL, doy INT DEFAULT NULL, company_id INT DEFAULT NULL, joiningdate VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE projectstatuses (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, is_default INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE activitycategories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_9077C0C25E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE milestones (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, project_id INT NOT NULL, estimated_startdate INT NOT NULL, estimated_enddate INT NOT NULL, actual_startdate INT NOT NULL, actual_enddate INT NOT NULL, status_id INT NOT NULL, estimated_hours INT DEFAULT NULL, isdefault INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE Projects (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type_id INT NOT NULL, estimated_startdate INT NOT NULL, estimated_enddate INT DEFAULT NULL, actual_startdate INT DEFAULT NULL, actual_enddate INT DEFAULT NULL, status_id INT NOT NULL, estimated_hours INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE sentmails (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, table_name VARCHAR(255) DEFAULT NULL, type_id INT NOT NULL, mail_to VARCHAR(255) NOT NULL, cc VARCHAR(255) DEFAULT NULL, bcc VARCHAR(255) DEFAULT NULL, mail_from VARCHAR(255) NOT NULL, content VARCHAR(255) NOT NULL, created_date_time INT NOT NULL, sent_date_time INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE projecttypes (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, is_default INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE activitystatuses (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, is_default INT NOT NULL, UNIQUE INDEX UNIQ_3FEA898C5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE Activities (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, category_id INT NOT NULL, milestone_id INT NOT NULL, subject VARCHAR(255) NOT NULL, priority_id INT NOT NULL, user_id INT NOT NULL, assigned_to_id INT DEFAULT NULL, status_id INT NOT NULL, due_date INT DEFAULT NULL, due_time INT DEFAULT NULL, description LONGTEXT NOT NULL, created_date INT NOT NULL, created_time INT NOT NULL, estimated_hours INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
ALTER TABLE tmp ADD CONSTRAINT FK_2CEF7D48A76ED395 FOREIGN KEY (user_id) REFERENCES user (id);
ALTER TABLE login ADD CONSTRAINT FK_AA08CB10A76ED395 FOREIGN KEY (user_id) REFERENCES user (id);

INSERT INTO `intranet`.`user` (`id`, `email`, `password`, `fname`, `lname`, `mobile`, `isactive`, `isadmin`, `dob`, `doy`, `company_id`, `joiningdate`) VALUES ('1', 'maulik@savitriya.com', '21df07c02796f71ed40e4663f6a6bb79', 'maulik', 'shah', '9428534990', '1', '1', '12-11', '1987', NULL, NULL);


#Updated Sequence of fields

CREATE TABLE `leave` (id INT AUTO_INCREMENT NOT NULL, title LONGTEXT NOT NULL, start_date INT NOT NULL, end_date INT NOT NULL, no_of_days INT NOT NULL, description LONGTEXT NOT NULL, is_sanctioned INT NOT NULL, created_date INT NOT NULL, created_time INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE activity_log (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, milestone_id INT NOT NULL, user_id INT NOT NULL, activity_id INT NOT NULL, description VARCHAR(255) NOT NULL, created_datetime INT NOT NULL, activity_date INT NOT NULL, seconds_spent INT NOT NULL, category_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE projecthistory (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, description LONGTEXT NOT NULL, created_date INT NOT NULL, created_time INT NOT NULL, activity_by_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE Holiday (id INT AUTO_INCREMENT NOT NULL, holidayname VARCHAR(255) NOT NULL, date INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, paddress VARCHAR(255) NOT NULL, raddress VARCHAR(255) NOT NULL, altcontactnumber INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE activityhistory (id INT AUTO_INCREMENT NOT NULL, activity_id INT NOT NULL, project_id INT NOT NULL, milestone_id INT DEFAULT NULL, description LONGTEXT NOT NULL, created_date INT NOT NULL, created_time INT NOT NULL, activity_by_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE tmp (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, logintime VARCHAR(255) NOT NULL, logouttime VARCHAR(255) NOT NULL, ipaddress VARCHAR(255) NOT NULL, created_date VARCHAR(255) NOT NULL, INDEX IDX_2CEF7D48A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE test (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE preferences (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, cc LONGTEXT NOT NULL, tomail LONGTEXT NOT NULL, leave_contact LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE Assignee (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, milestone_id INT NOT NULL, activity_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE login (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, logintime INT NOT NULL, logouttime INT DEFAULT NULL, ipaddress VARCHAR(255) DEFAULT NULL, created_date INT NOT NULL, created_time INT NOT NULL, loggedinby INT NOT NULL, loggedoutby INT NOT NULL, INDEX IDX_AA08CB10A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, fname VARCHAR(255) NOT NULL, lname VARCHAR(255) DEFAULT NULL, mobile VARCHAR(255) DEFAULT NULL, doy INT DEFAULT NULL, dob VARCHAR(255) DEFAULT NULL, isactive INT DEFAULT NULL, isadmin INT DEFAULT NULL, company_id INT DEFAULT NULL, joiningdate VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE projectstatuses (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, is_default INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE activitycategories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_9077C0C25E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE milestones (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, project_id INT NOT NULL, estimated_startdate INT NOT NULL, estimated_enddate INT NOT NULL, actual_startdate INT NOT NULL, actual_enddate INT NOT NULL, status_id INT NOT NULL, estimated_hours INT DEFAULT NULL, isdefault INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE Projects (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type_id INT NOT NULL, estimated_startdate INT NOT NULL, estimated_enddate INT DEFAULT NULL, actual_startdate INT DEFAULT NULL, actual_enddate INT DEFAULT NULL, status_id INT NOT NULL, estimated_hours INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE sentmails (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, table_name VARCHAR(255) DEFAULT NULL, type_id INT NOT NULL, mail_to VARCHAR(255) NOT NULL, cc VARCHAR(255) DEFAULT NULL, bcc VARCHAR(255) DEFAULT NULL, mail_from VARCHAR(255) NOT NULL, content VARCHAR(255) NOT NULL, created_date_time INT NOT NULL, sent_date_time INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE projecttypes (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, is_default INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE activitystatuses (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, is_default INT NOT NULL, UNIQUE INDEX UNIQ_3FEA898C5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE Activities (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, category_id INT NOT NULL, subject VARCHAR(255) NOT NULL, priority_id INT NOT NULL, user_id INT NOT NULL, assigned_to_id INT DEFAULT NULL, status_id INT NOT NULL, due_date INT DEFAULT NULL, due_time INT DEFAULT NULL, description LONGTEXT NOT NULL, created_date INT NOT NULL, created_time INT NOT NULL, estimated_hours INT DEFAULT NULL, milestone_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
ALTER TABLE tmp ADD CONSTRAINT FK_2CEF7D48A76ED395 FOREIGN KEY (user_id) REFERENCES user (id);
ALTER TABLE login ADD CONSTRAINT FK_AA08CB10A76ED395 FOREIGN KEY (user_id) REFERENCES user (id);


RENAME TABLE  `intranet`.`Activities` TO  `intranet`.`activities` ;

###############################################################################################################################################

Maulik Shah 28th March,2013
update user set joiningdate=NULL where id in (1,17);
###############################################################################################################################################=======
###############################################################################################################################################

########################################################
Date:29th,March 2013 Rohit Rathod
<<<<<<< .mine
ALTER TABLE `user` CHANGE `joiningdate` `joiningdate` INT( 11 ) NULL DEFAULT NULL

########################################################
Date:15th,April 2013 Maulik Shah
ALTER TABLE  `activity_log` CHANGE  `description`  `description` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
=======
ALTER TABLE `user` CHANGE `joiningdate` `joiningdate` INT( 11 ) NULL DEFAULT NULL;
ALTER TABLE `leave` CHANGE `description` `description` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;

########################################################
Date:30th,March 2013 Rohit Rathod
ALTER TABLE `sentmails` CHANGE `content` `content` LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

#############################################################
Date:3Apr2013 Nitin rajpurohit

update login set created_date=1364927400 where created_date=1364907600;

##########################################################
Date:11Apr2013 Nitin rajpurohit

ALTER TABLE `contact` ADD FOREIGN KEY ( `user_id` ) REFERENCES `user` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

delete from contact where user_id not in (select id from user);

DROP TABLE tmp;

alter table login drop FOREIGN KEY FK_AA08CB10A76ED395;

ALTER TABLE `login` ADD FOREIGN KEY ( `user_id` ) REFERENCES `user` (
`id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

#############################################################
Date:15Apr 2013

ALTER TABLE `activity_log` CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL 

####################################################################
Date:25Apr 2013
UPDATE  Activities JOIN milestones ON Activities.project_id = milestones.project_id SET  Activities.milestone_id = milestones.id

##################################################################
Date:10May 2013
ALTER TABLE `sentmails` ADD COLUMN `user_id` INTEGER

##################################################################
Date:16May 2013
ALTER TABLE `user` ADD COLUMN `needdailyreport` INTEGER
*
UPDATE activity_log
SET  `milestone_id`= (SELECT Activities.milestone_id
                     FROM Activities
                     WHERE Activities.id = activity_log.activity_id)

##################################################################
25th May Maulik
ALTER TABLE `user` DROP `doy`;
	update  `user` set  `dob`=NULL,`joiningdate`=NULL;
ALTER TABLE  `user` CHANGE  `joiningdate`  `joiningdate` INT NULL DEFAULT NULL;
ALTER TABLE  `user` CHANGE  `dob`  `dob` INT NULL DEFAULT NULL;
ALTER TABLE  `contact` CHANGE  `altcontactnumber`  `altcontactnumber` BIGINT( 20 ) NOT NULL;
##################################################################
7th Jun 2013 Nitin RajPurohit
ALTER  TABLE `user`  ADD leavingdate int

16th July 2013 Maulik
##################################################################
ALTER  TABLE `milestones`  ADD `description` text COLLATE utf8_unicode_ci;

##################################################################
26Jul 2013 Nitin rajpurohit

CREATE TABLE IF NOT EXISTS `milestones_assignee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `milestone_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
####################################################################

--19-sept-2013 Rohit Rathod
ALTER TABLE `contact` CHANGE `altcontactnumber` `altcontactnumber` BIGINT( 20 ) NULL;
####################################################################

--20-Sept-2013 Rohit Rathod
ALTER TABLE `sentmails` CHANGE `content` `content` LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
####################################################################

--11/Oct/2013 Rohit Rathod
-- Table structure for table `timing_slot`
CREATE TABLE IF NOT EXISTS `timing_slot` (
  `slot_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `slot_name` varchar(255) NOT NULL,
  `slot_login_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`slot_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- Dumping data for table `timing_slot`
INSERT INTO `timing_slot` (`slot_id`, `slot_name`, `slot_login_time`) VALUES
(1, 'slot 9', 32400),
(2, 'slot 9 30', 34200);

-- Add new timing slot id field in 'users' table
ALTER TABLE `user` ADD `timing_slot_id` INT( 11 ) UNSIGNED NOT NULL AFTER `dob`;
####################################################################

