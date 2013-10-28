CREATE TABLE activities (id INTEGER NOT NULL, project_id INTEGER NOT NULL, category_id INTEGER NOT NULL, subject VARCHAR(255) NOT NULL, priority_id INTEGER NOT NULL, user_id INTEGER NOT NULL, assigned_to_id INTEGER DEFAULT NULL, status_id INTEGER NOT NULL, due_date INTEGER DEFAULT NULL, due_time INTEGER DEFAULT NULL, description CLOB NOT NULL, created_date INTEGER NOT NULL, created_time INTEGER NOT NULL, PRIMARY KEY(id));

CREATE TABLE "activity_log" ("id" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL ,"project_id" INTEGER NOT NULL,"milestone_id" INTEGER, "user_id" INTEGER, "activity_id" INTEGER, "description" TEXT, "created_datetime" INTEGER,"activity_date" INTEGER, "seconds_spent" INTEGER);

CREATE TABLE activitycategories (id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, PRIMARY KEY(id));

CREATE TABLE activitystatuses (id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, is_default INTEGER NOT NULL, PRIMARY KEY(id));

CREATE TABLE holiday (id INTEGER NOT NULL, holidayname VARCHAR(255) NOT NULL, date VARCHAR(255) NOT NULL, PRIMARY KEY(id));

CREATE TABLE "milestones" ("id" INTEGER PRIMARY KEY  AUTOINCREMENT ,"name" VARCHAR,"project_id" INTEGER, "estimated_startdate" INTEGER, "estimated_enddate" INTEGER, "actual_startdate" INTEGER, "actual_enddate" INTEGER, "status_id" INTEGER);

CREATE TABLE projects (id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, type_id INTEGER NOT NULL, estimated_startdate INTEGER NOT NULL, estimated_enddate INTEGER DEFAULT NULL, actual_startdate INTEGER DEFAULT NULL, actual_enddate INTEGER DEFAULT NULL, status_id INTEGER NOT NULL, PRIMARY KEY(id));

CREATE TABLE projectstatuses (id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, is_default INTEGER NOT NULL, PRIMARY KEY(id));

CREATE TABLE [projecttypes] (
[id] INTEGER  NOT NULL PRIMARY KEY AUTOINCREMENT,
[name] VARCHAR(255)  NOT NULL,
[color] VARCHAR(255)  NOT NULL,
[is_default] INTEGER  NOT NULL
);	

CREATE UNIQUE INDEX UNIQ_9077C0C25E237E06 ON activitycategories (name);

CREATE UNIQUE INDEX UNIQ_3FEA898C5E237E06 ON activitystatuses (name);

CREATE TABLE "projectstatuses" ("id" INTEGER PRIMARY KEY  AUTOINCREMENT , "name" VARCHAR, "color" VARCHAR, "is_default" INTEGER)

CREATE TABLE activities (id INTEGER NOT NULL, project_id INTEGER NOT NULL, category_id INTEGER NOT NULL, subject VARCHAR(255) NOT NULL, priority_id INTEGER NOT NULL, user_id INTEGER NOT NULL, assigned_to_id INTEGER DEFAULT NULL, status_id INTEGER NOT NULL, due_date INTEGER DEFAULT NULL, due_time INTEGER DEFAULT NULL, description CLOB NOT NULL, created_date INTEGER NOT NULL, created_time INTEGER NOT NULL, PRIMARY KEY(id))


CREATE TABLE "activity_log" ("id" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL ,"project_id" INTEGER NOT NULL,"milestone_id" INTEGER, "user_id" INTEGER, "activity_id" INTEGER, "description" TEXT, "created_datetime" INTEGER,"activity_date" INTEGER, "seconds_spent" INTEGER)

CREATE TABLE "milestones" ("id" INTEGER PRIMARY KEY  AUTOINCREMENT , "project_id" INTEGER, "estimated_startdate" INTEGER, "estimated_enddate" INTEGER, "actual_startdate" INTEGER, "actual_enddate" INTEGER, "status" INTEGER, "name" VARCHAR)

