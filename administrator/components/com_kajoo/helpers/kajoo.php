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

// No direct access
defined('_JEXEC') or die;
require_once JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'kaltura'.DS.'KalturaClient.php';
/**
 * Kajoo helper.
 */
class KajooHelper
{
	/**
	 * Configure the Linkbar.
	 */

	
	public static function addSubmenu($vName = '')
	{
		JHtmlSidebar::addEntry(
			'<i class="icon-user"></i> '.JText::_('COM_KAJOO_TITLE_PARTNERS').'</span>',
			'index.php?option=com_kajoo&view=partners',
			$vName == 'partners'
		);
		JHtmlSidebar::addEntry(
			'<i class="icon-file"></i> '.JText::_('COM_KAJOO_TITLE_NOTES').'</span>',
			'index.php?option=com_kajoo&view=notes',
			$vName == 'notes'
		);
		JHtmlSidebar::addEntry(
			'<i class="icon-eye-open"></i> '.JText::_('COM_KAJOO_TITLE_CONTENTS').'</span>',
			'index.php?option=com_kajoo&view=contents',
			$vName == 'contents'
		);
		JHtmlSidebar::addEntry(
			'<i class="icon-list"></i> '.JText::_('COM_KAJOO_TITLE_FIELDS').'</span>',
			'index.php?option=com_kajoo&view=fields',
			$vName == 'fields'
		);
		JHtmlSidebar::addEntry(
			'<i class="icon-wrench"></i> '.JText::_('COM_KAJOO_TITLE_CONFIG').'</span>',
			'index.php?option=com_kajoo&view=configuration',
			$vName == 'configuration'
		);
		JHtmlSidebar::addEntry(
			'<i class="icon-upload"></i> '.JText::_('COM_KAJOO_TITLE_UPLOAD').'</span>',
			'index.php?option=com_kajoo&view=upload',
			$vName == 'upload'
		);
		//JHtmlSidebar::addEntry(
		//	'<i class="icon-arrow-down"></i> '.JText::_('COM_KAJOO_TITLE_REPORTS').'</span>',
		//	'index.php?option=com_kajoo&view=reports',
		//	$vName == 'reports'
		//);
		
		//$valid = self::isValid();
		//if($valid[0]):
		//	$icon = 'icon-ok';
		//else:
		//	$icon = 'icon-ban-circle';
		//endif;
		
		//JHtmlSidebar::addEntry(
		//	'<i class="'.$icon.'"></i> '.JText::_('COM_KAJOO_TITLE_VALIDATE').'</span>',
		//	'index.php?option=com_kajoo&view=validation',
		//	$vName == 'validation'
		//);

	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$assetName = 'com_kajoo';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete', 'kajoo.uploadtopartner', 'kajoo.managecategories','kajoo.editpartner','kajoo.createpartner','kajoo.deletepartner','kajoo.managepartner'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
	
	public static function getKalturaClient($partnerId, $adminSecret, $isAdmin, $url='')
	{
		$kConfig = new KalturaConfiguration($partnerId);
		if($url==''):
			$kConfig->serviceUrl = 'http://www.kaltura.com';
		else:
			$kConfig->serviceUrl = $url;
		endif;
		$client = new KalturaClient($kConfig);
		
		$userId = "admin";
		$sessionType = ($isAdmin)? KalturaSessionType::ADMIN : KalturaSessionType::USER; 
		try
		{
			$ks = $client->generateSession($adminSecret, $userId, $sessionType, $partnerId);
			$client->setKs($ks);
		}
		catch(Exception $ex)
		{
			die("Could not start session - check configuration in KajooHelper class");
		}
		
		return $client;
	}
	public static function getKalturaError($error)
	{
		return '<div class="alert alert-error">'.$error.'</div>';
	}
	public static function getPartnerInfo($partnerId)
	{
		try
		{
			$db = JFactory::getDBO();
			$query  = 'SELECT * FROM `#__kajoo_partners`';    	
	    	$query .= ' WHERE id = '.(int)$partnerId;
			$db->setQuery($query);
			$partnerInfo = $db->loadObject();
		}
		catch(Exception $ex)
		{
			die("Check the partner ID");
		}
		
		return $partnerInfo;
		
		
	}
	public static function checkPartnerConfigured()
	{
			$db = JFactory::getDBO();
			$app = JFactory::getApplication();
			
			$query  = 'SELECT count(*) FROM `#__kajoo_partners`';
			$db->setQuery($query);
			$partnerResult = $db->loadResult();

			if($partnerResult==0):
				$msg = JText::_('COM_KAJOO_PARTNER_CREATEONE');
				$view = JRequest::getVar('view');
				
				if(($view=='notes') || ($view=='contents') || ($view=='fields') || ($view=='configuration') || ($view=='upload')):
					$app->redirect(JRoute::_('index.php?option=com_kajoo&view=partners',false), $msg);
				endif;
			endif;

	}
	
	public static function KalturaNumRecords($partnerId)
	{
	
		//get default partner
		$db = JFactory::getDBO();
		$query  = 'SELECT min(id) FROM `#__kajoo_partners` WHERE state = 1';
		$db->setQuery($query);
		$defaultPartnerId = $db->loadResult();
		if($partnerId==null):
			$partnerId = $defaultPartnerId;
		endif;
		
			
	
		$PartnerInfo = self::getPartnerInfo($partnerId);
		$kClient = self::getKalturaClient($PartnerInfo->partnerid, $PartnerInfo->administratorsecret, true,$PartnerInfo->url);

		//Type = video
		$filter = new KalturaMediaEntryFilter();
		$filter->mediaTypeEqual =1;
		$numMedia = $kClient->media->count($filter);
		
		return $numMedia;
	}
	
	public static function DatabaseNumRecords($partnerId)
	{
		//get default partner
		$db = JFactory::getDBO();
		$query  = 'SELECT min(id) FROM `#__kajoo_partners` WHERE state = 1';
		$db->setQuery($query);
		$defaultPartnerId = $db->loadResult();
		if($partnerId==null):
			$partnerId = $defaultPartnerId;
		endif;
		
			
	
		$query  = 'SELECT count(*) FROM `#__kajoo_content` WHERE partner_id = '.(int)$partnerId;
		$db->setQuery($query);
		$numMedia = $db->loadResult();
		
		return $numMedia;	
	}
	
	private static function getNumpages($total)
	{
		$numPages = ceil(($total/500));
		return $numPages;
	}
	

	
	public static function syncPartner($partnerId)
	// Syncs the database with the new kaltura items providing a PartnerId
	{
			// Connect to Kaltura API
			$PartnerInfo = self::getPartnerInfo($partnerId);
			$kClient = self::getKalturaClient($PartnerInfo->partnerid, $PartnerInfo->administratorsecret, true,$PartnerInfo->url);
			
			
			$filter = null;
			$numMedia = $kClient->media->count($filter);
			$numPages = self::getNumpages($numMedia);
			
			$filter = new KalturaMediaEntryFilter();
			$filter->mediaTypeEqual = 1; //only sync videos
			
			try
			{
				$pager = new KalturaFilterPager();
				$pager->pageSize = 500;
				$arrayResults = array();
				for($i = 1; $i<=$numPages; $i++)
				{
					$pager->pageIndex = $i;
					$results = $kClient->media->listAction($filter,$pager);
					$arrayResults[] = $results->objects;
					$arrayResultsMerged = array_merge($arrayResults[0], $results->objects);
				}
	
		

			}
			
			catch(Exception $ex)
			{
				echo self::getKalturaError(JText::_('COM_KAJOO_PARTNER_NOTCONNECTED'));
			}
			
			
			// Get all content
			$db = JFactory::getDBO();
			$query  = 'SELECT entry_id,id FROM `#__kajoo_content` WHERE partner_id = '.(int)$partnerId;
			$db->setQuery($query);
			$dbContent = $db->loadRowList();


			foreach($arrayResultsMerged as $key=>$result):

				$existsInDb =  KajooHelper::in_array_r($result->id, $dbContent);
			
				if ($existsInDb) {
				    //If api entry is in db update it
				    $query  = 'SELECT id FROM `#__kajoo_content` WHERE entry_id = "'.$result->id.'"';
			
					$db->setQuery($query);
					$idDB = $db->loadResult();
				    $data =new stdClass();
				    $data->id = $idDB;
					$data->entry_id = $result->id;
					$data->name = $result->name;
					$data->description = $result->description;
					$data->thumburl = $result->thumbnailUrl;
					$data->searchText = $result->searchText;
					$data->duration = $result->msDuration;
					$data->partner_id = $partnerId;
					$data->updated = JFactory::getDate('now');
					$db->updateObject( '#__kajoo_content', $data,'id');
					
				}
				else{
					//If api entry is NOT in DB add it
		
					$data =new stdClass();
					$data->entry_id = $result->id;
					$data->name = $result->name;
					$data->description = $result->description;
					$data->thumburl = $result->thumbnailUrl;
					$data->searchText = $result->searchText;
					$data->partner_id = $partnerId;
					$data->added = JFactory::getDate('now');
					$data->updated = JFactory::getDate('now');
					$db->insertObject( '#__kajoo_content', $data);
				}
				
				
				
			endforeach;	

			//Insert the object in an array for fast processing
			$result_array = array();
			foreach($arrayResultsMerged as $resobj):
				$result_array[] = $resobj->id;
			endforeach;

			
			//Check if the entry_id in DB is in the API results array and delete if not
			foreach ($dbContent as $key=>$c):

				$existsInKaltura =  KajooHelper::in_array_r($c[0], $result_array);
			
				if (!$existsInKaltura) {
					//Delete content
				   $query = 'DELETE FROM `#__kajoo_content` WHERE entry_id = "'.$c[0].'"';
				   $db->setQuery($query);
				   $db->query();
				   
				   //Delete custom fields
				   $query = 'DELETE FROM `#__kajoo_field_values_entry` WHERE content_id = '.$c[1];
				   $db->setQuery($query);
				   $db->query();
				}
				
			endforeach;
			
			
			self::cleanContent();
			

			
				
	}
	
	private function deleteContent($id)
	{
		//Delete the content and relations
		$db = JFactory::getDBO();
		$query = 'DELETE FROM `#__kajoo_content` WHERE id = '.$id;
		$db->setQuery($query);
		$db->query();
		
		$query = 'DELETE FROM `#__kajoo_content_client_rel` WHERE content_id = '.$id;
		$db->setQuery($query);
		$db->query();
		
		$query = 'DELETE FROM `#__kajoo_field_values_entry` WHERE content_id = '.$id;
		$db->setQuery($query);
		$db->query();
	}
	
	public static function cleanContent()
	{
		$db = JFactory::getDBO();
		$query  = 'SELECT * FROM `#__kajoo_content` WHERE partner_id NOT IN (SELECT id FROM `#__kajoo_partners`)';
		$db->setQuery($query);
		$dbContent = $db->loadAssocList();
		
		//Delete content without partners
		foreach($dbContent as $content)
		{
			self::deleteContent($content['id']);
		}
		
		//Delete duplicates with same entry_id
		$query  = 'SELECT entry_id,id, COUNT(*) c FROM `#__kajoo_content` GROUP BY entry_id HAVING c > 1';
		$db->setQuery($query);
		$dbContent = $db->loadAssocList();
		foreach($dbContent as $content)
		{
			self::deleteContent($content['id']);
		}



	}
	
	public static function in_array_r($needle, $haystack, $strict = true) {
	    foreach ($haystack as $item) {
	        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && self::in_array_r($needle, $item, $strict))) {
	            return true;
	        }
	    }
	
