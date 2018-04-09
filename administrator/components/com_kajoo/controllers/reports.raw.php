<?php
/**
 * @version      $Id: reports.raw.php 64 2013-04-23 11:14:15Z freebandtech $
 * @package      Kajoo
 * @copyright    Copyright (C) FreebandTech. All rights reserved.
 * @license      GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Content controller class.
 */
class KajooControllerReports extends JControllerForm
{

    function __construct() {
        $this->view_list = 'reports';
        parent::__construct();
    }
    function getInfo()
    {
    	$showThumb = JRequest::getVar('showThumb', 1, 'POST', 'INT');
    	$showTitle = JRequest::getVar('showTitle', 1, 'POST', 'INT');
    	$showId = JRequest::getVar('showId', 1, 'POST', 'INT');
    	$showPartner = JRequest::getVar('showPartner', 1, 'POST', 'INT');
    	$fieldFilters = JRequest::getVar('fieldFilters', 1, 'POST', 'ARRAY');
    	$arrayTitles = JRequest::getVar('arrayTitles', 1, 'POST', 'ARRAY');
    	
    	
    	$view =$this->getView('reportspdf', 'html');
    	$view->display();

    }

  

}