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
 * @version $Id: font_table_hmtx.cls.php 40 2012-01-22 21:48:41Z fabien.menager $
 */

/**
 * `hmtx` font table.
 * 
 * @package php-font-lib
 */
class Font_Table_hmtx extends Font_Table {
  protected function _parse(){
    $font = $this->getFont();
    $offset = $font->pos();
    
    $numOfLongHorMetrics = $font->getData("hhea", "numOfLongHorMetrics");
    $numGlyphs = $font->getData("maxp", "numGlyphs");
    
    $font->seek($offset);
    
    $data = array();
    for($gid = 0; $gid < $numOfLongHorMetrics; $gid++) {
      $advanceWidth = $font->readUInt16();
      $leftSideBearing = $font->readUInt16();
      $data[$gid] = array($advanceWidth, $leftSideBearing);
    }
    
    if($numOfLongHorMetrics < $numGlyphs){
      $lastWidth = end($data);
      $data = array_pad($data, $numGlyphs, $lastWidth);
    }
    
    $this->data = $data;
  }
  
  protected function _encode() {
    $font = $this->getFont();
    $subset = $font->getSubset();
    $data = $this->data;
    
    $length = 0;
    
    foreach($subset as $gid) {
      $length += $font->writeUInt16($data[$gid][0]);
      $length += $font->writeUInt16($data[$gid][1]);
    }
    
    return $length;
  }
}