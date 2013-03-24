<?php
/*
* A list of citations attached to a survey
*
*/
?>

<?php if (isset($related_citations) && ($related_citations!==FALSE)): ?>
<?php $this->load->library('chicago_citation'); ?>

<table class="grid-table" cellpadding="0" cellspacing="0" id="related-citations-table">
<tbody>
	<tr align="left" valign="top">
    	<td colspan="2"><span class="info">Click on <i class="icon-remove-sign"></i> to remove citations</span>
        <div class="page-links" style="float:right"><a class="attach_citations" href="javascript:void(0);">Attach Citations</a></div></td>
    </tr>    

<?php foreach ($related_citations as $citation):?>
	<tr align="left" valign="top">
    	<td nowrap="nowrap">
        	<a href="<?php echo site_url('admin/related_citations/remove/'.$survey_id.'/'.$citation['id']);?>" class="icon-remove-sign link remove" title="Remove">&nbsp;</a>
        </td>
		<td><?php echo $this->chicago_citation->format($citation,'journal');?></td>
	</tr>
<?php endforeach; ?>
</tbody>
</table>    

<?php else:?>
<div><a class="add_survey" href="javascript:void(0);"><?php echo t('no_related_citations_click_here_to_add');?></div></div>
<?php endif;?>