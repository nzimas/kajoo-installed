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

$user	= JFactory::getUser();
$userId	= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$canOrder	= $user->authorise('core.edit.state', 'com_kajoo');
$saveOrder	= $listOrder == 'a.ordering';
?>

<form action="<?php echo JRoute::_('index.php?option=com_kajoo&view=contents_partners'); ?>" method="post" name="adminForm" id="adminForm">
<?php if(!empty( $this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
<div id="ajaxLoader"><div id="spin"></div></div>


	<table class="table table-striped" id="adminList">
		<thead>
			<tr>
	
	
				<th class='left' width="40%">
				<?php echo JHtml::_('grid.sort',  'COM_KAJOO_PARTNERS_PARTNERNAME', 'a.name', $listDirn, $listOrder); ?>
				</th>
				
				<th class='left'>
				<?php echo JText::_('COM_KAJOO_STATUSPARTNER'); ?>
				</th>
				
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
			?>
			<tr class="row<?php echo $i % 2; ?>">

				
				<td>

				<a href="<?php echo JRoute::_('index.php?option=com_kajoo&view=contents&partnerid='.(int) $item->id); ?>">
					<?php echo $this->escape($item->name); ?></a>

				</td>
				<td>
				<?php
				
				$kClient = KajooHelper::getKalturaClient($item->partnerid, $item->administratorsecret, true);
				try
				{
					$results = $kClient->media->listAction();
					echo '<span class="label label-success">'.JText::_('COM_KAJOO_PARTNER_CONNECTED').'</span>';
				}
				catch(Exception $ex)
				{
					echo '<span class="label label-important">'.JText::_('COM_KAJOO_PARTNER_NOTCONNECTED').'</span>';
				}
				?>		
				</td>
				
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
	</div>	
</form>