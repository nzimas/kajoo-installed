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

$document =JFactory::getDocument();
$document->addScript(JURI::base() . 'components/com_kajoo/assets/js/content.js');

?>
<?php JHTML::_( 'behavior.modal' ); ?> 

<?php if( $this->item ) :

$params = JComponentHelper::getParams( 'com_kajoo' );
$playerConfigId = $params->get( 'playerConfigId',8847102 );
$allow_download = $params->get( 'allow_download',1 );
$show_fields = $params->get( 'show_fields',1 );
$show_description = $params->get( 'show_description',1 );
$show_thumbs = $params->get( 'show_thumbs',1 );
$show_embed = $params->get( 'show_embed',1 );

// Connect to Kaltura API
$PartnerInfo = KajooHelper::getPartnerInfo($this->item->partner_id);
$kClient = KajooHelper::getKalturaClient($PartnerInfo->partnerid, $PartnerInfo->administratorsecret, true,$PartnerInfo->url);

try
{
	$thumbs = $kClient->thumbAsset->getbyentryid($this->item->kaltura_video->id);
}
catch(Exception $ex)
{
	echo KajooHelper::getKalturaError(JText::_('Fail on geting thumbs'));
}



 ?>

<a href="<?php echo JRoute::_('index.php?option=com_kajoo&view=contents'); ?>" class="btn btn-small"><i class="icon-chevron-left"></i> <?php echo JText::_('COM_KAJOO_ITEMCONTENT_PREVPAGE');?></a>

<div class="infoDetail_container">
	<h2><?php echo $this->item->name;?></h2>

	<div class="infoDetail_video">

		
		<?php echo KajooHelper::getUrlEmbed($this->item->partner_id,$this->item->kaltura_video->id,$PartnerInfo->defaultPlayer); ?>
		<hr>
		<?php if($show_embed):?>
		<textarea><?php  echo KajooHelper::getUrlEmbed($this->item->partner_id,$this->item->kaltura_video->id,$PartnerInfo->defaultPlayer); ?></textarea>
		<?php endif;?>

	</div>
	<?php if($show_thumbs):?>
		<div class="well">
		<ul class="gallerythumbsFront">
			<?php foreach($thumbs as $thumb):
				try
				{
					$thumb_url = $kClient->thumbAsset->serve($thumb->id);
				}
				catch(Exception $ex)
				{
					echo KajooHelper::getKalturaError(JText::_('Fail on geting thumb info'));
				}
				
			?>
				<li id="<?php echo $thumb->id;?>">
					<a href="<?php echo $thumb_url;?>.jpg" class="fancybox-button" rel="fancybox-button" title="<?php echo $this->item->name;?>">
						<img class="collectionthumbs" src="<?php echo $thumb_url;?>" />
					</a>
					
				</li>
			<?php endforeach;?>
	
		</ul>
		<div class="clearfix"></div>
		</div>
	<?php endif; //show thumbs ?>	
		
	<div class="infoDetail_share">
	
	<?php if($show_description):?>	
		<?php if($this->item->kaltura_video->description!=''): ?>
		<div class="infoDetail_description">
			<div class="well well-small">
			<?php echo $this->item->description;?>	
			</div>
		</div>
		<?php endif;?>
	<?php endif;?>
	
		<div class="well">
			<ul class="itemListDetails">
				<li><span class="tit_itemList"><?php echo JText::_('COM_KAJOO_ITEMCONTENT_CREATED');?></span> <?php echo JFactory::getDate($this->item->kaltura_video->createdAt); ?></li>
				<li><span class="tit_itemList"><?php echo JText::_('COM_KAJOO_ITEMCONTENT_UPDATED');?></span> <?php echo JFactory::getDate($this->item->kaltura_video->updatedAt); ?></li>
				<li><span class="tit_itemList"><?php echo JText::_('COM_KAJOO_ITEMCONTENT_LENGTH');?></span> <?php echo KajooHelper::formatTime($this->item->kaltura_video->msDuration);?></li>
				<li><span class="tit_itemList"><?php echo JText::_('COM_KAJOO_ITEMCONTENT_VIEWS');?></span> <?php echo $this->item->kaltura_video->views;?></li>
				<li><span class="tit_itemList"><?php echo JText::_('COM_KAJOO_ITEMCONTENT_PLAYS');?></span> <?php echo $this->item->kaltura_video->plays;?></li>
				
				<?php if($this->item->kaltura_video->categories!=''): ?>
					<li><span class="tit_itemList"><?php echo JText::_('COM_KAJOO_ITEMCONTENT_CATEGORIES');?></span> <?php echo $this->item->kaltura_video->categories;?></li>
				<?php endif;?>
				
				<?php if($this->item->kaltura_video->tags!=''): ?>
					<li><span class="tit_itemList"><?php echo JText::_('COM_KAJOO_ITEMCONTENT_KEYS');?></span> <?php echo $this->item->kaltura_video->tags;?></li>
				<?php endif;?>
				
				<?php if($allow_download): ?>
					<li><span class="tit_itemList"><?php echo JText::_('COM_KAJOO_ITEMCONTENT_DOWNLOAD');?></span> <a href="<?php echo $this->item->kaltura_video->dataUrl;?>">Download source</a></li>
				<?php endif;?>
				
				<?php if($show_fields):?>
					<hr>
					<?php foreach ($this->item->fields as $field):?>
						<?php $fieldValue = KajooHelper::getContentFieldValue($this->item->id,$field->id,$field->type); ?>
						<?php if($fieldValue):?>
							<li><span class="tit_itemList"><?php echo $field->name;?></span> <?php echo $fieldValue;?></li>
						<?php endif;?>
					<?php endforeach;?>
				<?php endif;?>
				
			</ul>
		</div>
	</div>

	
</div>
 
     <?php if(JFactory::getUser()->authorise('core.edit', 'com_kajoo.content'.$this->item->id)): ?>
		<a href="<?php echo JRoute::_('index.php?option=com_kajoo&task=content.edit&id='.$this->item->id); ?>">Edit</a>
	<?php endif; ?>

<?php else: ?>
    Could not load the item
<?php endif; ?>
