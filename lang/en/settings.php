<?php
$lang['name'] = 'CAS login service name';
$lang['logourl'] = 'URL to a logo for the CAS service. If serving login pages via HTTPS, make sure this is either relative (/...) or an HTTPS URL.';

$lang['server'] = 'CAS server hostnamme (cas.example.com)';
$lang['port'] = 'CAS server port (443)';
$lang['rootcas'] = 'CAS server uri (/cas)';

$lang['logfileuser'] = 'Log file name. If defined, log connections. The file is located in the logs folder. Time is in UTC';
$lang['http_header_real_ip'] = 'HTTP header with the real client\'s IP address. Use this if dokuwiki instance is reverse proxy-fied';

$lang['samlValidate'] = '??';



$lang['handlelogoutrequest'] = 'handle CAS logout requests';
$lang['handlelogoutrequestTrustedHosts'] = 'trusted hosts for logout requests(FQDN or IP), comma separated. Performs reverse DNS resolution. Be careful if you are behind a reverse proxy.';

$lang['autologin'] = 'login automatically';

$lang['group_attribut'] = 'CAS attribute containing list of user groups.';
$lang['group_attribut_separator'] = 'By default, the groupe attribute is supposed to be an array. If the attribute is a string containing all the groups, specify here the group separator (i.e. comma, colum, semicolon, ...)';
$lang['name_attribut'] = 'CAS attribute containing user name';
$lang['mail_attribut'] = 'CAS attribute containing user mail';
$lang['uid_attribut'] = 'CAS attribute containing user uid';

$lang['cacert'] = 'Authority that validated the CAS server certificate (put content of certificat)';

//$lang['force_redirect'] = 'Redirect user to CAS if permission is required or ACT=login (no login message)';

$lang['debug'] = 'View message debug';
