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
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_kajoo/assets/css/kajoo.css');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/js/contents.js');


$user	= JFactory::getUser();
$userId	= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$canOrder	= $user->authorise('core.edit.state', 'com_kajoo');
$saveOrder	= $listOrder == 'a.ordering';


?>
<div class="alert alert-info">

	<?php echo JText::_('COM_KAJOO_CONTENTS_DBRECORDS');?>: <strong><?php echo KajooHelper::DatabaseNumRecords($this->state->get('filter.partner'));?></strong><br />
	<?php echo JText::_('COM_KAJOO_CONTENTS_KALTURARECORDS');?>: <strong><?php echo KajooHelper::kalturaNumRecords($this->state->get('filter.partner'));?></strong>
	
</div>
<form action="<?php echo JRoute::_('index.php?option=com_kajoo&view=contents'); ?>" method="post" name="adminForm" id="adminForm">
<?php if(!empty( $this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>

		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo JText::_('COM_KAJOO_CONTENTS_FILTERSEARCH');?></label>
				<input type="text" name="filter_search" placeholder="<?php echo JText::_('COM_KAJOO_CONTENTS_FILTERSEARCH'); ?>" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_KAJOO_FILTER_SEARCH_DESC'); ?>" />
			</div>
						<div class="btn-group pull-left hidden-phone">
				<button class="btn tip hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button class="btn tip hasTooltip" type="button" onclick="document.id('filter_search').value='';this.form.submit();" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
			</div>
			
			<div class="filter-search btn-group pull-right">
			
			<a href="<?php echo JRoute::_('index.php?option=com_kajoo&controller=contents_partners&task=contents.sync&partnerid='.$this->state->get('filter.partner')); ?>" class="btn btn-info"><i class="icon-refresh"></i> <?php echo JText::_('COM_KAJOO_CONTENTS_SYNCENTRIES');?></a>
			</div>
		</div>
	
	<div class="clearfix"> </div>
	

    
	<table class="table table-striped" id="adminList">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th class='left' width="5%">
				<?php echo JText::_('COM_KAJOO_CONTENTS_THUMBNAIL');?>
				</th>
				<th class='left' width="30%;">
			
				<?php echo JHtml::_('grid.sort',  'COM_KAJOO_CONTENTS_THUMBNAIL_NAME', 'a.name', $listDirn, $listOrder); ?>
				</th>
		
				<?php foreach ($this->allFields as $field): ?>
				<th class='left'>
					<?php echo $field->name;?>
				</th>
				<?php endforeach; ?>
				
                <?php if (isset($this->items[0]->state)) { ?>
				<th width="5%">
					<?php echo JHtml::_('grid.sort',  'JPUBLISHED', 'a.state', $listDirn, $listOrder); ?>
				</th>
                <?php } ?>

                <?php if (isset($this->items[0]->id)) { ?>
                <th width="1%" class="nowrap">
                    <?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                </th>
                <?php } ?>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="10">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) :
			$ordering	= ($listOrder == 'a.ordering');
			$canCreate	= $user->authorise('core.create',		'com_kajoo');
			$canEdit	= $user->authorise('core.edit',			'com_kajoo');
			$canCheckin	= $user->authorise('core.manage',		'com_kajoo');
			$canChange	= $user->authorise('core.edit.state',	'com_kajoo');
			/*
			try
			{
				$api_result = $kClient->media->get($item->entry_id);
			}
			catch(Exception $ex)
			{
				echo self::getKalturaError(JText::_('COM_KAJOO_CANTGETMEDIA'));
			}
			*/

			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				
				<td>
				<?php if ($canEdit) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_kajoo&task=content.edit&id='.(int) $item->id); ?>">
					<img style="width:80px;"src="<?php echo $item->thumburl;?>" class="img-polaroid"></a>
				<?php else : ?>
					<img style="width:80px;"src="<?php echo $item->thumburl;?>" class="img-polaroid">
				<?php endif;?>	
				</td>
				
				<td>
					<?php if (isset($item->checked_out) && $item->checked_out) : ?>
					<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'contents.', $canCheckin); ?>
					<?php endif; ?>
					<?php if ($canEdit) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_kajoo&task=content.edit&id='.(int) $item->id); ?>">
					<span class="content_name"><?php echo $this->escape($item->name); ?></span>
					</a>
					<?php else : ?>
					<span class="content_name"><?php echo $this->escape($item->name); ?></span>
					<?php endif;?>
					
					<?php if($item->description!=''):?>
					<div class="content_description">
					<?php echo KajooHelper::limit_words($item->description,33);?>
					</div>
					<?php endif;?>
					
				</td>

				
				<?php foreach ($this->allFields as $field): ?>

				
				<td>
					<?php  $fieldValue = KajooHelper::getContentFieldValue($item->id,$field->id,$field->type);
						if($fieldValue):
							echo $fieldValue;
						else:
							echo "-";
						endif;
						
					?>
				</td>
				<?php endforeach; ?>
				
                <?php if (isset($this->items[0]->state)) { ?>
				    <td class="center">
					    <?php echo JHtml::_('jgrid.published', $item->state, $i, 'contents.', $canChange, 'cb'); ?>
				    </td>
                <?php } ?>
                               <?php if (isset($this->items[0]->id)) { ?>
				<td class="center">
					<?php echo (int) $item->id; ?>
				</td>
                <?php } ?>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="partnerid" value="<?php echo $this->state->get('filter.partner');?>" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
	</div>	
</form>