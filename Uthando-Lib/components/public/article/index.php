<?php

// no direct access
defined( 'PARENT_FILE' ) or die( 'Restricted access' );

if ($this->registry->action):
	
	if (!is_numeric($this->registry->action)):
		$page = "WHERE page='".addslashes(str_replace('-', ' ', $this->registry->action))."'";
	else:
		$page = "WHERE page_id='".$this->registry->action."'";
	endif;
	
	//DATE_FORMAT(registration_date, '%W %D %M %Y') AS date
	$sql = "
		SELECT page_id, page, content, DATE_FORMAT(cdate, '%W, %d %M %Y %h:%i %p') AS cdate, DATE_FORMAT(mdate, '%W, %d %M %Y %h:%i %p') AS mdate, params
		FROM pages
		$page
	";
	
	$row = $this->registry->db->getRow($sql);
		
	if ($row):
			
		$row->params = parse_ini_string($row->params, true);
		
		$page = ($row->params['show_title'] == 0) ? null : $row->page;
		$cdate = ($row->params['show_cdate'] == 0) ? null : $row->cdate;
		$mdate = ($row->params['show_mdate'] == 0) ? null : $row->mdate;
		
		foreach ($row->params['metadata'] as $key => $value):
			if ($value):
				$this->registry->template->settings['metadata'][$key] = $row->params['metadata'][$key];
			endif;
		endforeach;
		
		$this->setTitle($row->page . ' | ' . $this->get('config.server.site_name'));
		$this->registry->page_title = $row->page;
		
		$this->addContent($this->displayContentpane(htmlspecialchars($row->content),$page,$cdate,$mdate));
	else:
		$this->registry->Error('404 Page NOT Found', $this->registry->path);
	endif;
endif;
?>