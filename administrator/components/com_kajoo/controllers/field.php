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

jimport('joomla.application.component.controllerform');

/**
 * Field controller class.
 */
class KajooControllerField extends JControllerForm
{

    function __construct() {
        $this->view_list = 'fields';
        parent::__construct();
    }
    
	public function postSaveHook($model, $validData)
	{
        $item = $model->getItem();	        	        
       
        //Update field values
        $type = JRequest::getVar('jform[type]', 1, 'post', 'INT');

        if($type==1):
	        $field_values = JRequest::getVar('field_values', '[]', 'post','ARRAY');
	        $field_ids = JRequest::getVar('field_ids', '[]', 'post','ARRAY');

	        foreach ($field_ids as $key=>$id):
	      
	        	if($id==-1):
	        		$model->insertFieldValue($field_values[$key],$item->get('id'),$key);
	        	else:
	        		$model->updateFieldValue($field_values[$key],$item->get('id'),$key,$id);
	        	endif;
	        
	        endforeach;


        endif;

	}



}