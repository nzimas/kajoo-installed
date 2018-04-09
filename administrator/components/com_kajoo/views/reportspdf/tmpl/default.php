<?php 
/**
 *   kajoo
 *   Authors: Juan Dapena Paz (juan@bittingbits.com), Miguel Puig (miguel@freebandtech.com)
 *   Copyright (C) 2012
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

ob_start(); 
	// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<style>
@page { margin: 0in 0in 0in 0in;} 
body{
	font-family: Helvetica, sans-serif;
}
#header{
	width: 100%;
	background: #254787;
	color: #fff;
	height: 100px;
	margin-bottom: 15px;
}
#header .logo{
	width: 200px;
	height: 90px;
	position: absolute;
	left:20px;
	top:5px;
}
#header h1{
	font-size: 20px;
	margin-right: 20px;
	margin-bottom: 0px;
	margin-top: 5px;
	position: absolute;
	right:10px;
	top:10px;

	text-align: right;
}
div.contactinfo{
	text-align: right;
	position: absolute;
	right:10px;
	top:45px;
	margin-right: 20px;
	line-height: 150%;
	font-size: 12px;
	font-weight: normal;
}
div.contactinfo strong{
	color: #8FAEE8;
	font-size: 1.4em;
}

div.content_inner{
	padding: 20px;
}
.content {
  margin-bottom:20px;
  margin-top: 20px;
  height: 225px;
  background-color: #fafafa;
  width: 100%;
}
div.prog_image{
	float: left;
}
span.titprog{

	font-weight: bold;
	font-size: 20px;
	color: #254787;
}
div.prog_desc{
	font-size: 12px;
	margin-top: 10px;
	line-height: 145%;

}
table.tableitems td{
	vertical-align: top;
}
.content.second{
	background: #fff
}
div.clear{
	clear: both;
}
td.filters{
	border-left: 1px dotted #ccc;
	color: #222;
	width: 240px;
	padding-left: 18px;
}
td.middle{

	padding-right: 20px;
	width: 400px;
}
td.image{
	width: 200px;
}
tr.field{
	margin-bottom: 10px;
	margin-left: 10px;
	margin-top: 10px;
	text-transform: uppercase;
	font-size: 12px;
	font-weight: bold;
}
td.field_value{
	text-transform:none;
}
span.filters_table{
	margin-left: 20px;
	margin-top: 15px;
	text-transform: uppercase;
	font-size: 10px;
	font-weight: bold;
}
td.field_name{
	text-align: left;
	padding-right: 5px;
}
span.linea{
	display: block;
	margin-right: 15px;
	margin-bottom: 10px;
	text-align: left;


}
span.line_filt{
	font-weight: normal;
	color: #555;
}
span.rownew{
	
	display: block;
}
img.itemimg{
	padding: 3px;
	border: 1px solid #f4f4f4;
	background: #fff;
}

</style>
</head>
<body>
	<div class="main_cover"></div>

			<?php foreach ($this->titles as $key=>$title): ?>
			
			
			
			<?php			
				if ($key % 2 == 0):
					$divisiblebytwo = 1;
				else:
					$divisiblebytwo = 0;
				endif;
				
				
				if($title->partner->url=='')
					$title->partner->url='http://kaltura.com';
				
				
				if($this->showThumb==1):

				if (@GetImageSize($title->partner->url."/p/100/sp/10000/thumbnail/entry_id/".$title->entry_id."/width/220/height/130/bgcolor/000000/type/2")) {
					$default_image = $title->partner->url."/p/100/sp/10000/thumbnail/entry_id/".$title->entry_id."/width/220/height/130/bgcolor/000000/type/2";
				} else {
					$url = JURI::root();
					$path_image = $url.'components/com_kajoo/assets/images/default_thumb1.png';
					$default_image =$path_image;
				}
			else:
				
					$url = JURI::root();
					$path_image = $url.'components/com_kajoo/assets/images/default_thumb1.png';
					$default_image =$path_image;
				
			endif;
				
			?>
			
			
		<div class="content <?php if($divisiblebytwo==1): echo "first"; else: echo "second"; endif;?>">
		<div class="content_inner">
				
			<table width="100%" border="0" class="tableitems">
			<tr>

				<td class="image">
					<div class="prog_image"><img src="<?php echo $default_image;?>" width="200" class="itemimg"/></div>		
			    </td>

			    <td class="middle">
			    	<?php if($this->showTitle==1):?>
			    		<span class="titprog"><?php echo utf8_decode($title->name);?></span><br />
			    	<?php endif;?>
			    	<div class="prog_desc"><?php echo $description = utf8_decode(KajooHelper::limit_words($title->description,90)); ?></div>
			    </td>

			    <td class="filters">
			    	<span class="filters_table">
			    		<?php if($this->showPartner==1):?>
			    			<span class="linea">
					    		<?php echo JText::_('COM_KAJOO_TITLE_PARTNERS_SINGLE');?>: <span class="line_filt"><?php echo $title->partner->name;?> </span>
				    		</span>
			    		<?php endif;?>
			    		<?php 
					    	$a=1;
					    	
					    	if(is_array($title->fields)):
					    	foreach ($title->fields as $kf=>$field): 
					    	?>
				    		<span class="linea">
					    		<?php echo $field['fieldName'];?>: <span class="line_filt"><?php echo $field['value'];?> </span>
				    		</span>

							<?php
							
							$a++;						
							 endforeach;
							 endif;
							  ?>	
			    	</span>
			    </td>

			</tr>
			</table>
		</div>
	</div>	
			<?php endforeach;?>
	
</body>
</html>


<?php   
 $name_file = 'catalogue_'.JFactory::getDate()->Format('d_m_y_i_m_s');
  $htmlexport = ob_get_clean(); 
 ?>

<?php
require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'dompdf'.DS.'dompdf_config.inc.php'); 	
    	$dompdf = new DOMPDF();
    	$dompdf->load_html($htmlexport);
    	$dompdf->set_paper('a4', 'landscape');
    	$dompdf->render();
    	file_put_contents(JPATH_ROOT."/tmp/".$name_file.".pdf", $dompdf->output());
    	echo JURI::root()."tmp/".$name_file.".pdf";

?>