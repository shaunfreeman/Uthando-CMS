<?php

// no direct access
defined( 'PARENT_FILE' ) or die( 'Restricted access' );

if ($this->authorize()):
	
	$doc = new HTML_Element();
	
	$sql = "
		SELECT blog_id, category_id, page_id
		FROM ".$this->registry->core."blog
	";
	$result = $this->registry->db->query($sql);
	
	$num_pages = count($result);
	
	$menuBar = array(
		'new_page' => '/content/new',
	);
	
	$this->content .= ($num_pages <= $this->registry->settings['pages'] || $this->registry->settings['pages'] == -1) ? $this->makeToolbar($menuBar, 24) : $this->message(array('TYPE' => 'info', 'MESSAGE' => '<h2>You have reach your page limit. To add more pages please contact your administrator.</h2>'));

	if ($num_pages > 0):
		
		$c = 0;
		$data = array();
		
		foreach ($result as $row):
			
			$data[$c] = array(
				$row->page,
				$row->mdate,
				'<a href="/content/edit/id-'.$row->page_id.'"  style="text-decoration:none;" ><img src="/images/24x24/Edit3.png" class="Tips" title="Edit Page" rel="Click to edit the '.$row->page.' page contents" /></a>',
				
				'<a href="/content/delete/id-'.$row->page_id.'" ><img src="/images/24x24/DeleteRed.png" class="Tips" title="Delete Page" rel="Click to delete the '.$row->page.' page" /></a>'
			);
			
			$c++;
		endforeach;
		
		$header = array('Page Name', 'Last Modified', '', '');
		
		$table = $this->dataTable($data, $header);
		
		$div = $doc->createElement('div', $table->toHtml(), array('id' => 'tableWrap'));
		$doc->appendChild($div);
		$this->content .= $doc->toHTML();
		
	else:
		$this->content .= '<h3>No Content Yet</h3>';
	endif;
	
endif;
?>