--
--  `#__kajoo_clients` table structure
--

CREATE TABLE IF NOT EXISTS `#__kajoo_clients` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `company` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `observations` text NOT NULL,
  `added` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- `#__kajoo_config` table structure
--

CREATE TABLE IF NOT EXISTS `#__kajoo_config` (
  `id` int(150) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `#__kajoo_config` (`id`, `name`, `value`) VALUES
(1, 'frontendpositions', '["empty","empty",["filters","searchbox","categories","partners","wishlist"]]'),
(2, 'rebel', '["empty","empty",["0","","","-1",""]');


-- --------------------------------------------------------

--
-- `#__kajoo_content` table structure
--

CREATE TABLE IF NOT EXISTS `#__kajoo_content` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `thumburl` text NOT NULL,
  `searchText` text NOT NULL,
  `categories` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `checked_out` int(11) NOT NULL,
  `added` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `entry_id` varchar(255) NOT NULL,
  `partner_id` int(11) NOT NULL,
  `duration` int(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- `#__kajoo_content_client_rel` table structure
--

CREATE TABLE IF NOT EXISTS `#__kajoo_content_client_rel` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `content_id` int(255) NOT NULL,
  `client_id` int(255) NOT NULL,
  `state` int(11) NOT NULL DEFAULT '0',
  `comments` text NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- `#__kajoo_fields` tabled structure
--

CREATE TABLE IF NOT EXISTS `#__kajoo_fields` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ordering` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `filtrable` varchar(255) NOT NULL,
  `sitevisible` varchar(255) NOT NULL,
  `adminvisible` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- `#__kajoo_field_values` table structure
--

CREATE TABLE IF NOT EXISTS `#__kajoo_field_values` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ordering` int(11) NOT NULL,
  `fieldid` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- `#__kajoo_field_values_entry` table structure
--

CREATE TABLE IF NOT EXISTS `#__kajoo_field_values_entry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_id` int(255) NOT NULL,
  `field_id` int(11) NOT NULL,
  `value` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- `#__kajoo_notes` table structure
--

CREATE TABLE IF NOT EXISTS `#__kajoo_notes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `added` datetime NOT NULL,
  `ordering` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- `#__kajoo_notes_entries` table structure
--

CREATE TABLE IF NOT EXISTS `#__kajoo_notes_entries` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `note_id` int(255) NOT NULL,
  `entry_id` int(255) NOT NULL,
  `publisher_id` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- tabla `#__kajoo_partners` table structure
--

CREATE TABLE IF NOT EXISTS `#__kajoo_partners` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `added` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `ordering` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `name` varchar(255) NOT NULL,
  `partnerid` varchar(255) NOT NULL,
  `administratorsecret` varchar(255) NOT NULL,
  `usersecret` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `defaultPlayer` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
