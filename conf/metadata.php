<?php
$meta['server_ip_key']        = array('string', '_caution' => 'danger');
$meta['extranet_ip_list']     = array('string', '_caution' => 'danger');
$meta['extranet_ip_regex']    = array('string', '_pattern' => '/^($|\/.*\/.*$)/', '_caution' => 'danger');
$meta['hide_regex']           = array('string', '_pattern' => '/^($|\/.*\/.*$)/', '_caution' => 'danger');
$meta['hide_files']           = array('onoff');
$meta['disable_actions']      = array('multicheckbox', '_choices' => array('admin', 'edit', 'preview', 'save', 'revisions', 'diff', 'export_raw', 'export_xhtml', 'export_xhtmlbody', 'permalink', 'printpage', 'login', 'register', 'logout', 'recent', 'media', 'sendmail'), '_caution' => 'danger');
$meta['preserve_first_title'] = array('onoff');
$meta['message_prefix']       = array('string');
$meta['message_suffix']       = array('string');
