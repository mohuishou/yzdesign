--安装或更新时需要注册的sql写在这里--

--
-- 表的结构 `pw_medz_ext20131221_read`
--

CREATE TABLE IF NOT EXISTS `pw_medz_ext20131221_read` (
  `tid` int(10) unsigned NOT NULL COMMENT '帖子Tid',
  `json` text COMMENT '帖子Pid合集',
  UNIQUE KEY `tid` (`tid`)
) ENGINE=MyISAM COMMENT='帖内聚合表';

--
-- 表的结构 `pw_medz_ext20131221_juhe`
--

CREATE TABLE IF NOT EXISTS `pw_medz_ext20131221_juhe` (
  `tid` int(10) unsigned NOT NULL COMMENT 'tid',
  `type` text COMMENT '类型',
  `tids` text COMMENT 'tid合集',
  UNIQUE KEY `tid` (`tid`)
) ENGINE=MyISAM COMMENT='帖子聚合表';