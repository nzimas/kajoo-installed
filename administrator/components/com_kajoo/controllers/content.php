<?php
/**
 * @version      $Id: content.php 64 2013-04-23 11:14:15Z freebandtech $
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
class KajooControllerContent extends JControllerForm
{

    function __construct() {
        $this->view_list = 'contents';
        parent::__construct();
    }

    public function postSaveHook($model)
	{
        $item = $model->getItem();	

        
        $type = JRequest::getVar('jform[type]', 1, 'post', 'INT');
        $name = JRequest::getVar('name', '', 'post', 'STRING');
        $description = JRequest::getVar('description', '', 'post', 'STRING');
        $tags = JRequest::getVar('tags', '', 'post', 'STRING');
        $partnerid = JRequest::getVar('embedpartnerId', 1, 'post', 'INT');
        $categories = JRequest::getVar('categories');
        $currenttab = JRequest::getVar('currenttab', '', 'post', 'STRING');
        
        
		$session =JFactory::getSession();
		$session->set( 'currenttab', $currenttab );        

       $comma_separated_categories = implode(",", $categories);
       
       
       //Update Kaltura API object
		$PartnerInfo = KajooHelper::getPartnerInfo($partnerid);
		$kClient = KajooHelper::getKalturaClient($PartnerInfo->partnerid, $PartnerInfo->administratorsecret, true,$PartnerInfo->url);
		
		$k = new KalturaMediaEntry();          
		$k->name=$name;
		$k->description=$description;
		$k->tags=$tags;
		$k->categoriesIds=$comma_separated_categories;		
		
		try
		{
			$api_result = $kClient->media->update($item->entry_id,$k);
		}
		catch(Exception $ex)
		{
			echo JText::_('Cannot update media');
		}
		//Sync content
		KajooHelper::syncPartner($partnerid);
		
		//Update cutom fields
		$fieldsModel = JModelLegacy::getInstance('fields', 'KajooModel'); 
		$custom_fields = $fieldsModel->getAllFields();
		foreach($custom_fields as $field):
			$value = JRequest::getVar($field->alias, '', 'post', 'STRING');
			$model->updateField($item->id,$field->id,$value);
		endforeach;

	}
}