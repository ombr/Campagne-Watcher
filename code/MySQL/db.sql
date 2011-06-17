 CREATE TABLE IF NOT EXISTS `vote` (
  `id` int(11) NOT NULL auto_increment,
  `vote` tinyint(4) NOT NULL,
  `tel` varchar(255) NOT NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1202 ;
