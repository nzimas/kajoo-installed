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
JHtml::_('behavior.formvalidation');
// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_kajoo/assets/css/kajoo.css');
$document->addScript(JURI::base() . 'components/com_kajoo/assets/js/upload.js');



$app = JFactory::getApplication();

?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'field.cancel' || document.formvalidator.isValid(document.id('field-form'))) {
			Joomla.submitform(task, document.getElementById('field-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}

function bytesToSize(bytes) {
    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (bytes == 0) return 'n/a';
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
};


$(function () {
    $("#partnerSelector").live("change keyup", function () {
        $("#formPartner").submit();
    });
});
</script>






<?php if(!empty( $this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
<div id="ajaxLoader"><div id="spin"></div></div>

<?php $partnerId = JRequest::getVar('partnerId', 0, 'GET');?>

<form id="formPartner" action="<?php echo JRoute::_('index.php'); ?>" method="get">

	<legend>Select Partner</legend>
	
		<select name="partnerId" id="partnerSelector">
			<option value="0">Select partner</option>
		<?php foreach ($this->partners as $partner):?>
			<option value="<?php echo $partner->id;?>" <?php echo $x = ($partnerId == $partner->id) ? 'selected="selected"': '';?>><?php echo $partner->name;?></option>
		<?php endforeach;?>
		</select>
		<input type="hidden" name="view" value="upload">
		<input type="hidden" name="option" value="com_kajoo">
</form>


<?php


if($partnerId==0):
?>
	<div class="alert">
		<?php echo JText::_('COM_KAJOO_UPLOADALERTSELPID'); ?>
	</div>

<?php

else:
?>


<hr>
<?php

// Connect to Kaltura API
$PartnerInfo = KajooHelper::getPartnerInfo($partnerId);
//$kClient = KajooHelper::getKalturaClient($PartnerInfo->partnerid, $PartnerInfo->administratorsecret, true);

?>

	


<?php

	
	//define constants
	define("KALTURA_PARTNER_ID", $PartnerInfo->partnerid);
	define("KALTURA_PARTNER_SERVICE_SECRET", $PartnerInfo->usersecret);
	
	//define session variables
	$partnerUserID          = 'ANONYMOUS';
	
	//construct Kaltura objects for session initiation
	$config           = new KalturaConfiguration(KALTURA_PARTNER_ID);

	
	$kClient = KajooHelper::getKalturaClient($PartnerInfo->partnerid, $PartnerInfo->administratorsecret, true,$PartnerInfo->url);
		
		if($PartnerInfo->url==''):
			$url = 'http://www.kaltura.com';
		else:
			$url = $PartnerInfo->url;
		endif;
	$filename = JPATH_BASE.'/components/com_kajoo/assets/confileKajoo.xml';
	$fh = fopen($filename, 'r');
	$confXML = fread($fh, filesize($filename));
	
	$uiConf = new KalturaUiConf();
	$uiConf->objType = KalturaUiConfObjType::CONTRIBUTION_WIZARD;
	$uiConf->creationMode = KalturaUiConfCreationMode::ADVANCED;
	$uiConf->swfUrl = "/flash/kcw/v2.1.2/ContributionWizard.swf";
	$uiConf->name = "Custom UI Conf";
	$uiConf->confFile = $confXML;

	try
	{
		$uiConf = $kClient->uiConf->add($uiConf);
	}
	
	catch(Exception $ex)
	{
		echo $ex;
	}

		try
		{
			$ks = $kClient->session->start(KALTURA_PARTNER_SERVICE_SECRET, $partnerUserID, KalturaSessionType::USER);
			
			//Prepare variables to be passed to embedded flash object.
				$flashVars = array();
				$flashVars["uid"]               = $partnerUserID;
				$flashVars["partnerId"]         = KALTURA_PARTNER_ID;
				$flashVars["ks"]                  = $ks;
				$flashVars["afterAddEntry"]     = "onContributionWizardAfterAddEntry";
				$flashVars["close"]       		= "onContributionWizardClose";
				$flashVars["showCloseButton"]   = false; 
				$flashVars["Permissions"]       = 1;
				
				
				?>
				<form action="<?php echo JRoute::_('index.php?option=com_kajoo'); ?>" method="post" name="adminForm" id="field-form" class="form-validate">
				<div id="kcw"></div>
				<script type="text/javascript">
				var params = {
				        allowScriptAccess: "always",
				        allowNetworking: "all",
				        wmode: "opaque"
				};
				// php to js
				var flashVars = <?php echo json_encode($flashVars); ?>;
				
				swfobject.embedSWF("<?php echo $url;?>/kcw/ui_conf_id/<?php echo $uiConf->id;?> ", "kcw", "680", "360", "9.0.0", "expressInstall.swf", flashVars, params);
				</script>
				
				<!--implement callback scripts-->
				<script type="text/javascript">
				function onContributionWizardAfterAddEntry(entries) {

			            alert("<?php echo JText::_('COM_KAJOO_UPLOAD_OK'); ?>");
			            window.location.replace("<?php echo JRoute::_('index.php?option=com_kajoo&controller=contents_partners&task=contents.sync&partnerid='.$partnerId,false); ?>");
			  
				        
				}
				</script>
				<script type="text/javascript">
				function onContributionWizardClose() {
				        //alert("Thank you for using Kaltura ontribution Wizard");
				}
				</script>
					
					
				</div>	    
				
				
					<input type="hidden" name="task" value="" />
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
			
		<?php
		}
		catch(Exception $ex)
		{
			?>
			<div class="alert alert-error">
			<?php echo $ex;?>
				<?php echo JText::_('COM_KAJOO_UPLOADALERTSELPIDNOTCONFIG'); ?>
			</div>

			<?php
		}
	
		?>
<?php endif;?>
