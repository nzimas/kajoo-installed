<?php
/**
 * @version     0.1
 * @package     com_kajoo
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Miguel Puig <miguel@freebandtech.com> - http://freebandtech.com
 */


// No direct access
defined('_JEXEC') or die;
/**
 * @package php-font-lib
 * @link    http://php-font-lib.googlecode.com/
 * @author  Fabien Ménager <fabien.menager@gmail.com>
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * @version $Id: font_truetype_table_directory_entry.cls.php 34 2011-10-23 13:53:25Z fabien.menager $
 */

require_once dirname(__FILE__)."/font_table_directory_entry.cls.php";

/**
 * TrueType table directory entry.
 * 
 * @package php-font-lib
 */
class Font_TrueType_Table_Directory_Entry extends Font_Table_Directory_Entry {
  function __construct(Font_TrueType $font) {
    parent::__construct($font);
    $this->checksum = $this->readUInt32();
    $this->offset = $this->readUInt32();
    $this->length = $this->readUInt32();
    $this->entryLength += 12;
  }
}

