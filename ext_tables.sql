#
# Table structure for table 'tx_datecblog_domain_model_post'
#
CREATE TABLE tx_datecblog_domain_model_post (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	
	header varchar(255) DEFAULT '' NOT NULL,
	text text NOT NULL,
	images text DEFAULT NULL,
	teaser_text text NOT NULL,
	first_image_in_teaser tinyint(4) unsigned DEFAULT '0' NOT NULL,
	category int(11) DEFAULT '0' NOT NULL,	
	keywords int(11) DEFAULT '0' NOT NULL,
	comments_disable tinyint(4) unsigned DEFAULT '0' NOT NULL,
	comments_fileupload tinyint(4) unsigned DEFAULT '0' NOT NULL,		
	comments_usergroups tinytext NOT NULL,
	
	update_user int(11) unsigned DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
	
);
#
# Table structure for table 'tx_datecblog_domain_model_keyword'
#
CREATE TABLE tx_datecblog_domain_model_keyword (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	
	word varchar(255) DEFAULT '' NOT NULL,
	posts int(11) DEFAULT '0' NOT NULL,
	click_count int(11) DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
	
);
#
# Table structure for table 'tx_datecblog_domain_model_post_keyword_mm'
#
CREATE TABLE tx_datecblog_domain_model_post_keyword_mm (
	uid int(11) NOT NULL auto_increment,
	uid_local int(11) DEFAULT '0' NOT NULL,
	uid_foreign int(11) DEFAULT '0' NOT NULL,
	tablenames varchar(30) DEFAULT '' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,
	sorting_foreign int(11) DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign),
	PRIMARY KEY (uid),
);
#
# Table structure for table 'tx_datecblog_domain_model_category'
#
CREATE TABLE tx_datecblog_domain_model_category (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	
	name varchar(255) DEFAULT '' NOT NULL,
	parent int(11) DEFAULT '0' NOT NULL,	
	usergroups tinytext NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
	
);
#
# Table structure for table 'tx_datecblog_domain_model_comment'
#
CREATE TABLE tx_datecblog_domain_model_comment (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	
	post int(11) DEFAULT '0' NOT NULL,
	comment_creator int(11) DEFAULT '0' NOT NULL,
	text text NOT NULL,	
	files text NOT NULL,
	parent int(11) DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
	
);
#
# Table structure for table 'tx_datecblog_domain_model_commentcreator'
#
CREATE TABLE tx_datecblog_domain_model_commentcreator (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	
	fe_user int(11) DEFAULT '0' NOT NULL,
	username varchar(255) DEFAULT '' NOT NULL,
	email varchar(255) DEFAULT '' NOT NULL,	
	blocked tinyint(4) unsigned DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
	
);