CREATE TABLE activityhistory (id INTEGER NOT NULL, activity_id INTEGER NOT NULL, project_id INTEGER NOT NULL, milestone_id INTEGER DEFAULT NULL, status_id INTEGER NOT NULL, description CLOB NOT NULL, created_date INTEGER NOT NULL, created_time INTEGER NOT NULL, user_id INTEGER NOT NULL, assigned_to_id INTEGER DEFAULT NULL, PRIMARY KEY(id))
ALTER TABLE "activity_log" ADD COLUMN "category_id" INTEGER;

DROP TABLE IF EXISTS "activitycategories";
CREATE TABLE [activityhistory] (
[id] INTEGER  NOT NULL PRIMARY KEY AUTOINCREMENT,
[activity_id] INTEGER  NOT NULL,
[project_id] INTEGER  NOT NULL,
[milestone_id] INTEGER  NULL,
[description] TEXT  NOT NULL,
[created_date] INTEGER  NOT NULL,
[created_time] INTEGER  NOT NULL,
[activity_by_id] INTEGER  NOT NULL
);
ALTER TABLE "milestones" ADD COLUMN "estimated_hours" INTEGER
ALTER TABLE "projects" ADD COLUMN "estimated_hours" INTEGER
ALTER TABLE "activities" ADD COLUMN "estimated_hours" INTEGER

ALTER TABLE "user" ADD COLUMN "isadmin" INTEGER

ALTER TABLE "main"."activities" ADD COLUMN "milestone_id" INTEGER

CREATE TABLE "assignee" ("id" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL , "project_id" INTEGER NOT NULL , "milestone_id" INTEGER, "activity_id" INTEGER, "user_id" INTEGER NOT NULL )


CREATE TABLE [projecthistory] (
[id] INTEGER  NOT NULL PRIMARY KEY AUTOINCREMENT,
[project_id] INTEGER  NOT NULL,
[description] TEXT  NOT NULL,
[created_date] INTEGER  NOT NULL,
[created_time] INTEGER  NOT NULL,
[activity_by_id] INTEGER  NOT NULL
)

CREATE TABLE "sentmails" ("id" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL , "parent_id" INTEGER, "table_name" VARCHAR, "type_id" INTEGER NOT NULL , "to" VARCHAR NOT NULL , "cc" VARCHAR NOT NULL , "bcc" VARCHAR NOT NULL , "from" VARCHAR NOT NULL , "content" VARCHAR NOT NULL , "createddatettime" INTEGER NOT NULL , "sentdatetime" INTEGER NOT NULL );
 ===============================================
Dt:4th Jan,2013 Nitin Rajpurohit

update activities set due_date=due_date-19800;
update activities set created_date=created_date-19800;
update activity_log set activity_date=activity_date-19800;

#########################################################
Dt:4th Jan,2013 Rohit Rathod

CREATE TABLE [leave] (
[id] INTEGER  NOT NULL PRIMARY KEY AUTOINCREMENT,
[title] text  NOT NULL,
[start_date] INTEGER  NOT NULL,
[end_date] INTEGER  NOT NULL,
[no_of_days] INTEGER  NULL,
[description] text  NULL,
[is_sanctioned] INTEGER  NOT NULL
);
#########################################################
===============================================
Dt:5th Jan,2013 Nitin Rajpurohit

update projects set estimated_startdate=estimated_startdate-19800, estimated_enddate=estimated_enddate-19800,actual_startdate=actual_startdate-19800,actual_enddate=actual_enddate-19800;
update milestones set estimated_startdate=estimated_startdate-19800, estimated_enddate=estimated_enddate-19800,actual_startdate=actual_startdate-19800,actual_enddate=actual_enddate-19800;
update holiday set date=date-19800;
update projecthistory set created_date=created_date-19800;

