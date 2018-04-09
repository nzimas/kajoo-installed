<?php
/**
 * @version      $Id: default.php 66 2013-04-23 11:22:05Z freebandtech $
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

$partnerid = JRequest::getVar('partnerid', 0, 'get','INT');

$cats=  KajooHelper::getCategoryList($partnerid);

?>
<?php if(count($cats)>0):?>
<div class="well well-small">
	<legend><?php echo JText::_('COM_KAJOO_CONTENTS_CATEGORIES');?></legend>

	<?php
		function get_categories($cats,$i=0)
		{
			if($i==0):
				$html = '<ul id="navigationCategories" class="nav nav-pills nav-stacked">';
				$html .= '<li id="all"> <a href="#" rel="panel">All</a></li>';
				
			else:	
			    $html = '<ul class="nav nav-pills nav-stacked">';
		    endif;
		    
		    $i++;  
		    
		    foreach ($cats as $cat)
		    {
		
		        $html .= '<li id="' . $cat->id. '"><a href="#" rel="panel">' . $cat->name. '</a>';
		        if(isset($cat->childs))
		        {
		            $html .= get_categories($cat->childs,$i);
		        }
		        $html .= '</li>';
		    }
		    $html .= '</ul>';
		    return $html;
		  
		}

	?>
	

<?php if(count($cats)>0):?>
	<?php echo get_categories($cats); ?>
<?php endif; ?>


	<a href="#" id="selectAllCats">All</a> | <a href="#" id="unselectAllCats">None</a>
</div>
<?php endif;?>