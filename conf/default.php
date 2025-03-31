<?php
$conf['name'] = 'CAS Login';
$conf['logourl'] = '';

$conf['server'] = '';
$conf['rootcas'] = '/cas';
$conf['port'] = '443';
$conf['samlValidate'] = 0;
$conf['debug'] = 0;
$conf['http_header_real_ip'] = 'HTTP_X_FORWARDED_FOR';

$conf['handlelogoutrequest'] = 0;
$conf['handlelogoutrequestTrustedHosts'] = '';

$conf['autologin'] = 0;

$conf['group_attribut'] = 'groups';
$conf['group_attribut_separator'] = '';
$conf['name_attribut'] = 'displayName';
$conf['mail_attribut'] = 'mail';
$conf['uid_attribut'] = 'uid';
