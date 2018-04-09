<?php
/**
 * @version     2.0.1
 * @package     com_kajoo
 * @copyright   Copyright (C) 2012-2018. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Miguel Puig <miguel@freebandtech.com> - http://freebandtech.com
 */

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_kajoo/assets/css/kajoo.css');
$document->addStyleSheet('components/com_kajoo/assets/css/jquery.tagit.css');
$document->addStyleSheet('components/com_kajoo/assets/css/tagit.ui-zendesk.css');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/js/content.js');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/js/tag-it.js');


// Connect to Kaltura API
$PartnerInfo = KajooHelper::getPartnerInfo($this->item->partner_id);
$kClient = KajooHelper::getKalturaClient($PartnerInfo->partnerid, $PartnerInfo->administratorsecret, true,$PartnerInfo->url);


try
{
	$uiConf = $kClient->uiConf->listAction();
}
catch(Exception $ex)
{
	echo KajooHelper::getKalturaError(JText::_('Fail on geting Uiconfig action'));
}

try
{
	$api_result = $kClient->media->get($this->item->entry_id);
}
catch(Exception $ex)
{
	echo KajooHelper::getKalturaError(JText::_('COM_KAJOO_CANTGETMEDIA'));
}

try
{
	$api_result_categories = $kClient->category->listAction();
}
catch(Exception $ex)
{
	echo KajooHelper::getKalturaError(JText::_('Fail on geting categories'));
}




try
{
	$flavors = $kClient->flavorAsset->getflavorassetswithparams($this->item->entry_id);
}
catch(Exception $ex)
{
	echo KajooHelper::getKalturaError(JText::_('Fail on geting flavors'));
}



try
{
	$thumbs = $kClient->thumbAsset->getbyentryid($this->item->entry_id);
}
catch(Exception $ex)
{
	echo KajooHelper::getKalturaError(JText::_('Fail on geting thumbs'));
}



$formated_duration = KajooHelper::formatTime($api_result->msDuration);

