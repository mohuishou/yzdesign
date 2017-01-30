DROP TABLE IF EXISTS pw_app_yunqian_qduser;
CREATE TABLE  `pw_app_yunqian_qduser` (
  `uid` int(10) NOT NULL COMMENT '用户ID',
  `author` varchar(15) NOT NULL COMMENT '用户名',
  `times` int(10) NOT NULL COMMENT '最后一次签到时间',
  `money` int(10) NOT NULL COMMENT '总奖励',
  `day` int(10) NOT NULL COMMENT '总签到天数',
  `content` text NOT NULL COMMENT '签到内容',
  `dj` int(10) NOT NULL DEFAULT '1' COMMENT '签到等级',
  `dtop` int(10) NOT NULL DEFAULT '0' COMMENT '今日排名',
  `mday` int(10) NOT NULL DEFAULT '0' COMMENT '本月统计',
  `mtime` int(10) NOT NULL DEFAULT '0' COMMENT '本月时间',
  `qdxq` int(10) NOT NULL DEFAULT '0' COMMENT '签到心情',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT '签到会员记录表';

DROP TABLE IF EXISTS pw_app_yunqian_level;
CREATE TABLE  `pw_app_yunqian_level` (
  `hits` int(10)  NOT NULL COMMENT '需要次数',
  `dj` int(10) NOT NULL  COMMENT '等级数',
  `title` varchar(100) NOT NULL  COMMENT '等级名称',
  PRIMARY KEY (`dj`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT '签到等级表';

DROP TABLE IF EXISTS pw_app_yunqian;
CREATE TABLE  `pw_app_yunqian` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '动态ID',
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `author` varchar(15) NOT NULL DEFAULT '' COMMENT '用户名',
  `times` int(10) NOT NULL DEFAULT '0' COMMENT '签到时间',
  `qdxq` int(10) NOT NULL DEFAULT '0' COMMENT '签到心情',
  `money` int(10) NOT NULL DEFAULT '0' COMMENT '签到奖励',
  `ip` varchar(100) NOT NULL DEFAULT '' COMMENT '签到ip',
  `content` text NOT NULL COMMENT '签到内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT '签到记录表';

DROP TABLE IF EXISTS pw_app_yunqian_count;
CREATE TABLE  `pw_app_yunqian_count` (
  `id` smallint(3) unsigned NOT NULL auto_increment COMMENT '主键ID',
  `newmember` varchar(15) NOT NULL default '' COMMENT '今日会员',
  `total` mediumint(8) unsigned NOT NULL default '0' COMMENT '签到总数',
  `higholnum` mediumint(8) unsigned NOT NULL default '0' COMMENT '历史最高',
  `higholtime` int(10) unsigned NOT NULL default '0' COMMENT '历史最高时间',
  `zqian` mediumint(8) unsigned NOT NULL default '0' COMMENT '昨日签到',
  `dqian` mediumint(8) unsigned NOT NULL default '0' COMMENT '今日签到',
  `mqian` int(10) NOT NULL default '0' COMMENT '月统计',
  `dtime` int(10) NOT NULL default '0' COMMENT '今日时间',
  `newuid` int(10) NOT NULL default '0' COMMENT '今日之星UID',
  `mtime` int(10) NOT NULL default '0' COMMENT '当月时间',
  `zmoney` int(10) NOT NULL default '0' COMMENT '总奖励',
  `dtid` int(10) NOT NULL default '0' COMMENT '今日贴子',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT '统计表';

INSERT INTO `pw_app_yunqian_count` (`id`, `newmember`, `total`, `higholnum`, `higholtime`, `zqian`, `dqian`, `mqian`, `dtime`, `newuid`, `mtime`, `zmoney`, `dtid`) VALUES 
('1', '', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');