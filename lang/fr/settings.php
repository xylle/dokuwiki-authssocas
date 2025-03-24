<?php
$lang['name'] = 'Nom du service d\'authentification';
$lang['logourl'] = 'URL du Logo pour le service CAS. Si https, il faut que l\'URL soit relative ou en https';

$lang['server'] = 'fqdn du serveur cas (cas.example.com)';
$lang['port'] = 'port du CAS (443)';
$lang['rootcas'] = 'URI du service cas (/cas)';
$lang['logfileuser'] = 'Nom du fichier journal. Si définit, enregistre les connexions des utilisateurs. Le fichier se trouve le dossier des journaux. L\'heure est en UTC';
$lang['http_header_real_ip'] = 'En-tête HTTP contenant l\'adresse IP réelle du client. A utiliser si l\'instance dokuwiki est "derrière" un serveur mandataire inverse (reverse proxy)';


$lang['handlelogoutrequest'] = 'Gérer les demandes de déconnexion du CAS';
$lang['handlelogoutrequestTrustedHosts'] = 'Hôtes de confiance pour les demandes de déconnexion(FQDN ou IP), séparé par des virgules. Réalise une résolution DNS inverse. Attention si vous êtes derrière un proxy inverse.';

$lang['autologin'] = 'Connexion automatique';

$lang['group_attribut'] = 'Attribut CAS contenant la liste des groupes de l\'utilisateurs.';
$lang['name_attribut'] = 'Attribut CAS contenant le nom de l\'utilisateur.';
$lang['mail_attribut'] = 'Attribut CAS contenant le courriel de l\'utilisateur.';
$lang['uid_attribut'] = 'Attribut CAS contenant l\'identifiant de l\'utilisateur.';

$lang['cacert'] = 'Autorité ayant validé le certificat du serveur CAS (mettre le contenu du certificat)';
$lang['debug'] = 'Afficher les informations de débogage';

