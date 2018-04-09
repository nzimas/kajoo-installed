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
 * @version $Id$
 */

/**
 * Font table name record.
 * 
 * @package php-font-lib
 */
class Font_Table_name_Record extends Font_Binary_Stream {
  public $platformID;
  public $platformSpecificID;
  public $languageID;
  public $nameID;
  public $length;
  public $offset;
  public $string;
  
  public static $format = array(
    "platformID" => self::uint16,
    "platformSpecificID" => self::uint16,
    "languageID" => self::uint16,
    "nameID"     => self::uint16,
    "length"     => self::uint16,
    "offset"     => self::uint16,
  );
  
  public function map($data) {
    foreach($data as $key => $value) {
      $this->$key = $value;
    }
  }
  
  public function getUTF8() {
    return $this->string;
  }
  
  public function getUTF16() {
    return Font::UTF8ToUTF16($this->string);
  }
  
  function __toString(){
    return $this->string;
  }
}