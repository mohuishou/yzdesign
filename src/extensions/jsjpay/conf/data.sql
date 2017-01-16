DROP TABLE IF EXISTS `pw_app_jsjpay`;

CREATE TABLE IF NOT EXISTS `pw_app_jsjpay` (

	`id` int(10) NOT NULL AUTO_INCREMENT,

  	`uid` int(10) NOT NULL,
	`total` int(10) NOT NULL,
	`addnum` char(32) NOT NULL,
	`op` int(2) NOT NULL,
	`time` int(10) NOT NULL,  
	PRIMARY KEY (`id`)
) 
ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单表';