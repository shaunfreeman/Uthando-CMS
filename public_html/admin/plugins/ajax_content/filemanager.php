<?php

ob_start('ob_gzhandler');
	
// Set flag that this is a parent file.
define( 'PARENT_FILE', 1 );

$site_path = realpath('../../');
define ('__SITE_PATH', $site_path);

/*{START_PHP_INI_PATH}*/
define ('__PHP_PATH', realpath(__SITE_PATH.'/../../uthando/php'));
/*{END_PHP_INI_PATH}*/

// Set include paths.
$ini_path = ini_get('include_path') .
	PATH_SEPARATOR . __PHP_PATH .
	PATH_SEPARATOR . __PHP_PATH . '/PEAR' .
	PATH_SEPARATOR . __PHP_PATH . '/Uthando' .
	PATH_SEPARATOR . __PHP_PATH . '/Uthando/Admin' .
	PATH_SEPARATOR . __PHP_PATH . '/Uthando/Admin/FileManager' .
	PATH_SEPARATOR . __PHP_PATH . '/Uthando/functions' .
	PATH_SEPARATOR . __SITE_PATH . '/modules' .
	PATH_SEPARATOR . __SITE_PATH . '/components';

set_include_path($ini_path);

// Include functions.
require_once('functions.php');

$registry = new Admin_Registry(true);

require('Dbug/FirePHP.class.php');

$registry->firephp = FirePHP::getInstance(true);

$registry->setSite(realpath(__SITE_PATH.'/../../uthando/ini/uthandoSites.ini.php'));

$registry->loadIniFiles(array('admin_config' => 'uthandoAdmin', 'config' => 'uthando'));
$registry->setDefaults();

if (isset($_GET['session'])):
	$registry->sessionId = $_GET['session'];
elseif (isset($_POST['session'])):
	$registry->sessionId = $_POST['session'];
endif;

try
{
		
	$registry->db = new DB_Core($registry);

	$registry->session = new Session($registry);
	//UthandoUser::setUserInfo();
	
	// Load component.

	//user_agent|s:15:"Shockwave Flash";remote_addr|s:9:"127.0.0.1"
	
	$dir = $_SERVER['DOCUMENT_ROOT'].'/../userfiles/'.$_POST['folder'].'/';
			
	function UploadIsAuthenticated($get){
			global $registry;
			if(!empty($get['session'])) {
				if (isset($_SESSION['user_id']) && isset($_SESSION['username']) && $_SERVER['HTTP_USER_AGENT'] == 'Shockwave Flash') {

					// Query the database.
					$row = $registry->db->getResult(
						'user_id, username, user_group',
						$registry->user.'users',
						$registry->user.'user_groups',
						array(
							'where' =>'user_id='.$_SESSION['user_id'],
							'and' => array("username='".$_SESSION['username']."'", "user_group != 'registered'")
						),
						false
					);
					$dir = $_SERVER['DOCUMENT_ROOT'].'/../userfiles/'.$_POST['folder'].'/';
					if (count($row) == 1) return true;
					return false;
				} else {
					return false;
				}
			}

			return false;
		}

		$browser = new File_Manager($registry, array(
			'baseURL' => $registry->get('config.server.web_url').'/',
			'directory' => $dir,
			'assetBasePath' => $_SERVER['DOCUMENT_ROOT'].'/templates/admin/images/FileManager',
			'move' => true,
			'create' => true,
			'upload' => true,
			'destroy' => true,
			'filter' => (is_string($_POST['filter'])) ? $_POST['filter'].'/' : null
		));

		$browser->fireEvent(!empty($_GET['event']) ? $_GET['event'] : null);
}
catch (PDOException $e)
{
	$registry->Error ($e->getMessage());
}

$registry->db = null;
	
unset ($uthando, $registry);
	
ob_end_flush();

?>