--��װ�����ʱ��Ҫע���sqlд������--

--
-- ��Ľṹ `pw_medz_ext20131221_read`
--

CREATE TABLE IF NOT EXISTS `pw_medz_ext20131221_read` (
  `tid` int(10) unsigned NOT NULL COMMENT '����Tid',
  `json` text COMMENT '����Pid�ϼ�',
  UNIQUE KEY `tid` (`tid`)
) ENGINE=MyISAM COMMENT='���ھۺϱ�';

--
-- ��Ľṹ `pw_medz_ext20131221_juhe`
--

CREATE TABLE IF NOT EXISTS `pw_medz_ext20131221_juhe` (
  `tid` int(10) unsigned NOT NULL COMMENT 'tid',
  `type` text COMMENT '����',
  `tids` text COMMENT 'tid�ϼ�',
  UNIQUE KEY `tid` (`tid`)
) ENGINE=MyISAM COMMENT='���Ӿۺϱ�';