?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
	
		if (task == 'content.cancel' || document.formvalidator.isValid(document.id('content-form'))) {
			Joomla.submitform(task, document.getElementById('content-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
	
$(function () {

	
	$('#methodTags').tagit({
	    allowSpaces: true,
        removeConfirmation: true
	});

});



$(function () {

	<?php $session =JFactory::getSession();
		  $currenttab = $session->get( 'currenttab', '#review' );
	?>
	$('#myTabsContent a[href="<?php echo $currenttab;?>"]').tab('show'); // Select last tab

});


	
   
    	
</script>


<form action="<?php echo JRoute::_('index.php?option=com_kajoo&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="content-form" class="form-validate">
<?php if(!empty( $this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
<div id="ajaxLoader"><div id="spin"></div></div>
<h2><?php echo $api_result->name;?></h2>
<div class="tabbable">
    <ul class="nav nav-tabs" id="myTabsContent">    
        <li><a href="#review"  class="active" data-toggle="tab"><?php echo JText::_('COM_KAJOO_MENUCONTENT_REVIEW'); ?></a></li>
        <li><a href="#categories" data-toggle="tab"><?php echo JText::_('COM_KAJOO_MENUCONTENT_CATEGORIES'); ?></a></li>
        <li><a href="#customfields" data-toggle="tab"><?php echo JText::_('COM_KAJOO_MENUCONTENT_CUSTOMFIELDS'); ?></a></li>
        <li><a href="#flavors" data-toggle="tab"><?php echo JText::_('COM_KAJOO_MENUCONTENT_FLAVORS'); ?></a></li>
        <li><a href="#thumb" data-toggle="tab"><?php echo JText::_('COM_KAJOO_MENUCONTENT_THUMB'); ?></a></li>
        <li><a href="#preview" data-toggle="tab"><?php echo JText::_('COM_KAJOO_MENUCONTENT_PREVIEWEMBED'); ?></a></li>
    </ul>
 
    <div class="tab-content">
    
        <div class="tab-pane active" id="review">
			<div class="well">
				<legend><?php echo JText::_('COM_KAJOO_MENUCONTENT_REVIEW'); ?></legend>
			    <table class="table table-striped">
			    
			    	<tr>
				    	<td class="tdtablecon"><?php echo JText::_('COM_KAJOO_MENUCONTENT_THUMB'); ?></td>
				    	<td><img class="img-polaroid" id="thumbreview" src="<?php echo $api_result->thumbnailUrl;?>" style="width:90px;" /></td>
			    	</tr>
			    	
			    	<tr>
				    	<td class="tdtablecon"><?php echo JText::_('COM_KAJOO_CONTENTEDIT_KAJOOID'); ?></td>
				    	<td><?php echo $this->item->id;?></td>
			    	</tr>
			    	<tr>
				    	<td class="tdtablecon"><?php echo JText::_('COM_KAJOO_CONTENTEDIT_PARTNERID'); ?></td>
				    	<td><?php echo $PartnerInfo->partnerid;?></td>
			    	</tr>
			    	
			    	<tr>
				    	<td class="tdtablecon"><?php echo JText::_('COM_KAJOO_FORM_LBL_CONTENT_ENTRY_ID'); ?></td>
				    	<td><?php echo $this->item->entry_id;?> <a href="<?php echo $api_result->downloadUrl;?>">[<?php echo JText::_('COM_KAJOO_FORM_LBL_CONTENT_DOWNLOADSOURCE'); ?>]</a></td>
			    	</tr>
			    	<tr>
				    	<td class="tdtablecon"><?php echo JText::_('COM_KAJOO_CONTENTEDIT_ADDED'); ?></td>
				    	<td><?php echo JFactory::getDate($api_result->createdAt)->Format('d-M-Y');?></td>
			    	</tr>
			    	<tr>
				    	<td class="tdtablecon"><?php echo JText::_('COM_KAJOO_CONTENTEDIT_UPDATED'); ?></td>
				    	<td><?php echo JFactory::getDate($api_result->updatedAt)->Format('d-M-Y');?></td>
			    	</tr>
			    	
			    	
			    	<tr>
				    	<td class="tdtablecon"><?php echo JText::_('COM_KAJOO_CONTENTEDIT_PLAYS'); ?></td>
				    	<td><?php echo $api_result->plays;?></td>
			    	</tr>
			    	
			    	<tr>
				    	<td class="tdtablecon"><?php echo JText::_('COM_KAJOO_CONTENTEDIT_DURATION'); ?></td>
				    	<td><?php echo $formated_duration;?></td>
			    	</tr>
			    	<tr>
				    	<td class="tdtablecon"><?php echo JText::_('COM_KAJOO_MENUCONTENT_CATEGORIES'); ?></td>
				    	<td><?php echo $api_result->categories;?></td>
			    	</tr>

			    	<tr>
				    	<td class="tdtablecon"><?php echo JText::_('COM_KAJOO_CONTENTEDIT_VIDEONAME'); ?></td>
				    	<td><input type="text" id="name" name="name" value="<?php echo $api_result->name;?>" class="input-xxlarge"></td>
			    	</tr>
			    	
			    	<tr>
				    	<td class="tdtablecon"><?php echo JText::_('COM_KAJOO_CONTENTEDIT_VIDEODESC'); ?></td>
				    	<td><textarea rows="5" name="description" id="description"><?php echo $api_result->description;?></textarea></td>
			    	</tr>	
			    			    	
			    	<tr>
				    	<td class="tdtablecon"><?php echo JText::_('COM_KAJOO_MENUCONTENT_TAGS'); ?></td>
				    	<td>
				    	    <div class="alert alert-info">
						    <?php echo JText::_('COM_KAJOO_MENUCONTENT_TAGS_INFO'); ?>
						    </div>
					    	<input name="tags" id="methodTags" value="<?php echo $api_result->tags;?>">
				    	</td>
			    	</tr>	

			    </table>

			</div>
        </div>
        
        
        <div class="tab-pane fade" id="categories">
	        <div class="well">
				<legend><?php echo JText::_('COM_KAJOO_MENUCONTENT_CATEGORIES'); ?></legend>
				
				
				<?php
				$categoriesArray = explode(",", $api_result->categoriesIds);

				?>
				
				<select multiple="multiple" name="categories[]" style="height:200px;">
					<?php foreach ($api_result_categories->objects as $category):?>
						<option value="<?php echo $category->id;?>" <?php if(in_array($category->id, $categoriesArray)) echo "selected";?>><?php echo $category->fullName;?></option>
					<?php endforeach;?>
					

				</select>
				
			</div>
        </div>

        
        <div class="tab-pane fade" id="customfields">
	        <div class="well">
				<h3><?php echo JText::_('COM_KAJOO_MENUCONTENT_CUSTOMFIELDS'); ?></h3>
				<?php if((isset($this->fields))&&(count($this->fields)>0)):?>
				<table class="table table-striped">
				<?php foreach ($this->fields as $field):

					$actualValue = KajooHelper::getContentFieldValue($this->item->id,$field->id,$field->type);
				?>
					<tr>
						<td class="tdtablecon"><?php echo $field->name;?></td>
						<td>
							<?php if($field->type == 1): ?>
							<select name="<?php echo $field->alias;?>">
								<option value=""><?php echo JText::_('COM_KAJOO_CONTENTEDIT_SELECTFIELD'); ?></option>
								<?php foreach ($field->values as $value):?>								
									<option value="<?php echo $value->id;?>"<?php if($actualValue==$value->value) echo 'selected="selected"'; ?>><?php echo $value->value;?></option>
								<?php endforeach;?>
							</select>
							<?php else:?>
							<input type="text" value="<?php if(isset($actualValue)!='') echo $actualValue; ?>" name="<?php echo $field->alias;?>">
							<?php endif;?>
						</td>
					</tr>
				<?php endforeach;?>	
				</table>
				<?php else:?>
				<div class="alert">
				<?php echo JText::_('COM_KAJOO_MENUCONTENT_CUSTOMFIELDSCHECKCANADD'); ?>
				</div>
				
				<?php endif;?>
			</div>
        </div>
        
        <div class="tab-pane fade" id="flavors">
	        <div class="well">

				<table class="table table-striped">
				<tr>
					<th width="20%"><?php echo JText::_('COM_KAJOO_MENUCONTENT_FLAVORNAME'); ?></th>
					<th><?php echo JText::_('COM_KAJOO_MENUCONTENT_FLAVORCODEC'); ?></th>
					<th width="80"><?php echo JText::_('COM_KAJOO_MENUCONTENT_PLAYERSCONFWIDTH'); ?></th>
					<th width="80"><?php echo JText::_('COM_KAJOO_MENUCONTENT_PLAYERSCONFHEIGHT'); ?></th>
					<th><?php echo JText::_('COM_KAJOO_MENUCONTENT_FLAVORDESC'); ?></th>
					<th><?php echo JText::_('COM_KAJOO_MENUCONTENT_FLAVORACTION'); ?></th>
				</tr>
				
				<?php foreach ($flavors as $key=>$flavor):?>

				    <div class="modal modalflavor" id="modalFlavor<?php echo $key;?>"  data-backdrop="true" data-controls-modal="event-modal" data-keyboard="true">
					    <div class="modal-header">
					    	<h3 id="myModalLabel"><?php echo $flavor->flavorParams->name;?></h3>
					    </div>
					    <div class="modal-body">
					    	<ul class="flavorsinfo">
					    		<li><span class="labelinfo"><?php echo JText::_('COM_KAJOO_MENUCONTENT_FLAVOR_BITRATE'); ?>: </span><?php if(isset($flavor->flavorAsset->bitrate)){ echo $flavor->flavorAsset->bitrate; } else {echo "-"; }?></li>
					    		<li><span class="labelinfo"><?php echo JText::_('COM_KAJOO_MENUCONTENT_FLAVOR_FRAMERATE'); ?>: </span><?php if(isset($flavor->flavorAsset->frameRate)){ echo $flavor->flavorAsset->frameRate; } else {echo "-"; }?></li>
					    		<li><span class="labelinfo"><?php echo JText::_('COM_KAJOO_MENUCONTENT_FLAVOR_TAGS'); ?>: </span><?php if(isset($flavor->flavorAsset->tags)){ echo $flavor->flavorAsset->tags; } else {echo "-"; }?></li>
					    		<li><span class="labelinfo"><?php echo JText::_('COM_KAJOO_MENUCONTENT_FLAVORCODEC'); ?>: </span><?php if(isset($flavor->flavorParams->videoCodec)){ echo $flavor->flavorParams->videoCodec; } else {echo "-"; }?></li>
					    		<li><span class="labelinfo"><?php echo JText::_('COM_KAJOO_MENUCONTENT_FLAVORBITRATE'); ?>: </span><?php if(isset($flavor->flavorParams->videoBitrate)){ echo $flavor->flavorParams->videoBitrate; } else {echo "-"; }?></li>
					    		<li><span class="labelinfo"><?php echo JText::_('COM_KAJOO_MENUCONTENT_FLAVORCODECAUDIO'); ?>: </span><?php if(isset($flavor->flavorParams->audioCodec)){ echo $flavor->flavorParams->audioCodec; } else {echo "-"; }?></li>
					    		<li><span class="labelinfo"><?php echo JText::_('COM_KAJOO_MENUCONTENT_FLAVORBRATEAUDIO'); ?>: </span><?php if(isset($flavor->flavorParams->audioBitrate)){ echo $flavor->flavorParams->audioBitrate; } else {echo "-"; }?></li>
					    		<li><span class="labelinfo"><?php echo JText::_('COM_KAJOO_MENUCONTENT_FLAVORFORMAT'); ?>: </span><?php if(isset($flavor->flavorParams->format)){ echo $flavor->flavorParams->format; } else {echo "-"; }?></li>
					    		<?php if(isset($flavor->flavorAsset->id)): ?>
								<li>
								<span class="labelinfo"></span>
								<a class="btn" href="<?php echo JRoute::_('index.php?option=com_kajoo&controller=content&task=content.downloadFlavor&format=raw&tmpl=component&partnerid='.(int)$this->item->partner_id.'&flavorid='.$flavor->flavorAsset->id); ?>">
									<?php echo JText::_('COM_KAJOO_DOWNLOAD'); ?>
								</a>
								</li>
					    		<?php endif; ?>
					    	</ul>
					    	
					    </div>
					    <div class="modal-footer">
						    <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('COM_KAJOO_MENUCONTENT_CLOSE'); ?></button>
					    </div>
				    </div>	
				
				<?php if(count($flavor->flavorAsset)>0):?>
				   			
				<tr>
					<td><?php echo $flavor->flavorParams->name;?></td>
					<td><?php if(isset($flavor->flavorParams->videoCodec)){ echo $flavor->flavorParams->videoCodec; } else {echo "-"; }?></td>
					<td><?php if(isset($flavor->flavorAsset->width)){ echo $flavor->flavorAsset->width;} else {echo "-"; }?></td>
					<td><?php if(isset($flavor->flavorAsset->height)){ echo $flavor->flavorAsset->height; } else {echo "-"; }?></td>
					<td><?php echo $flavor->flavorParams->description;?></td>
					<td>
						
						<div class="convert_container" id="<?php echo $flavor->flavorAsset->id;?>">
						    <div class="btn-group">
						    <a class="btn dropdown-toggle btn-info" data-toggle="dropdown" href="#">
						    	<i class="icon-cog icon-white"></i> <?php echo JText::_('COM_KAJOO_MENUCONTENT_FLAVORACTION'); ?>
						    <span class="caret"></span>
						    </a>
						    <ul class="dropdown-menu">
						    	<li><a href="#modalFlavor<?php echo $key;?>" role="button" data-toggle="modal"><i class="icon-eye-open icon-white"></i> <?php echo JText::_('COM_KAJOO_FLAVORS_VIEWDETAILS'); ?></a></li>
						    	<?php if($flavor->flavorParams->id!=0):?>
						    		<li><a href="#" class="deleteflavor" id="<?php echo $flavor->flavorAsset->id;?>"><i class="icon-minus icon-white"></i> <?php echo JText::_('COM_KAJOO_FLAVORS_DELETE'); ?></a></li>
						    		<li><a href="#" class="reconvertflavor" id="<?php echo $flavor->flavorAsset->id;?>"><i class="icon-refresh icon-white"></i> <?php echo JText::_('COM_KAJOO_FLAVORS_RECONVERT'); ?></a></li>
						    	<?php endif;?>
						    </ul>
						    </div>
					
						</div>
					
					</td>
				</tr>
				<?php else:?>
					
				<tr>
					<td><?php echo $flavor->flavorParams->name;?></td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>
					<div class="convert_container" id="<?php echo $flavor->flavorParams->id;?>">
						<a href="#" title="<?php echo JText::_('COM_KAJOO_FLAVORS_CONVERT'); ?>" id="<?php echo $flavor->flavorParams->id;?>" role="button" class="btn btn-success convertflavor" data-toggle="modal">
						<i class="icon-refresh icon-white"></i> <?php echo JText::_('COM_KAJOO_FLAVORS_CONVERT'); ?>
						</a>
					</div>
					</td>
				</tr>
				
				
				<?php endif;?>
				
				<?php endforeach;?>
								
				</table>
				
				
				    <div class="alert alert-info">
				    <?php echo JText::_('COM_KAJOO_MENUCONTENT_FLAVORINFOMORE'); ?>
				    </div>
				 
			</div>
        </div>


        
        <div class="tab-pane fade" id="thumb">
	        <div class="well">

<?php if($PartnerInfo->url==''):
	$urlKaltura = 'http://www.kaltura.com';
	$urlKalturaCDN = 'http://cdn.kaltura.com';
else:
	$urlKaltura = $PartnerInfo->url;
	$urlKalturaCDN = $PartnerInfo->url;
endif;
	
?>



    <div id="thumbVideo<?php echo $this->item->entry_id;?>" style="width:640px;height:320px;"></div>
    <script type="text/javascript" src="http://html5.kaltura.org/js"></script>
	<script>
    	mw.setConfig( 'forceMobileHTML5', false );
    		
	mw.setConfig("Kaltura.ServiceUrl", "<?php echo $urlKaltura;?>" );
	mw.setConfig("Kaltura.ServiceBase", "/api_v3/index.php?service=" );
	mw.setConfig("Kaltura.CdnUrl", "<?php echo $urlKalturaCDN;?>" );
    	
    	
	    kWidget.embed({
	    'targetId': 'thumbVideo<?php echo $this->item->entry_id;?>',
	    'wid': '_<?php echo $PartnerInfo->partnerid;?>',
	    'uiconf_id' : '<?php echo $uiConf->objects[0]->id;?>',
	    'entry_id' : '<?php echo $this->item->entry_id;?>',
	    'host' : '<?php echo $PartnerInfo->url;?>',
	    'flashvars':{
                        'allowscriptaccess' : 'always',
                        'autoPlay' : false
        },
	    'readyCallback': function( playerId ){
				//console.log( "kWidget player ready: " + playerId );
				var kdp = $('#' + playerId ).get(0);
				
				kdp.addJsListener("playerUpdatePlayhead", "playerUpdatePlayheadHandler");
			}
	    });
	    
	    var newVolume = 0;
		var oldVolume = 1;

		function playerUpdatePlayheadHandler(data, id) {
			
			$('#videotime').val(data);
			
			return true;
		}
		
		
		mw.setConfig( 'KalturaSupport.LeadWithHTML5', true );
		
    </script>




	<div class="alert alert-info">
	<?php echo JText::_('COM_KAJOO_MENUCONTENT_THUMB_INFO'); ?>
	</div>
	
	<a href="#" class="btn btn-success getTimethumb"><?php echo JText::_('COM_KAJOO_MENUCONTENT_THUMB_ADD'); ?></a>
	<input class="input-mini" type="text" name="videotime" id="videotime" value="0">
	<input type="hidden" name="serviceurl" id="serviceurl" value="<?php echo $urlKalturaCDN;?>">
	<input type="hidden" name="videoFulltime" id="videoFulltime"value="<?php echo $api_result->duration;?>">


<div class="thumbnailsCollection ui-widget ui-helper-clearfix">

<ul id="gallerythumbs" class="gallerythumbs ui-helper-reset ui-helper-clearfix">
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
		<li class="ui-widget-content ui-corner-tr" id="<?php echo $thumb->id;?>">
			<img class="collectionthumbs" id="thumb<?php echo $thumb->id;?>" src="<?php echo $thumb_url;?>" style="height:80px;" />
			<a id="<?php echo $thumb->id;?>" href="#" title="Set as default" class="ui-icon ui-icon-star">Set as default</a>
			<a id="<?php echo $thumb->id;?>" href="#" title="Delete this image" class="ui-icon ui-icon-trash">Delete image</a>			
		</li>

		
	
	<?php endforeach;?>
	
</ul>	
</div>


			</div>
        </div>
        
        <div class="tab-pane fade" id="preview">
	        <div class="well">
		        <legend><?php echo JText::_('COM_KAJOO_MENUCONTENT_PREVIEWEMBED'); ?></legend>

		
		<div id="kajooEmbed">
		<?php
		
		$embed = KajooHelper::getUrlEmbed($this->item->partner_id,$this->item->entry_id,$PartnerInfo->defaultPlayer);
		echo $embed;
		
		$embed = str_replace(array("\r\n", "\r"), "\n", $embed);
		$lines = explode("\n", $embed);
		$new_lines = array();
		
		foreach ($lines as $i => $line) {
		    if(!empty($line))
		        $new_lines[] = trim($line);
		}


		
		?>
		</div>
		<legend><?php echo JText::_('COM_KAJOO_MENUCONTENT_PLAYERSCONF'); ?></legend>
		
		<table class="table table-striped tablePlayers">
			<tr>
				<th>ID</th>
				<th><?php echo JText::_('COM_KAJOO_MENUCONTENT_PLAYERSCONFNAME'); ?></th>
				<th><?php echo JText::_('COM_KAJOO_MENUCONTENT_PLAYERSCONFWIDTH'); ?></th>
				<th><?php echo JText::_('COM_KAJOO_MENUCONTENT_PLAYERSCONFHEIGHT'); ?></th>
				<th></th>
				<th></th>
			</tr>
			<?php foreach ($uiConf->objects as $conf):?>	
			
			<?php if($PartnerInfo->defaultPlayer==$conf->id):?>
			<tr id="player<?php echo $conf->id;?>" class="currentPlayer">
			<?php else: ?>
			<tr id="player<?php echo $conf->id;?>">
			<?php endif;?>
				<td><?php echo $conf->id;?></td>
				<td><?php echo $conf->name;?></td>
				<td><?php echo $conf->width;?>px</td>
				<td><?php echo $conf->height;?>px</td>
				<td><a class="btn change" id="<?php echo $conf->id;?>" href="#"><?php echo JText::_('COM_KAJOO_MENUCONTENT_EMBEDCODECHOOSE'); ?></a></td>
				<td><a class="btn setDefault btn-success" id="<?php echo $conf->id;?>" href="#"><?php echo JText::_('COM_KAJOO_MENUCONTENT_EMBEDSETDEFPLAYER'); ?></a></td>
			</tr>
			<?php endforeach;?>			
		</table>
		<input type="hidden" id="embedpartnerId" name="embedpartnerId" value="<?php echo $this->item->partner_id;?>">
		<input type="hidden" id="embedentryId" name="embedentryId" value="<?php echo $this->item->entry_id;?>">
		<input type="hidden" id="itemId" name="itemId" value="<?php echo $this->item->id;?>">
		<input type="hidden" id="currenttab" name="currenttab" value="#review">

		<legend><?php echo JText::_('COM_KAJOO_MENUCONTENT_EMBEDCODE'); ?></legend>
		
		<textarea rows="3" style="width:80%" id="embedarea"><?php echo implode($new_lines);?></textarea>
		
		
		
		
		
			</div>
        </div>
        
        
    </div>
</div>

	
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id;?>" />
	<input type="hidden" name="jform[entry_id]" value="<?php echo $this->item->entry_id;?>" />
	<?php echo JHtml::_('form.token'); ?>
	<div class="clr"></div>

    <style type="text/css">
        /* Temporary fix for drifting editor fields */
        .adminformlist li {
            clear: both;
        }
    </style>
</div>    
</form>