===============================================
Dt:7th Jan,2013 Nitin Panchal
ALTER TABLE "login" ADD COLUMN "loggedinby" INTEGER;
ALTER TABLE "login" ADD COLUMN "loggedoutby" INTEGER;
update login set loggedinby=user_id,loggedoutby=user_id;
===============================================
Dt:7th Jan,2013 Maulik Shah
DROP TABLE IF EXISTS "preferences";
CREATE TABLE preferences (id INTEGER PRIMARY KEY  AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, cc CLOB NOT NULL, tomail CLOB NOT NULL);
===============================================
#########################################################
Dt:7th Jan,2013 Rohit Rathod

drop table sentmails;
CREATE TABLE [sentmails] (
[id] INTEGER  NOT NULL PRIMARY KEY AUTOINCREMENT,
[parent_id] INTEGER NULL,
[table_name] TEXT NULL,
[type_id] INTEGER NOT NULL,
[mail_to] TEXT NOT NULL,
[cc] TEXT NULL,
[bcc] TEXT NULL,
[mail_from] TEXT NOT NULL,
[content] TEXT NOT NULL,
[created_date_time] INTEGER NOT NULL,
[sent_date_time] INTEGER NOT NULL
);

drop table leave;
CREATE TABLE [leave] (
[id] INTEGER  PRIMARY KEY AUTOINCREMENT NOT NULL,
[title] TEXT  NOT NULL,
[start_date] INTEGER  NOT NULL,
[end_date] INTEGER  NOT NULL,
[no_of_days] INTEGER  NULL,
[description] TEXT  NULL,
[is_sanctioned] INTEGER  NOT NULL,
[created_date] INTEGER  NOT NULL,
[created_time] INTEGER  NOT NULL
);
#########################################################

Date:8th Jan 2013 Maulik Shah
delete from assignee;
insert into assignee ( project_id,milestone_id,activity_id,user_id )select
project_id,milestone_id,id,assigned_to_id from activities;
===============================================
Date:8th jan 2013 Nitin Panchal
update user set fname=lower(fname);
update user set lname=lower(lname)
=====================================================
########################################################
Date:11th,Jan-2013 Rohit Rathod

drop table leave;
CREATE TABLE [leave] (
[id] INTEGER  PRIMARY KEY AUTOINCREMENT NOT NULL,
[title] TEXT  NOT NULL,
[start_date] INTEGER  NOT NULL,
[end_date] INTEGER  NOT NULL,
[no_of_days] INTEGER  NULL,
[description] TEXT  NULL,
[is_sanctioned] INTEGER  NOT NULL,
[created_date] INTEGER  NOT NULL,
[created_time] INTEGER  NOT NULL,
[user_id] INTEGER  NOT NULL
);
#########################################################
Dt:12 Jan,2013 Nitin Rajpurohit

update login set  created_date=created_date-19800
#########################################################
Dt:18 Jan,2013 Nitin Rajpurohit

ALTER TABLE "milestones" ADD COLUMN "isdefault" INTEGER
##########################################################
Dt:25 Jan,2013 Nitin rajpurohit
update login set  loggedoutby=user_id  where loggedoutby  is null
update projects set name=lower(name);

#########################################################
Date:2nd,Feb-2013 Rohit Rathod
CREATE TABLE preferences_temp AS SELECT * FROM preferences;
DROP TABLE preferences;
CREATE TABLE preferences(id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT, user_id INTEGER NOT NULL, cc CLOB, tomail CLOB, leave_contact CLOB);
INSERT INTO preferences (user_id, cc, tomail) SELECT user_id, cc, tomail FROM preferences_temp;
DROP TABLE preferences_temp;
#########################################################
Date:1st,mar-2013 Nitin Rajpurohit

DROP TABLE IF EXISTS "company";
CREATE TABLE "company" ("id" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL , "name" VARCHAR NOT NULL );
COMMIT;

DROP TABLE IF EXISTS "contact";
CREATE TABLE "contact" ("id" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL , "paddress" VARCHAR, "raddress" VARCHAR, "altcontactnumber" TEXT, "user_id" INTEGER);
COMMIT;

ALTER TABLE "user" ADD COLUMN "company_id" INTEGER
	ALTER TABLE "user" ADD COLUMN "joiningdate" VARCHAR

