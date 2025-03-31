<?php
$lang['name'] = 'Name of the authentication service';
$lang['logourl'] = 'URL of the logo for the CAS service. If using HTTPS, the URL must be either relative or use HTTPS.';

$lang['server'] = 'FQDN of the CAS server (e.g. cas.example.com)';
$lang['port'] = 'Port of the CAS server (e.g. 443)';
$lang['rootcas'] = 'Path of the CAS service (e.g. /cas)';
$lang['logfileuser'] = 'Log file name. If defined, user logins will be recorded in this file located in the log directory. Time is in UTC.';
$lang['http_header_real_ip'] = 'HTTP header containing the real client IP. Use this if DokuWiki is behind a reverse proxy.';

$lang['handlelogoutrequest'] = 'Handle CAS logout requests (Single Logout)';
$lang['handlelogoutrequestTrustedHosts'] = 'Trusted hosts for CAS logout requests (FQDN or IP), separated by commas. Performs reverse DNS resolution. Be careful if behind a reverse proxy.';

$lang['autologin'] = 'Enable automatic login if the user is already authenticated with CAS';

$lang['group_attribut'] = 'CAS attribute containing the user\'s group list.';
$lang['group_attribut_separator'] = 'By default, the group attribute should be an array. If it is a string, specify the separator used (e.g. comma, semicolon, etc.).';
$lang['name_attribut'] = 'CAS attribute containing the user\'s full name.';
$lang['mail_attribut'] = 'CAS attribute containing the user\'s email address.';
$lang['uid_attribut'] = 'CAS attribute containing the user\'s unique identifier (UID).';

$lang['cacert'] = 'Content of the certificate authority that signed the CAS server\'s certificate.';
$lang['debug'] = 'Enable debug mode for phpCAS (detailed logging).';
