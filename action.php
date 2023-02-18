<?php

if(!defined('DOKU_INC')) die();

class action_plugin_extranet extends DokuWiki_Action_Plugin {
	
	public function register(Doku_Event_Handler $controller) {
		if($this->_isclientfromextranet()) {
			$controller->register_hook('AUTH_LOGIN_CHECK', 'AFTER', $this, '_disableactions');
			$controller->register_hook('PARSER_CACHE_USE', 'BEFORE', $this, '_createextranetcache');
			$controller->register_hook('IO_WIKIPAGE_READ', 'AFTER', $this, '_displayhidemessageifrestricted');
			$controller->register_hook('MEDIA_SENDFILE', 'BEFORE', $this, '_hidemediasifrestricted');
		}
	}
	
	function _isclientfromextranet() {
		$client_ip = $_SERVER[$this->getConf('server_ip_key')];
		
		if(!empty($this->getConf('extranet_ip_list'))) {
			$restricted_ip_list = explode(',', $this->getConf('extranet_ip_list'));
			foreach($restricted_ip_list as $restricted_ip) {
				if($client_ip === $restricted_ip) {
					return true;
				}
			}
		}
		
		if(!empty($this->getConf('extranet_ip_regex'))) {
			preg_match($this->getConf('extranet_ip_regex'), $client_ip, $matches);
			return !empty($matches[0]);
		}
		
		return false;
	}
	
	function _iscontentrestrictedfromextranet($content) {
		return strpos($content, '~~NOEXTRANET~~') !== false;
	}
	
	function _isnamespacerestrictedfromextranet() {
		global $ID;
		
		if(!empty($this->getConf('hide_regex'))) {
			preg_match($this->getConf('hide_regex'), $ID, $matches);
			return !empty($matches[0]);
		}
		
		return false;
	}
	
	function _ismediarestrictedfromextranet($media) {
		if(!empty($this->getConf('hide_regex'))) {
			preg_match($this->getConf('hide_regex'), $media, $matches);
			return !empty($matches[0]);
		}
		
		return false;
	}
	
    function _disableactions(Doku_Event &$event, $param) {
		if(!empty($this->getConf('disable_actions'))) {
			global $conf;
			$conf['disableactions'] = (!empty($conf['disableactions'])? $conf['disableactions'].',' : '').$this->getConf('disable_actions');
		}
    }
	
    function _createextranetcache(Doku_Event &$event, $param) {
		$cache = $event->data;
        $cache->key .= '#extranet';
        $cache->cache = getCacheName($cache->key, $cache->ext);
    }
	
    function _displayhidemessageifrestricted(Doku_Event &$event, $param) {
		$isPageRestricted = $this->_iscontentrestrictedfromextranet($event->result)
			|| $this->_isnamespacerestrictedfromextranet();
		
		if($isPageRestricted) {
			$result = '';
			
			if($this->getConf('preserve_first_title')) {
				$titlePattern = '/(?:^|\v)(={2,6}.+={2,})(?:\v|$)/';
				preg_match($titlePattern, $event->result, $matches);
				
				if(!empty($matches[0])) {
					$result .= $matches[0]."\r\n";
				}
			}
			$result .= $this->getConf('message_prefix').$this->getLang('hidden_message').$this->getConf('message_suffix');
			
			$event->result = $result;
		}
    }
	
    function _hidemediasifrestricted(Doku_Event &$event, $param) {
		$isMediaRestricted = $this->getConf('hide_files') && $this->_ismediarestrictedfromextranet($event->data['media']);
		
		if($isMediaRestricted) {
			$event->data['file'] = dirname(__FILE__).'/images/restricted.png';
			$event->data['status'] = 403;
			$event->data['mime'] = 'image/png';
			$event->data['download'] = false;
		}
    }
}
