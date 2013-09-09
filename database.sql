# Why you should use `utf8mb4` instead of `utf8`: http://mathiasbynens.be/notes/mysql-utf8mb4

CREATE TABLE `link` (
  `id` int AUTO_INCREMENT NOT NULL,
	`slug` varchar(14) collate utf8mb4_unicode_ci NOT NULL,
  `namespace` varchar(14) collate utf8mb4_unicode_ci,
	`url` varchar(620) collate utf8mb4_unicode_ci NOT NULL,
	`created_at` datetime NOT NULL,
  `expires_at` datetime,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Links for URL shortener';

CREATE TABLE `hit` (
  `id` int AUTO_INCREMENT NOT NULL,
  `link_id` int NOT NULL,
  `visited_at` datetime NOT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `ua` varchar(255) DEFAULT NULL,
  `referer` varchar(620) collate utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Links for URL shortener';

ALTER TABLE `hit` ADD CONSTRAINT fk_link_id FOREIGN KEY (link_id) REFERENCES link (id);