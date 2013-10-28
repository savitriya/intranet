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


ALTER TABLE  `Activities` ADD INDEX (  `project_id` );
ALTER TABLE  `Activities` ADD INDEX (  `category_id` );
ALTER TABLE  `Activities` ADD INDEX (  `user_id` );
ALTER TABLE  `Activities` ADD INDEX (  `assigned_to_id` );
ALTER TABLE  `Activities` ADD INDEX (  `status_id` );

ALTER TABLE  `user` ADD INDEX (  `company_id` );

ALTER TABLE  `login` ADD INDEX (  `user_id` );

ALTER TABLE  `login` ADD INDEX (  `loggedinby` );

ALTER TABLE  `login` ADD INDEX (  `loggedoutby` );

ALTER TABLE  `Activities` ADD INDEX (  `created_date` );
ALTER TABLE  `activityhistory` ADD INDEX (  `activity_id` );

ALTER TABLE  `activityhistory` ADD INDEX (  `project_id` );






ALTER TABLE  `activityhistory` ADD INDEX (  `milestone_id` );
ALTER TABLE  `activityhistory` ADD INDEX (  `created_date` );
ALTER TABLE  `activityhistory` ADD INDEX (  `activity_by_id` );
ALTER TABLE  `activitystatuses` ADD INDEX (  `is_default` );

ALTER TABLE  `activity_log` ADD INDEX (  `project_id` );
ALTER TABLE  `activity_log` ADD INDEX (  `milestone_id` );
ALTER TABLE  `activity_log` ADD INDEX (  `user_id` );
ALTER TABLE  `activity_log` ADD INDEX (  `activity_id` );
ALTER TABLE  `activity_log` ADD INDEX (  `activity_date` );
ALTER TABLE  `activity_log` ADD INDEX (  `category_id` );

ALTER TABLE  `Assignee` ADD INDEX (  `project_id` );
ALTER TABLE  `Assignee` ADD INDEX (  `milestone_id` );
ALTER TABLE  `Assignee` ADD INDEX (  `activity_id` );
ALTER TABLE  `Assignee` ADD INDEX (  `user_id` );

ALTER TABLE  `contact` ADD INDEX (  `user_id` );
ALTER TABLE  `leave` ADD INDEX (  `created_date` );
ALTER TABLE  `leave` ADD INDEX (  `user_id` );
ALTER TABLE  `login` DROP INDEX  `user_id`;
ALTER TABLE  `login` ADD INDEX (  `created_date` );
ALTER TABLE  `milestones` ADD INDEX (  `project_id` );
ALTER TABLE  `milestones` ADD INDEX (  `status_id` );
ALTER TABLE  `milestones` ADD INDEX (  `isdefault` );
ALTER TABLE  `Projects` ADD INDEX (  `type_id` );
ALTER TABLE  `Projects` ADD INDEX (  `status_id` );
ALTER TABLE  `projectstatuses` ADD INDEX (  `is_default` );
ALTER TABLE  `projecttypes` ADD INDEX (  `is_default` );
ALTER TABLE  `preferences` ADD INDEX (  `user_id` );
ALTER TABLE  `projecthistory` ADD INDEX (  `project_id` );
ALTER TABLE  `projecthistory` ADD INDEX (  `created_date` );
ALTER TABLE  `projecthistory` ADD INDEX (  `activity_by_id` );
