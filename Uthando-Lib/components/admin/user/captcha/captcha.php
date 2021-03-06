<?php

// no direct access
defined( 'PARENT_FILE' ) or die( 'Restricted access' );
	
$action = split(":", $action);

	switch($action[1]):
		case 'validate':
			if ($form->validate() && $_SESSION['rand_chars']):
				require_once('user/validate/'.$action[0].'.php');
			else:
				$_SESSION['rand_chars'] = $rand_chars;
				
				//$form->addElement('submit', 'submit', 'Submit');
			
				$renderer = new UthandoForm(__SITE_PATH . '/templates/' . $this->get ('admin_config.site.template'));
		
				$renderer->setFormTemplate('form');
				$renderer->setHeaderTemplate('header');
				$renderer->setElementTemplate('element');
				$renderer->setElementTemplate('footer', 'submit');
		
				$form->accept($renderer);
				$this->content .= $renderer->toHtml();
			endif;
			break;
			
		case 'display':
		default:
			$_SESSION['rand_chars'] = $rand_chars;
			
			//$form->addElement('submit', 'submit', 'Submit');
			
			$renderer = new UthandoForm(__SITE_PATH . '/templates/' . $this->get('admin_config.site.template'));
		
			$renderer->setFormTemplate('form');
			$renderer->setHeaderTemplate('header');
			$renderer->setElementTemplate('element');
			$renderer->setElementTemplate('footer', 'submit');
		
			$form->accept($renderer);
			$this->content .= $renderer->toHtml();
			break;
	endswitch;
?>