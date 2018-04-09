<?php
/**
 * @version      $Id: content.raw.php 64 2013-04-23 11:14:15Z freebandtech $
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
    function getEmbedPlayer()
    {
    	$partnerId = JRequest::getVar('partnerid', 0, 'GET', 'INT');
    	$entryid = JRequest::getVar('entryid', '', 'GET', 'STRING');
    	$uiConf = JRequest::getVar('uiConf', 0, 'GET', 'INT');
	    $video = KajooHelper::getUrlEmbed($partnerId,$entryid,$uiConf);
	    echo $video;
    }
    function setDefaultPlayer()
    {
	    $partnerId = JRequest::getVar('partnerid', 0, 'GET', 'INT');
	    $uiConf = JRequest::getVar('uiConf', 0, 'GET', 'INT');
	    
	    $db =JFactory::getDBO();

	    $data =new stdClass();
	    $data->id = $partnerId;
		$data->defaultPlayer = $uiConf;
		$db->updateObject( '#__kajoo_partners', $data,'id');
		
		echo JText::_('PLAYERCHANGED');
    }
    
    function downloadFlavor()
    {
	    $flavorid = JRequest::getVar('flavorid', '', 'GET', 'STRING');
	    $partnerid = JRequest::getVar('partnerid', 0, 'GET', 'INT');
	   // Connect to Kaltura API
		$PartnerInfo = KajooHelper::getPartnerInfo($partnerid);
		$kClient = KajooHelper::getKalturaClient($PartnerInfo->partnerid, $PartnerInfo->administratorsecret, true,$PartnerInfo->url);
		
		try
		{
			$flavordownload = $kClient->flavorAsset->getdownloadurl($flavorid);
		}
		catch(Exception $ex)
		{
			echo KajooHelper::getKalturaError(JText::_('Cannot get flavor'));
		}
		if($flavordownload!=''):
			$this->setRedirect($flavordownload);
		endif;
    }
    function convert()
    //convert flavor asset
    {
	    $flavorParamId = JRequest::getVar('flavorParamId', 0, 'GET', 'INT');
	    $partnerid = JRequest::getVar('partnerid', 0, 'GET', 'INT');
	    $entryid = JRequest::getVar('entryid', '', 'GET', 'STRING');
	   // Connect to Kaltura API
		$PartnerInfo = KajooHelper::getPartnerInfo($partnerid);
		$kClient = KajooHelper::getKalturaClient($PartnerInfo->partnerid, $PartnerInfo->administratorsecret, true,$PartnerInfo->url);
		
		try
		{
			$flavorconvert = $kClient->flavorAsset->convert($entryid,$flavorParamId);
		}
		catch(Exception $ex)
		{
			echo KajooHelper::getKalturaError(JText::_('Cannot convert flavor'));
		}
		return true;
    }
    function delete()
    //delete flavor asset
    {
	    $flavorAssetId = JRequest::getVar('flavorAssetId', '', 'GET', 'STRING');
	    $partnerid = JRequest::getVar('partnerid', 0, 'GET', 'INT');

	   // Connect to Kaltura API
		$PartnerInfo = KajooHelper::getPartnerInfo($partnerid);
		$kClient = KajooHelper::getKalturaClient($PartnerInfo->partnerid, $PartnerInfo->administratorsecret, true,$PartnerInfo->url);
		
		try
		{
			$flavordelete = $kClient->flavorAsset->delete($flavorAssetId);
		}
		catch(Exception $ex)
		{
			echo KajooHelper::getKalturaError(JText::_('Cannot delete flavor'));
		}	    
		return true;
    }
    
    function reconvert()
    //reconvert flavor asset
    {
	    $flavorAssetId = JRequest::getVar('flavorAssetId', '', 'GET', 'STRING');
	    $partnerid = JRequest::getVar('partnerid', 0, 'GET', 'INT');

	   // Connect to Kaltura API
		$PartnerInfo = KajooHelper::getPartnerInfo($partnerid);
		$kClient = KajooHelper::getKalturaClient($PartnerInfo->partnerid, $PartnerInfo->administratorsecret, true,$PartnerInfo->url);
		
		try
		{
			$flavordelete = $kClient->flavorAsset->reconvert($flavorAssetId);
		}
		catch(Exception $ex)
		{
			echo KajooHelper::getKalturaError(JText::_('Cannot reconvert flavor'));
		}
		return true;	    
    }
    function syncContent()
    {
    	$partnerId = JRequest::getVar('partnerId', 0, 'GET', 'INT');
	    KajooHelper::syncPartner($partnerId);
	    echo "Sync succesfull";
    }
    function changeThumbnail()
    {

	    $partnerid = JRequest::getVar('partnerid', 0, 'GET', 'INT');
	    $serviceurl = JRequest::getVar('serviceurl', '', 'GET', 'STRING');
	    $entryid = JRequest::getVar('entryid', '', 'GET', 'STRING');
	    $videotime = JRequest::getVar('videotime', 0, 'GET', 'INT');
	    
	    // Connect to Kaltura API
		$PartnerInfo = KajooHelper::getPartnerInfo($partnerid);
		$kClient = KajooHelper::getKalturaClient($PartnerInfo->partnerid, $PartnerInfo->administratorsecret, true,$PartnerInfo->url);
		$urlThumb = $serviceurl."/p/".$PartnerInfo->partnerid."/thumbnail/entry_id/".$entryid."/width/800/height/600/type/1/vid_sec/".$videotime;		
		//Add to collection
		try
		{
			$colellectionthumb = $kClient->thumbAsset->addfromurl($entryid,$urlThumb);
		}
		catch(Exception $ex)
		{
			echo KajooHelper::getKalturaError(JText::_('Cannot add Thumbnail'));
		}
		$colellectionthumb->urlthumb = $urlThumb;
		
		echo json_encode($colellectionthumb);
		
    }
    function setDefaultthumb()
    {
	    $partnerid = JRequest::getVar('partnerid', 0, 'GET', 'INT');
	    $thumbAssetId = JRequest::getVar('thumbAssetId', '', 'GET', 'STRING');
	    $itemId = JRequest::getVar('itemId',0,'GET', 'INT');
	    $db =JFactory::getDBO();
	    
	    // Connect to Kaltura API
		$PartnerInfo = KajooHelper::getPartnerInfo($partnerid);
		$kClient = KajooHelper::getKalturaClient($PartnerInfo->partnerid, $PartnerInfo->administratorsecret, true,$PartnerInfo->url);
		
		try
		{
			$flavorupdatethumb = $kClient->thumbAsset->setasdefault($thumbAssetId);
		}
		catch(Exception $ex)
		{
			echo KajooHelper::getKalturaError(JText::_('Cannot set default thumbnail'));
		}
		
		try
		{
			$newthumb = $kClient->thumbAsset->serve($thumbAssetId);

		}
		catch(Exception $ex)
		{
			echo KajooHelper::getKalturaError(JText::_('Cannot serve thumbnail'));
		}
		try
		{
			
		    
		    $data =new stdClass();
		    $data->id = $itemId;
			$data->thumburl = $newthumb;
			$db->updateObject( '#__kajoo_content', $data,'id');
		}
		catch(Exception $ex)
		{
			echo KajooHelper::getKalturaError(JText::_('Cannot set the thumbnail in the DB'));
		}

		return $newthumb;
    }
    function deleteThumb()
    {
	    $partnerid = JRequest::getVar('partnerid', 0, 'GET', 'INT');
	    $thumbAssetId = JRequest::getVar('thumbAssetId', '', 'GET', 'STRING');
	    // Connect to Kaltura API
		$PartnerInfo = KajooHelper::getPartnerInfo($partnerid);
		$kClient = KajooHelper::getKalturaClient($PartnerInfo->partnerid, $PartnerInfo->administratorsecret, true,$PartnerInfo->url);
		
		try
		{
			$flavorupdatethumb = $kClient->thumbAsset->delete($thumbAssetId);
		}
		catch(Exception $ex)
		{
			echo KajooHelper::getKalturaError(JText::_('Cannot delete thumbnail'));
		}
	
    }

}