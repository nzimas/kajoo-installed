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
 * @package dompdf
 * @link    http://www.dompdf.com/
 * @author  Fabien Ménager <fabien.menager@gmail.com>
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * @version $Id: javascript_embedder.cls.php 448 2011-11-13 13:00:03Z fabien.menager $
 */

/**
 * Embeds Javascript into the PDF document
 *
 * @access private
 * @package dompdf
 */
class Javascript_Embedder {
  
  /**
   * @var DOMPDF
   */
  protected $_dompdf;

  function __construct(DOMPDF $dompdf) {
    $this->_dompdf = $dompdf;
  }

  function insert($code) {
    $this->_dompdf->get_canvas()->javascript($code);
  }

  function render($frame) {
    if ( !DOMPDF_ENABLE_JAVASCRIPT )
      return;
      
    $this->insert($frame->get_node()->nodeValue);
  }
}
