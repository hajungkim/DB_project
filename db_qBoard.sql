CREATE TABLE IF NOT EXISTS `qboard` (
  `qb_idx` int(11) NOT NULL auto_increment,
  `qb_table` varchar(20) NOT NULL default '',
  `qb_group_num` int(11) NOT NULL default '0',
  `qb_ord` int(11) NOT NULL default '0',
  `qb_depth` int(11) NOT NULL default '0',
  `qm_user` varchar(50) NOT NULL default '',
  `qb_subject` varchar(255) NOT NULL default '',
  `qb_content` text NOT NULL default '',
  `qb_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `qb_hits` int(11) NOT NULL default '0',
  `qb_ip` varchar(25) NOT NULL default '',
  PRIMARY KEY  (`qb_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `qboard_comment` (
  `qc_idx` int(11) NOT NULL auto_increment,
  `qc_parent` int(11) default NULL,
  `qm_user` varchar(50) NOT NULL default '',
  `qc_comment` varchar(255) NOT NULL default '',
  `qc_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `qc_ip` varchar(25) NOT NULL default '',
  PRIMARY KEY  (`qc_idx`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `qboard_member` (
  `qm_user` varchar(50) NOT NULL default '',
  `qm_pwd` varchar(255) default NULL,
  `qm_email` varchar(255) default NULL,
  `qm_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `qm_updated_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `qm_ip` varchar(25) default NULL,
  `qm_profile` varchar(255) default NULL,
  `qm_level` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`qm_user`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


INSERT INTO `qboard_member` (`qm_user`, `qm_pwd`, `qm_email`, `qm_ip`, `qm_level`) VALUES
('admin', '*A4B6157319038724E3560894F7F932C8886EBFCF', 'admin@admin.com', '127.0.0.1', 100);