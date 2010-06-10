
<?php

// no direct access
defined( 'PARENT_FILE' ) or die( 'Restricted access' );

class Admin_Uthando extends Uthando {
	
	private $authorized = FALSE;
	public $lang = 'en';
	
	public function __construct($registry)
	{
		parent::__construct($registry);
		$this->loadLang();
	}
	
	public function getPath()
	{
		return $this->registry->path;
	}
	
	public function authorize()
	{
		if (!$this->authorized) $this->getAuthorize();
		return $this->authorized;
	}
	
	private function getAuthorize()
	{
		if (isset($_SESSION['user_id']) && isset($_SESSION['username']) && isset($_SESSION['user_group']) && UthandoUser::checkUser()):
		
			// Query the database.
			$row = $this->getResult(
				'user_id, username, user_group',
				$this->registry->user.'users',
				$this->registry->user.'user_groups',
				array(
					'where' =>'user_id='.$_SESSION['user_id'],
					'and' => "user_group != 'registered'"
				),
				false
			);
	
			if ($row):
				if ($row->username == $_SESSION['username'] && $row->user_id == $_SESSION['user_id'] && $row->user_group == $_SESSION['user_group']):
					$this->upid = $this->setUserPermissions($_SESSION['user_group']);
					if ($this->upid > 0 && $this->upid < 4) $this->authorized = true;
				endif;
			endif;
		endif;
	}
	
	private function setUserPermissions($group)
	{
		switch ($group):
			case 'super administrator':
				return 1;
				break;
			case 'administrator':
				return 2;
				break;
			case 'manager':
				return 3;
				break;
			default:
				return false;
				break;
		endswitch;
	}
	
	public function getLangMessage($key)
	{
		return (isset($this->messages[$this->registry->component][$key])) ? $this->messages[$this->registry->component][$key] : null;
	}
	
	private function loadLang()
	{
		$js = file_get_contents(LANG.$this->lang.'.json');
		$this->messages = json_decode($js, true);
	}
	
	// seperate this out to it's own HTML class.
	public function makeMessageBar($items, $icon_size)
	{
		if (is_array($items)):
			$menuBar = '<div id="messageBarWrap"><div id="messageBar" class="center">';
			foreach ($items as $key => $value):
				
				if ($key == 'seperator'):
					$menuBar .= '<div class="seperator"></div>';
				else:
					$menuBar .= '<a id="'.$key.'-'.$icon_size.'" class="Tips button" href="'.$value.'" title="'.ucwords(str_replace('_', ' ', $key)).'" rel="'.$this->getLangMessage($key).'"><span>'.ucwords(str_replace('_', ' ', $key)).'</span></a>';
				endif;
			endforeach;
			$menuBar .= '<div class="both"><!-- --></div>';
			$menuBar .= '</div></div>';
			return $menuBar;
		else:
			return false;
		endif;
	}
	
	// seperate this out to it's own HTML class.
	public function makeToolbar($items, $icon_size)
	{
		if (is_array($items)):
			$menuBar = '<div id="menuToolbarWrap">';
			$menuBar .= '<img width="8" height="53" id="scrollLeft" alt="scroll left" src="/images/left_scroll.png"/>';
			$menuBar .= '<div id="menuToolbar">';
			$menuBar .= '<ul id="iconMenuStrip">';
			foreach ($items as $key => $value):
				
				if ($key == 'seperator'):
					//$menuBar .= '<div class="seperator"></div>';
				else:
					$menuBar .= '<li><a id="'.$key.'-'.$icon_size.'" class="Tips button iconImgs" href="'.$value.'" title="'.ucwords(str_replace('_', ' ', $key)).'" rel="'.$this->getLangMessage($key).'"><span class="iconCaptions">'.ucwords(str_replace('_', ' ', $key)).'</span></a></li>';
				endif;
			endforeach;
			$menuBar .= '</ul>';
			$menuBar .= '<div class="both"><!-- --></div>';
			$menuBar .= '</div>';
			$menuBar .= '<img width="8" height="53" id="scrollRight" alt="scroll right" src="/images/right_scroll.png"/>';
			$menuBar .= '</div>';
			return $menuBar;
		else:
			return false;
		endif;
	}
	
	public function message($params)
	{
		$message = file_get_contents(TEMPLATES . $this->get('admin_config.site.template') . '/html/message.html');
		return $this->templateParser($message, $params, '<!--{', '}-->');
	}
	
	public function array_search_recursive($needle, $haystack, $strict=false, $path=array())
	{
		if ( !is_array($haystack) ) return false;
 
		foreach ( $haystack as $key => $val ):
			if ( is_array($val) && $subPath = UthandoAdmin::array_search_recursive($needle, $val, $strict, $path) ):
				$path = array_merge($path, array($key), $subPath);
				return $path;
			elseif ( (!$strict && $val == $needle) || ($strict && $val === $needle) ):
				$path[] = $key;
				return $path;
			endif;
		endforeach;
		return false;
	}
	
}
?>