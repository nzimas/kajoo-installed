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
 * @version $Id: font_table.cls.php 37 2011-11-07 07:38:44Z fabien.menager $
 */

/**
 * Generic font table.
 * 
 * @package php-font-lib
 */
class Font_Table extends Font_Binary_Stream {
  /**
   * @var Font_Table_Directory_Entry
   */
  protected $entry;
  protected $def = array();
  
  public $data;
  
  final public function __construct(Font_Table_Directory_Entry $entry) {
    $this->entry = $entry;
    $entry->setTable($this);
  }
  
  /**
   * @return Font_TrueType
   */
  public function getFont(){
    return $this->entry->getFont();
  }
  
  protected function _encode(){
    if (empty($this->data)) {
      Font::d("  >> Table is empty");
      return 0;
    }
    
    return $this->getFont()->pack($this->def, $this->data);
  }
  
  protected function _parse(){
    $this->data = $this->getFont()->unpack($this->def);
  }
  
  protected function _parseRaw(){
    $this->data = $this->getFont()->read($this->entry->length);
  }
  
  protected function _encodeRaw(){
    return $this->getFont()->write($this->data, $this->entry->length);
  }
  
  final public function encode(){
    $this->entry->startWrite();
    
    if (false && empty($this->def)) {
      $length = $this->_encodeRaw();
    }
    else {
      $length = $this->_encode();
    }
    
    $this->entry->endWrite();
    
    return $length;
  }
  
  final public function parse(){
    $this->entry->startRead();
    
    if (false && empty($this->def)) {
      $this->_parseRaw();
    }
    else {
      $this->_parse();
    }
    
    $this->entry->endRead();
  }
}