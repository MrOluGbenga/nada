<style>
.box1{padding:10px; background-color:#FFFFCC;border:1px solid gainsboro;margin-top:10px;margin-bottom:15px;}
span.active-repo{background:gainsboro;padding:5px;}

.copy-studies a.attach, .copy-studies a.remove {
background: green;
padding: 3px;
color: white;
display: block;
-webkit-border-radius: 3px;
-moz-border-radius: 3px;
border-radius: 3px;
float: left;
width: 60px;
text-align: center;
text-transform: capitalize;
}
.copy-studies a.remove {
	background: red;
}
.linked-studies-count{font-weight:bold;}
</style>
<?php
	//set default page size, if none selected
	if(!$this->input->get("ps"))
	{
		$ps=15;
	}
?>
<div class="body-container copy-studies" style="padding:10px;">

<?php $error=$this->session->flashdata('error');?>
<?php echo ($error!="") ? '<div class="error">'.$error.'</div>' : '';?>

<?php $message=$this->session->flashdata('message');?>
<?php echo ($message!="") ? '<div class="success">'.$message.'</div>' : '';?>

<h1 class="page-title">
	<?php echo t('copy_studies_to');?>
    <?php if (isset($active_repo) && $active_repo!=NULL):?>
    	<span class="active-repo"><?php echo $active_repo->title;?></span>
    <?php endif;?>
</h1>

<p><?php echo sprintf(t('msg_copy_studies'),'<img src="themes/admin/bullet-gray.gif" />');?> </p>
<p>&nbsp;</p>
<!-- search form-->
<form class="left-pad" style="margin-bottom:10px;" method="GET" id="catalog-search">	
  <input type="text" size="40" name="keywords" id="keywords" value="<?php echo form_prep($this->input->get('keywords')); ?>"/>
  <select name="field" id="field">
    <option value="all"		<?php echo ($this->input->get('field')=='all') ? 'selected="selected"' : '' ; ?> ><?php echo t('all_fields');?></option>
    <option value="titl"	<?php echo ($this->input->get('field')=='titl') ? 'selected="selected"' : '' ; ?> ><?php echo t('title');?></option>
    <option value="nation"	<?php echo ($this->input->get('field')=='nation') ? 'selected="selected"' : '' ; ?> ><?php echo t('country');?></option>
    <option value="surveyid"><?php echo t('survey_id');?></option>
    <option value="authenty"><?php echo t('producer');?></option>
    <option value="sponsor"><?php echo t('sponsor');?></option>
    <option value="repositoryid"><?php echo t('repository');?></option>
  </select>
  <input type="submit" value="<?php echo t('search');?>" name="search"/>
  <?php if ($this->input->get("keywords")!=''): ?>
    <a href="<?php echo site_url();?>/admin/catalog"><?php echo t('reset');?></a>
  <?php endif; ?>
<br/><br/>

<?php if ($rows): ?>
<?php		
	//pagination 
	$page_nums=$this->pagination->create_links();
	$current_page=($this->pagination->cur_page == 0) ? 1 : $this->pagination->cur_page;

	$sort_by=$this->input->get("sort_by");
	$sort_order=$this->input->get("sort_order");			
	
	//current page url
	$page_url=site_url().'/'.$this->uri->uri_string();
?>
<?php
	if ($this->pagination->cur_page>0) {
		$to_page=$this->pagination->per_page*$this->pagination->cur_page;

		if ($to_page> $this->pagination->total_rows) 
		{
			$to_page=$this->pagination->total_rows;
		}

		$pager=sprintf(t('showing %d-%d of %d')
						,(($this->pagination->cur_page-1)*$this->pagination->per_page+(1))
						,$to_page
						,$this->pagination->total_rows);
	}
	else
	{
		$pager=sprintf(t('showing %d-%d of %d')
				,$current_page
				,$this->pagination->total_rows
				,$this->pagination->total_rows);
	}
?>

<table width="100%">
    <tr>
        <td><div class="linked-studies-count"><?php echo t('studies_linked_count');?>: <?php echo count($linked_studies);?></div></td>
        <td align="right">
            <div class="pagination"><em><?php echo $pager; ?></em>&nbsp;&nbsp;&nbsp; <?php echo $page_nums;?></div>
        </td>
    </tr>
</table>

<div id="surveys">
	<?php $tr_class=""; ?>
    <table class="grid-table" width="100%" cellspacing="0" cellpadding="0">
    <tr class="header">
         	<?php if ($this->config->item("regional_search")=='yes'):?>
			  	<th><?php echo create_sort_link($sort_by,$sort_order,'repositoryid',t('repository'),$page_url); ?></th>
                <th><?php echo create_sort_link($sort_by,$sort_order,'nation',t('country'),$page_url); ?></th>
            <?php endif;?>
			<th><?php echo create_sort_link($sort_by,$sort_order,'title',t('title'),$page_url); ?></th>
			<th><?php echo create_sort_link($sort_by,$sort_order,'changed',t('modified'),$page_url); ?></th>
			<th><?php echo t('actions');?></th>
        </tr>
	<?php foreach($rows as $row): ?>
	    <?php if($tr_class=="") {$tr_class="alternate";} else{ $tr_class=""; } ?>
        <tr class="<?php echo $tr_class;?>">
         	<?php if ($this->config->item("regional_search")=='yes'):?>
            	<td><?php echo strtoupper($row['repositoryid']);?></td>
			  	<td><?php echo $row['nation'];?></td>
            <?php endif;?>
            <td><?php echo $row['titl']; ?> - <?php echo $row['proddate']; ?></td>
            <td><?php echo date($this->config->item('date_format'), $row['changed']); ?></td>
            <td class="">
            	<?php if (!in_array($row['id'],$linked_studies)):?>
            	<a class="attach" data-value="<?php echo $row['id'];?>" href="<?php echo site_url('admin/catalog/do_copy_study/'.$active_repo->repositoryid.'/'.$row['id']);?>"><?php echo t('select'); ?></a>
                <?php else:?>
                <a class="remove" data-value="<?php echo $row['id'];?>" href="<?php echo site_url('admin/catalog/unlink/'.$active_repo->repositoryid.'/'.$row['id']);?>"><?php echo t('deselect') ?></a>
				<?php endif?>                
            </td>
            
        </tr>        
    <?php endforeach;?>
	</table>
<table width="100%">
    <tr>
        <td>
        <?php echo t("select_number_of_records_per_page");?>:
        <?php echo form_dropdown('ps', array(5=>5,10=>10,15=>15,30=>30,50=>50,100=>100,500=>t('ALL')), get_form_value("ps",isset($ps) ? $ps : ''),'id="ps" style="font-size:10px;"'); ?>
        </td>
        <td>    
            <div class="pagination">
                    <em><?php echo $pager; ?></em>&nbsp;&nbsp;&nbsp; <?php echo $page_nums;?>
            </div>
		</td>
    </tr>
</table>
</div>
<?php else: ?>
<?php echo t('no_records_found');?>
<?php endif; ?>
</form>


</div>

<script type='text/javascript'>

jQuery(document).ready(function(){

		//link/unlink studies
		var attach_url="<?php echo site_url('admin/catalog/do_copy_study/'.$active_repo->repositoryid.'/');?>";
		var detach_url="<?php echo site_url('admin/catalog/unlink/'.$active_repo->repositoryid.'/');?>";
	
		$(document.body).on("click","#surveys a.attach", function(event){ 
			$.get($(this).attr("href"));
			$(this).html("<?php echo t('deselect'); ?>");
			$(this).removeClass("attach").addClass("remove");
			var sid=$(this).attr("data-value");
			$(this).attr("href",detach_url+sid);
			return false;
		});
	
		$(document.body).on("click","#surveys a.remove", function(event){ 
			$.get($(this).attr("href"));
			$(this).html("<?php echo t('select'); ?>");	
			$(this).removeClass("remove").addClass("attach");
			var sid=$(this).attr("data-value");
			$(this).attr("href",detach_url+sid);
			return false;
		});
	
});

//page change
$('#ps').change(function() {
  $('#catalog-search').submit();
});
</script>