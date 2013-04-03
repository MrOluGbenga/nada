<?php 
	//current page url
	$page_url=site_url().$this->uri->uri_string();
	
	//total pages
	$pages=ceil($surveys['found']/$surveys['limit']);	
?>

<?php $this->load->view("catalog_search/active_filter_tokens");?>

<?php if (isset($surveys['rows']) && count($surveys['rows'])>0): ?>

<?php		
	//citations
	if ($surveys['citations']===FALSE)
	{
		$citations=array();
	}
	
	//sorting
	$sort_by=$search_options->sort_by;
	$sort_order=$search_options->sort_order;

	//set default sort
	if(!$sort_by)
	{
		if ($this->config->item("regional_search")=='yes')
		{
			$sort_by='nation';
		}
		else
		{
			$sort_by='titl';
		}
	}

	//current page url with query strings
	$page_url=site_url().'/catalog/';		
	
	//page querystring for variable sub-search
	$variable_querystring=get_sess_querystring( array('sk', 'vk', 'vf'),'search');
	
	//page querystring for variable sub-search
	$search_querystring='?'.get_sess_querystring( array('sk', 'vk', 'vf','view','topic','country'),'search');
?>
<input type="hidden"  id="sort_order" value="<?php echo $sort_order;?>"/>
<input type="hidden" id="sort_by" value="<?php echo $sort_by;?>"/>

<table style="width:100%;" border="0" cellpadding="0" cellspacing="0">
<tr>
<td>
<div class="catalog-sort-links">
<?php echo t('sort_results_by');?>:
<?php
  //nation  
  if ($this->config->item("regional_search")=='yes')
  {
    echo create_sort_link($sort_by,$sort_order,'nation',t('country'),$page_url,array('sk','vk','vf') );
    echo "| "; 
  }
   
  //year  
  echo create_sort_link($sort_by,$sort_order,'proddate',t('year'),$page_url,array('sk','vk','vf') ); 
  echo "| ";
   
	//titl
	echo create_sort_link($sort_by,$sort_order,'titl',t('title'),$page_url,array('sk','vk','vf') );

  echo "| ";
   
	//popularity
	echo create_sort_link($sort_by,$sort_order,'popularity',t('Popularity'),$page_url,array('sk','vk','vf') );
	
?>
</div>
</td>
<td align="right">
	<?php if (isset($search_options->vk) && $search_options->vk!=''):?>
     <a href="#" onclick="change_view('v');return false;"><?php echo t('switch_to_variable_view');?></a> |
     <a class="dlg" title="<?php echo t('compare_hover_text');?>" target="_blank" href="<?php echo site_url(); ?>/catalog/compare"><?php echo t('compare');?></a>
    <?php endif;?>
</td>
</tr>
</table>

<div class="pagination">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr valign="middle">
	<td>
        <?php echo sprintf(t('showing_studies'),
            (($surveys['limit']*$current_page)-$surveys['limit']+1),
            ($surveys['limit']*($current_page-1))+ count($surveys['rows']),
            $surveys['found']);
		
			$pager_bar=(pager($surveys['found'],$surveys['limit'],$current_page,5));
		?>
        
   </td>
    <td align="right"><?php echo $pager_bar;?></td>
</tr>
</table>
</div>

<?php foreach($surveys['rows'] as $row): ?>
	<div class="survey-row" data-url="<?php echo site_url(); ?>/catalog/<?php echo $row['id']; ?>" title="<?php echo t('View study');?>">
        	<div class="data-access-icon data-access-<?php echo $row['form_model'];?>" title="<?php echo t("legend_data_".$row['form_model']);?>"></div>
            <h2 class="title">
                <a href="<?php echo site_url(); ?>/catalog/<?php echo $row['id']; ?>"  title="<?php echo $row['titl']; ?>" >
                	<?php echo $row['titl'];?>
                </a>
            </h2>
            <div class="study-country">
				<?php if ($this->regional_search=='yes'):?>
                        <?php echo $row['nation']. ',';?>
                <?php endif;?>
                <?php 
					$survey_year=NULL;
					$survey_year[$row['data_coll_start']]=$row['data_coll_start'];
					$survey_year[$row['data_coll_end']]=$row['data_coll_end'];
					$survey_year=implode('-',$survey_year);
				?>
                <?php echo $survey_year!=0 ? $survey_year : '';?>
			</div>
            <div class="sub-title">
            	<div>
				<?php echo t('by');?> <?php $authenty=json_decode($row['authenty']);?>
                <?php if (is_array($authenty)):?>
                	<?php echo implode(", ",$authenty);?>
                <?php else:?>
                	<?php echo $row['authenty'];?>
                <?php endif;?>
            	</div>
				<?php if (isset($row['repo_title']) && $row['repo_title']!=''):?>
                    <div><?php echo t('catalog_owned_by')?>: <a href="<?php echo site_url('catalog/'.$row['repositoryid'].'/about');?>"><?php echo $row['repo_title'];?></a></div>
                <?php endif;?>
            </div>
			<div class="survey-stats">
            	<span>Created on: <?php echo date('M d, Y',$row['created']);?></span>
                <span>Last modified: <?php echo date('M d, Y',$row['changed']);?></span>
                <span>Views: <?php echo (int)$row['total_views'];?></span>
                <span>Downloads: <?php echo (int)$row['total_downloads'];?></span>
                <?php if (array_key_exists($row['id'],$surveys['citations'])): ?>
                    <span>
                    Citations: <?php echo $surveys['citations'][$row['id']];?>
                    </span>                    
            	<?php endif;?> 
            </div>
		
        <?php if ( isset($row['var_found']) ): ?>
            <div class="variables-found" style="clear:both;">
                    <a class="vsearch" style="outline:none;display:block;" href="<?php echo site_url(); ?>/catalog/vsearch/<?php echo $row['id']; ?>/?<?php echo $variable_querystring; ?>">
                        <?php echo sprintf(t('variables_keywords_found'),$row['var_found'],$row['varcount']);?>
                        <img class="open-close" src="images/next.gif" alt="Expand"/>
                    </a>
                    <span class="vsearch-result"></span>
                   <div class="variable-footer">
                       <input class="btn-style-1 btn-compare-var" type="button" name="compare-variable" value="Compare variables"/> 
                       <span class="var-compare-summary"><?php echo t('To compare, select two or more variables');?></span>
                   </div>
            </div>
            <?php endif; ?>
    </div>    
<?php endforeach;?>

<div class="pagination">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr valign="middle">
	<td>
		<?php echo sprintf(t('showing_studies'),
            (($surveys['limit']*$current_page)-$surveys['limit']+1),
            ($surveys['limit']*($current_page-1))+ count($surveys['rows']),
            $surveys['found']);
		?>
   </td>
    <td align="right">
         <?php echo $pager_bar;?>
    </td>
</tr>
</table>
</div>
<div class="light switch-page-size">
    <?php echo t('select_number_of_records_per_page');?>:
    <span class="btn">15</span>
    <span class="btn">30</span>
    <span class="btn">50</span>
    <span class="btn">100</span>
</div>

<?php else: ?>
	<div style="padding:10px;background:white;border:1px solid gainboro;margin-bottom:20px;"><?php echo t('search_no_results');?></div>
<?php endif; ?>
<?php $this->load->view('tracker/tracker');?>