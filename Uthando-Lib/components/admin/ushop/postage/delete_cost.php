<?php

// no direct access
defined( 'PARENT_FILE' ) or die( 'Restricted access' );

if ($this->authorize()):
	
	$menuBar = array();
		
	if ($this->registry->params['comfirm'] == 'delete'):
		
		$res = $this->remove($ushop->db_name.'post_costs', 'post_cost_id='.$this->registry->params['id']);
			
		if ($res):
			$params['TYPE'] = 'pass';
			$params['MESSAGE'] = '<h2>Post cost was successfully deleted.</h2>';
		else:
			$params['TYPE'] = 'error';
			$params['MESSAGE'] = '<h2>Post cost could not be deleted due to an error.</h2>';
		endif;
		// done!
		$menuBar = array('back' => '/ushop/postage/overview');
	else:
		
		$menuBar = array(
			'cancel' => '/ushop/postage/overview',
			'delete' => '/ushop/postage/action-delete_cost/id-' . $this->registry->params['id'] . '/comfirm-delete'
		);
		$params['TYPE'] = 'warning';
		$params['MESSAGE'] = 'Are you sure you want to delete this post cost';
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