<?php
/**
 * @version      $Id: configuration.raw.php 64 2013-04-23 11:14:15Z freebandtech $
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
class KajooControllerConfiguration extends JControllerForm
{

    function __construct() {
        $this->view_list = 'configuration';
        parent::__construct();
    }
   function setOrderPositions()
   {

	   $colArray = JRequest::getVar('data');
	   
	
	   $colArray_json = json_encode($colArray);
	   		
	   		$db = JFactory::getDbo();
	   		$data =new stdClass();
			$data->id = 1;
			$data->value = $colArray_json;
			$db->updateObject( '#__kajoo_config', $data, 'id');

	  
   }
   public function deleteCat()
   {
	   $catId = JRequest::getVar('id', 0, 'GET', 'INT');
	   $partnerid = JRequest::getVar('partnerid', 0, 'GET', 'INT');

	    
	   // Connect to Kaltura API
		$PartnerInfo = KajooHelper::getPartnerInfo($partnerid);
		$kClient = KajooHelper::getKalturaClient($PartnerInfo->partnerid, $PartnerInfo->administratorsecret, true,$PartnerInfo->url);
		
		try
		{
			$catadd = $kClient->category->delete($catId);
		}
		catch(Exception $ex)
		{
			echo KajooHelper::getKalturaError(JText::_('Cannot delete category'));
		}
		$redirect = JRoute::_('index.php?option=com_kajoo&view=configuration',false);
		$msg = JText::_('COM_KAJOO_POSFRONTEND_CATDELETED');
		$this->setRedirect( $redirect, $msg );

   }
   public function resetPositions()
   {
	   		$db = JFactory::getDbo();
	   		$data =new stdClass();
			$data->id = 1;
			$data->value = '["empty","empty",["searchbox","categories","partners","wishlist","filters"]]';
			$db->updateObject( '#__kajoo_config', $data, 'id');
			$redirect = JRoute::_('index.php?option=com_kajoo&view=configuration',false);
			$this->setRedirect( $redirect);
   }
   public function addCategory()
   {

	    $partnerid = JRequest::getVar('partnerid', 0, 'POST', 'INT');
	    $parent = JRequest::getVar('parent', 0, 'POST', 'INT');
	    $name = JRequest::getVar('name', 0, 'POST', 'STRING');
	    
	    
	    $KalturaCategory = new KalturaCategory();
	    $KalturaCategory->name = $name;
	    $KalturaCategory->partnerid = $partnerid;
	    $KalturaCategory->parentId = $parent;
	    
	   // Connect to Kaltura API
		$PartnerInfo = KajooHelper::getPartnerInfo($partnerid);
		$kClient = KajooHelper::getKalturaClient($PartnerInfo->partnerid, $PartnerInfo->administratorsecret, true,$PartnerInfo->url);
		
		try
		{
			$catadd = $kClient->category->add($KalturaCategory);
		}
		catch(Exception $ex)
		{
			echo KajooHelper::getKalturaError(JText::_('Cannot add category'));
		}

   }
   public function validate()
   {
	
	   $colArray = JRequest::getVar('data');
	   
	   $colArray_json = json_encode($colArray);
	   $db = JFactory::getDbo();
		$data =new stdClass();
		$data->id = 2;
		$data->value = $colArray_json;
		$db->updateObject( '#__kajoo_config', $data, 'id');
   }
   

}