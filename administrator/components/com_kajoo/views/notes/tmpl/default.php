<?php
/**
 * @version     0.1
 * @package     com_kajoo
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Miguel Puig <miguel@freebandtech.com> - http://freebandtech.com
 */


// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHTML::_('script','system/multiselect.js',false,true);
// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_kajoo/assets/css/kajoo.css');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/js/jquery.validate.min.js');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/js/notes.js');

$document->addScript(JURI::base() . 'components/com_kajoo/assets/data-tables/js/jquery.dataTables.min.js');
$document->addStyleSheet('components/com_kajoo/assets/data-tables/css/jquery.dataTables.css');


$user	= JFactory::getUser();
$userId	= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$canOrder	= $user->authorise('core.edit.state', 'com_kajoo');
$saveOrder	= $listOrder == 'a.ordering';
?>


<?php if(!empty( $this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>




	<div class="clearfix"> </div>
	<div id="ajaxLoader"><div id="spin"></div></div>


			
			<legend><?php echo JText::_('COM_KAJOO_NOTES_LISTCLIENTS');?></legend>
			<div class="alert alert-info"><?php echo JText::_('COM_KAJOO_NOTES_LISTCLIENTSINFO');?></div>

			<div id="clientsCont"></div>
			
			
			<div class="span4">
				<div id="clientAddEdit">
				<div class="well well-small">
					<legend id="titEdAdd"><?php echo JText::_('COM_KAJOO_NOTES_ADDNEWCLIENT');?></legend>
					    <form class="form" name="addClient" id="addClient">
						    <div class="control-group">
							    <label class="control-label" for="name"><?php echo JText::_('COM_KAJOO_NOTES_NAME');?></label>
								 <div class="controls">
								    <input type="text" id="name" name="name" placeholder="Name">
							    </div>
						    </div>
						    
						    <div class="control-group">
							    <label class="control-label" for="company"><?php echo JText::_('COM_KAJOO_NOTES_COMPANY');?></label>
								 <div class="controls">
								    <input type="text" id="company" name="company" placeholder="Company">
							    </div>
						    </div>
						    
						    <div class="control-group">
							    <label class="control-label" for="position"><?php echo JText::_('COM_KAJOO_NOTES_POSITION');?></label>
								 <div class="controls">
								    <input type="text" id="position" name="position" placeholder="Position">
							    </div>
						    </div>
						    
						    <div class="control-group">
							    <label class="control-label" for="email"><?php echo JText::_('COM_KAJOO_NOTES_EMAIL');?></label>
								 <div class="controls">
								    <input type="text" id="email" name="email" placeholder="Email">
							    </div>
						    </div>
						    
						    <div class="control-group">
							    <label class="control-label" for="phone"><?php echo JText::_('COM_KAJOO_NOTES_PHONE');?></label>
								 <div class="controls">
								    <input type="text" id="phone" name="phone" placeholder="Phone">
							    </div>
						    </div>
						    
						    <div class="control-group">
							    <label class="control-label" for="observations"><?php echo JText::_('COM_KAJOO_NOTES_OBSERVATIONS');?></label>
								 <div class="controls">
								    <textarea id="observations" name="observations"></textarea>
							    </div>
						    </div>
						    
						    
						    <input type="hidden" id="idClient" name="idClient" value="">
						   	<button type="submit" id="addClientBtn" class="btn btn-success"><?php echo JText::_('COM_KAJOO_NOTES_ADDCLIENT');?></button>
						   	<button id="cancelClientBtn" class="btn"><?php echo JText::_('COM_KAJOO_NOTES_CANCEL');?></button>
						    </div>
						    </div>
					    </form>
		
			</div>
			
			
			
	<div class="span7">
				<div class="well well-small" id="clientDataWrapper">

					<legend id="titEdAdd"><?php echo JText::_('COM_KAJOO_NOTES_CLDATA');?></legend>
					
					<div id="clientData"></div>
					<hr>
					<legend id="laddCl"><?php echo JText::_('COM_KAJOO_NOTES_ADDCONTENT');?></legend>
					
		
					
					<table class="table table-striped dataTable" id="tableContents">
						<thead>
							<tr>
								<th>#</th>
								<th></th>	
								<th></th>
								<th></th>				
							</tr>
						</thead>
						<tbody>
							<?php foreach ($this->allContents as $key=>$content):?>
						
							<tr id="<?php echo $content->id;?>" class="rowContent">
			
								<td><img src="<?php echo $content->thumburl;?>" style="height:20px; width:20px;"/></td>
								<td><?php echo $content->name;?></td>	
								<td><i style="font-size:85%; color:#999;"><?php echo $content->entry_id;?></i></td>
								<td><button class="btn btn-mini btn-success addContent" id="<?php echo $content->id;?>"><?php echo JText::_('COM_KAJOO_NOTES_ADDCONTENTBTN');?></button></td>			
							</tr>
							<?php endforeach;?>
						</tbody>
						
					</table>
</div>
					
				</div>


