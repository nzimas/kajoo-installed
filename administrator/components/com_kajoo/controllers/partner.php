<?php
/**
 * @version      $Id: partner.php 65 2013-04-23 11:16:14Z freebandtech $
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
 * Partner controller class.
 */
class KajooControllerPartner extends JControllerForm
{

    function __construct() {
        $this->view_list = 'partners';
        parent::__construct();
    }
     public function postSaveHook($model)
	{
        $item = $model->getItem();	
        
        KajooHelper::syncPartner($item->id);
        
        if($item->defaultPlayer==''):
        
        	$PartnerInfo = KajooHelper::getPartnerInfo($item->id);
        	$kClient = KajooHelper::getKalturaClient($PartnerInfo->partnerid, $PartnerInfo->administratorsecret, true,$PartnerInfo->url);
     
        	try
			{
				$resultUi = $kClient->uiConf->listAction();
				$model->updatePartner($item->id,$resultUi->objects[0]->id);
			}
			catch(Exception $ex)
			{
			}

     
        endif;

    }
    

}