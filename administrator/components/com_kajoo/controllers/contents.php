<?php
/**
 * @version     0.1
 * @package     com_kajoo
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Miguel Puig <miguel@freebandtech.com> - http://freebandtech.com
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * Contents list controller class.
 */
class KajooControllerContents extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'content', $prefix = 'KajooModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	function delete()
    {

	    parent::delete();
    }
	public function sync()
	{	
		$partnerid = JRequest::getVar('partnerid', 0, 'GET', 'INT');
		if($partnerid==0):
			
			$partners = KajooHelper::getPartnersList();

			foreach ($partners as $partner):
				KajooHelper::syncPartner($partner->id);
			endforeach;
			$link = JRoute::_('index.php?option=com_kajoo&view=contents',false);
		else:
			$PartnerInfo = KajooHelper::syncPartner($partnerid);
			$link = JRoute::_('index.php?option=com_kajoo&view=contents&partnerid='.$partnerid,false);
			
		endif;
		$msg = JText::_('COM_KAJOO_CONTENTS_SYNCMESSAGESUCC');
		$this->setRedirect($link,$msg);
	}
}