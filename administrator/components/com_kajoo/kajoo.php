<?php
/**
 * @version      $Id: kajoo.php 66 2013-04-23 11:22:05Z freebandtech $
 * @package      Kajoo
 * @copyright    Copyright (C) FreebandTech. All rights reserved.
 * @license      GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */


// no direct access
defined('_JEXEC') or die;
if(!defined('DS')){
define('DS',DIRECTORY_SEPARATOR);
}
 
// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_kajoo')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

JLoader::register('KajooHelper', __DIR__ . '/helpers/kajoo.php');
KajooHelper::checkPartnerConfigured();

$document = JFactory::getDocument();
//http://addyosmani.github.com/jquery-ui-bootstrap/
$document->addStyleSheet(JURI::base() . 'components/com_kajoo/assets/js/jqueryui/css/custom-theme/jquery-ui-1.8.16.custom.css');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/js/jquery-1.8.2.js');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/js/jqueryui/js/jquery-ui.min.js');

$document->addScript(JURI::base() . 'components/com_kajoo/assets/js/jqueryui/third-party/jQuery-UI-Date-Range-Picker/js/date.js');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/js/jqueryui/third-party/jQuery-UI-Date-Range-Picker/js/daterangepicker.jQuery.compressed.js');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/js/jqueryui/third-party/wijmo/jquery.mousewheel.min.js');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/js/jqueryui/third-party/wijmo/jquery.bgiframe-2.1.3-pre.js');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/js/jqueryui/third-party/wijmo/jquery.wijmo-open.1.5.0.min.js');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/js/jqueryui/third-party/jQuery-UI-FileInput/js/enhance.min.js');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/js/jqueryui/third-party/jQuery-UI-FileInput/js/fileinput.jquery.js');

$document->addScript(JURI::base() . 'components/com_kajoo/assets/bootstrap/js/bootstrap-tab.js');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/bootstrap/js/bootstrap-modal.js');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/bootstrap/js/bootstrap-tooltip.js');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/bootstrap/js/bootstrap-transition.js');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/bootstrap/js/bootstrap-collapse.js');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/bootstrap/js/bootstrap-popover.js');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/bootstrap/js/bootstrap-alert.js');




// Include dependancies
jimport('joomla.application.component.controller');

$controller	= JControllerLegacy::getInstance('Kajoo');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