	    return false;
    }
    
    public static function formatTime($miliseconds)
    {
	    $uSec = $miliseconds % 1000;
		$miliseconds = floor($miliseconds / 1000);
		
		$seconds = $miliseconds % 60;
		$miliseconds = floor($miliseconds / 60);
		if($seconds<10)
			$seconds = '0'.$seconds;
		
		$minutes = $miliseconds % 60;
		$miliseconds = floor($miliseconds / 60); 
		if($minutes<10)
			$minutes = '0'.$minutes;
		
		$hours = $miliseconds % 60;
		$miliseconds = floor($miliseconds / 60); 
		if($hours<10)
			$hours = '0'.$hours;
		return $formated_duration = $hours.':'.$minutes.':'.$seconds;
    }
    
	public static function embedPlayer($partnerId,$entry_id,$configId)
	{
		
		// Connect to Kaltura API
		$PartnerInfo = self::getPartnerInfo($partnerId);
		$kClient = self::getKalturaClient($PartnerInfo->partnerid, $PartnerInfo->administratorsecret, true, $PartnerInfo->url);
		
		try
		{
			$uiConf = $kClient->uiConf->get($configId);
		}
		catch(Exception $ex)
		{
			echo self::getKalturaError(JText::_('Fail on getting uiconf'));
		}
		
		if($PartnerInfo->url==''):
			$PartnerInfo->url = 'http://www.kaltura.com';
		endif;


		
$video = '<script type="text/javascript" src="http://html5.kaltura.org/js"></script>		
		
<div id="myVideoTarget" style="width:720px;height:306px;">
	<!--  SEO and video metadata go here -->
	<span property="dc:description" content="An Open Source Movie from the Project Durian"></span>
	<span property="media:title" content="Sintel"></span>
	<span property="media:width" content="720"></span>
	<span property="media:height" content="306"></span>
</div>

<script>
	mw.setConfig( "KalturaSupport.LeadWithHTML5", true );
	mw.setConfig("Kaltura.ForceFlashOnDesktop", false );
	kWidget.embed({
		"targetId": "myVideoTarget",
		"wid": "_'.$PartnerInfo->partnerid.'",
		"uiconf_id" : "'.$uiConf->id.'",
		"entry_id" : "'.$entry_id.'",
		"flashvars":{
			"externalInterfaceDisabled" : false,
			"autoPlay" : false,
			"fooBar": "cats"
		},
		"readyCallback": function( playerId ){
			console.log( "kWidget player ready: " + playerId );
			var kdp = $("#" + playerId ).get(0);
		}
	});
</script>';
		
		
		
		return $video;
	}
	
	public static function getUrlEmbed($partnerId,$entry_id,$configId)
	{
		// Connect to Kaltura API
		$PartnerInfo = self::getPartnerInfo($partnerId);
		$kClient = self::getKalturaClient($PartnerInfo->partnerid, $PartnerInfo->administratorsecret, true, $PartnerInfo->url);
		
		try
		{
			$uiConf = $kClient->uiConf->get($configId);
		}
		catch(Exception $ex)
		{
			echo self::getKalturaError(JText::_('Fail on getting uiconf'));
		}
		
		if($PartnerInfo->url==''):
			$typeEmbed = 'sas';
			$PartnerInfo->url = 'http://www.kaltura.com';
		else:
			$typeEmbed = 'cdn';
		endif;
	

	$video  = '<script type="text/javascript" src="http://html5.kaltura.org/js"></script>';
	//$video  = '<object 
	//  id="video_'.$entry_id.'" 
	//  name="video_'.$entry_id.'" 
	//  type="application/x-shockwave-flash" 
	//  height="'.$uiConf->height.'" 
	//  width="'.$uiConf->width.'" 
	//  allowFullScreen="true" 
	//  allowNetworking="all" 
	//  allowScriptAccess="always" 
	//  data="'.$PartnerInfo->url.'/kwidget/wid/_'.$PartnerInfo->partnerid.'/uiconf_id/'.$uiConf->id.'/entry_id/'.$entry_id.'" 
	//  xmlns:dc="http://purl.org/dc/terms/" 
	//  xmlns:media="http://search.yahoo.com/searchmonkey/media/" 
	//  rel="media:video" 
	//  
//resource="'.$PartnerInfo->url.'/kwidget/wid/_'.$PartnerInfo->partnerid.'/uiconf_id/'.$uiConf->id.'/entry_id/'.$entry_id.'">
//	  <param name="allowFullScreen" value="true" />
//	  <param name="allowNetworking" value="all" />
//	  <param name="allowScriptAccess" value="always" />
//	  <param name="bgcolor" value="#000000" />
//	  <param name="flashVars" value="" />
//	  <param name="movie" 
//value="'.$PartnerInfo->url.'/kwidget/wid/_'.$PartnerInfo->partnerid.'/uiconf_id/'.$uiConf->id.'/entry_id/'.$entry_id.'" />
//	</object>';
	
		$video = '<script 
src="'.$PartnerInfo->url.'/p/'.$PartnerInfo->partnerid.'/sp/'.$PartnerInfo->partnerid.'00/embedIframeJs/uiconf_id/'.$uiConf->id.'/partner_id/'.$PartnerInfo->partnerid.'?autoembed=true&entry_id='.$entry_id.'&playerId=kaltura_player_1523104050&cache_st=1523104050&width=560&height=395&flashvars[streamerType]=auto"></script>';

		return $video;
		
	}
	
	public static function getContentFieldValue($contentId,$fieldId,$type)
	{
		$db = JFactory::getDbo();
		$query  = 'SELECT value FROM `#__kajoo_field_values_entry`';    	
    	$query .= ' WHERE content_id = '.(int)$contentId;
    	$query .= ' AND field_id = '.(int)$fieldId;
		$db->setQuery($query);
		$result = $db->loadResult();
		
		if($type==1):
			$query  = 'SELECT value FROM `#__kajoo_field_values`';    	
	    	$query .= ' WHERE id = '.(int)$result;
			$db->setQuery($query);
			$result = $db->loadResult();	
			return $result;
		else:
			return $result;		
		endif;

	}
	public static function getContentPartnerValue($partnerId)
	{
		$db = JFactory::getDbo();
		$query  = 'SELECT name FROM `#__kajoo_partners`';    	
    	$query .= ' WHERE id = '.$partnerId;
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}
	public static function getFrontEndPositionDetails()
	{
		$alias = array();
		$alias['searchbox']['name'] = JText::_('COM_KAJOO_SEARCHBOX');
		$alias['filters']['name'] = JText::_('COM_KAJOO_FILTERS');
		$alias['maincontent']['name'] = JText::_('COM_KAJOO_MAINCONTENT');
		$alias['categories']['name'] = JText::_('COM_KAJOO_CATEGORIES');
		$alias['partners']['name'] = JText::_('COM_KAJOO_TITLE_PARTNERS');
		$alias['wishlist']['name'] = JText::_('COM_KAJOO_TITLE_WISHLIST');
		
		return $alias;
	}
	public static function buildCatTree($items) {

    	$childs = array();

	    foreach($items->objects as $item)
	        $childs[$item->parentId][] = $item;
	
	    foreach($items->objects as $item) if (isset($childs[$item->id]))
	        $item->childs = $childs[$item->id];
	
	    return $childs[0];
	}
	
	
	public static function getPartnersList()
	{
		//get default partner
		$db = JFactory::getDBO();
		$query  = 'SELECT * FROM `#__kajoo_partners` WHERE state = 1';
		$db->setQuery($query);
		$partners = $db->loadObjectList();
		return $partners;
		
	}

	
	public static function getCategoryList($partnerId=0)
	{
		//get default partner
		$db = JFactory::getDBO();
		$query  = 'SELECT min(id) FROM `#__kajoo_partners` WHERE state = 1';
		$db->setQuery($query);
		$defaultPartnerId = $db->loadResult();
		if($partnerId==0):
			$partnerId = $defaultPartnerId;
		endif;
		
		// Connect to Kaltura API
		$PartnerInfo = self::getPartnerInfo($partnerId);
		$kClient = self::getKalturaClient($PartnerInfo->partnerid, $PartnerInfo->administratorsecret, true,$PartnerInfo->url);
		try
		{
			$api_result_categories = $kClient->category->listAction();
			
			$tree = self::buildCatTree($api_result_categories);
			
			return $tree;
		}
		catch(Exception $ex)
		{
			echo self::getKalturaError(JText::_('Fail on geting categories'));
			return false;
		}
	}
	public static function getCategoryListRaw($partnerId=0)
	{
		//get default partner
		$db = JFactory::getDBO();
		$query  = 'SELECT min(id) FROM `#__kajoo_partners` WHERE state = 1';
		$db->setQuery($query);
		$defaultPartnerId = $db->loadResult();
		if($partnerId==0):
			$partnerId = $defaultPartnerId;
		endif;
		
		// Connect to Kaltura API
		$PartnerInfo = self::getPartnerInfo($partnerId);
		$kClient = self::getKalturaClient($PartnerInfo->partnerid, $PartnerInfo->administratorsecret, true,$PartnerInfo->url);
		try
		{
			$api_result_categories = $kClient->category->listAction();
						
			return $api_result_categories;
		}
		catch(Exception $ex)
		{
			echo self::getKalturaError(JText::_('Fail on geting categories'));
			return false;
		}
	}
	public static function limit_words($string, $word_limit)
	{
		$words = explode(" ",$string);
		return implode(" ",array_splice($words,0,$word_limit));
	}
	public static function getConfig()
	{
		$db = JFactory::getDBO();
		$query  = 'SELECT * FROM `#__kajoo_config`';
		$db->setQuery($query);
		$config = $db->loadObjectList();
		return $config;
	}
		public static function sendMail($body,$subject,$recipient,$sender)
	{
		$mailer = JFactory::getMailer();
		$config = JFactory::getConfig();
		 
		$mailer->setSender($sender);
	
		$mailer->addRecipient($recipient);
		
		
		$mailer->isHTML(true);
		$mailer->setSubject($subject);
		$mailer->setBody($body);
		
		$send = $mailer->Send();
		if ( $send !== true ) {
		    echo JText::_('COM_KAJOO_TITLE_WISHLIST_MAILSENTCLOSENOT');
		} else {
		    echo JText::_('COM_KAJOO_TITLE_WISHLIST_MAILSENTCLOSE');
		}
		
		
	}
	public static function isValid()
	{
		$valid = new stdClass;
		$db = JFactory::getDBO();
		$query  = 'SELECT value FROM `#__kajoo_config` WHERE id = 2';
		$db->setQuery($query);
		$valid = $db->loadResult();
		$valid = json_decode($valid);
		if($valid[3]==1)
			$valid[0] = 0;
			
		return $valid;
	}
	public static function rediretLicense($error)
	{

        JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_kajoo&view=validation',false), $error, 'error' );
	}

}
