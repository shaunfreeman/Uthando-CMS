<?php

// no direct access
defined( 'PARENT_FILE' ) or die( 'Restricted access' );

if ($this->authorize()):
	
	$menuBar = array();
		
	if ($this->registry->params['comfirm'] == 'delete'):
		
		$res = $this->remove($ushop->db_name.'tax_codes', 'tax_code_id='.$this->registry->params['id']);
			
		if ($res):
			$params['TYPE'] = 'pass';
			$params['MESSAGE'] = '<h2>Tax code was successfully deleted.</h2>';
		else:
			$params['TYPE'] = 'error';
			$params['MESSAGE'] = '<h2>Tax code could not be deleted due to an error.</h2>';
		endif;	
		// done!
		$menuBar = array('back' => '/ushop/tax/overview');
	else:
		
		$menuBar = array(
			'cancel' => '/ushop/tax/overview',
			'delete' => '/ushop/tax/action-delete_tax_code/id-' . $this->registry->params['id'] . '/comfirm-delete'
		);
		$params['TYPE'] = 'warning';
		$params['MESSAGE'] = 'Are you sure you want to delete this tax code';
	endif;
	
	if (isset($params)):
		$params['CONTENT'] = $this->makeMessageBar($menuBar, 24);
		$this->content .= $this->message($params);
	endif;
else:
	header("Location:" . $this->get('config.server.web_url'));
	exit();
endif;
?>