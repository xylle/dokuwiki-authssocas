<?php
$lang['name'] = 'Nom du service d\'authentification';
$lang['logourl'] = 'URL du logo pour le service CAS. Si l\'URL est en HTTPS, elle doit être relative ou en HTTPS.';

$lang['server'] = 'FQDN du serveur CAS (ex. cas.example.com)';
$lang['port'] = 'Port du serveur CAS (ex. 443)';
$lang['rootcas'] = 'Chemin du service CAS (ex. /cas)';
$lang['logfileuser'] = 'Nom du fichier journal. S\'il est défini, les connexions des utilisateurs y seront enregistrées. Le fichier est stocké dans le dossier de journaux. L\'heure est en UTC.';
$lang['http_header_real_ip'] = 'Nom de l\'en-tête HTTP contenant l\'IP réelle du client. Utile si DokuWiki est derrière un reverse proxy.';

$lang['handlelogoutrequest'] = 'Activer la gestion des demandes de déconnexion CAS (Single Logout)';
$lang['handlelogoutrequestTrustedHosts'] = 'Hôtes de confiance pour les demandes de déconnexion (FQDN ou IP), séparés par des virgules. Une résolution DNS inverse est effectuée. Attention si vous êtes derrière un proxy inverse.';

$lang['autologin'] = 'Activer la connexion automatique si l\'utilisateur est authentifié CAS';

$lang['group_attribut'] = 'Attribut CAS contenant la liste des groupes de l\'utilisateur.';
$lang['group_attribut_separator'] = 'Par défaut, l\'attribut de groupe doit être un tableau. Si c\'est une chaîne, indiquez ici le séparateur utilisé (ex. virgule, point-virgule, etc.).';
$lang['name_attribut'] = 'Attribut CAS contenant le nom complet de l\'utilisateur.';
$lang['mail_attribut'] = 'Attribut CAS contenant l\'adresse e-mail de l\'utilisateur.';
$lang['uid_attribut'] = 'Attribut CAS contenant l\'identifiant unique (UID) de l\'utilisateur.';

$lang['cacert'] = 'Contenu du certificat de l\'autorité ayant signé le certificat du serveur CAS.';
$lang['debug'] = 'Activer le mode débogage pour phpCAS (journalisation détaillée).';
