<?php

/**
 * CAS authentication plugin
 *
 * @licence   GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author    Xylle, Fabian Bircher
 * @version   0.0.3
 *
 */

use AuthSSOCas\SimpleFileLogger;
use dokuwiki\Extension\AuthPlugin;
use dokuwiki\Logger;


class auth_plugin_authssocas extends AuthPlugin
{
    /**
     * @var array|mixed
     */
    private array $options = array();

    private ?string $logfileuser = null;

    public function __construct()
    {
        global $conf;
        parent::__construct();
        require_once __DIR__ . '/vendor/autoload.php';

        // Vérifie si la classe phpCAS existe
        if (!class_exists('phpCAS')) {
            msg("CAS err: phpCAS class not found.", -1);
            $this->success = false;
            return;
        }
        // Vérifie si l'extension curl existe
        if (!extension_loaded("curl")) {
            msg("CAS err: CURL php extension not found.", -1);
            $this->success = false;
            return;
        }
        // Définition des capacités de l'extension d'authentification
        $this->cando['external'] = true;

        // Création d'un journal des connexions, si un fichier est défini.
        if ($this->getConf('logfileuser')) {
            $this->logfileuser = $conf['logdir'] . "/" . $this->getConf('logfileuser');
        }
        if (!is_null($this->logfileuser) and !@is_readable($this->logfileuser)) {
            if (!fopen($this->logfileuser, 'a')) {
                msg("plainCAS: The CAS log users file could not be opened.", -1);
                $this->success = false;
            }
        }


        // Chargement des options
        $this->options['debug'] = $this->getConf('debug');
        $this->options['group_attribut'] = $this->getConf('group_attribut');
        $this->options['group_attribut_separator'] = $this->getConf('group_attribut_separator');
        $this->options['handlelogoutrequest'] = $this->getConf('handlelogoutrequest');
        $this->options['handlelogoutrequestTrustedHosts'] = $this->getConf('handlelogoutrequestTrustedHosts');
        $this->options['mail_attribut'] = $this->getConf('mail_attribut');
        $this->options['name_attribut'] = $this->getConf('name_attribut');
        $this->options['port'] = $this->getConf('port');
        $this->options['samlValidate'] = $this->getConf('samlValidate');
        $this->options['server'] = $this->getConf('server');
        $this->options['rootcas'] = $this->getConf('rootcas');
        $this->options['uid_attribut'] = $this->getConf('uid_attribut');
        $this->options['cacert'] = $this->getConf('cacert');

        $server_version = CAS_VERSION_2_0;
        if ($this->getOption("samlValidate")) {
            $server_version = SAML_VERSION_1_1;
        }

        if ($this->getOption("debug")) {
            $logdir = $conf['logdir'];
            $logger = new SimpleFileLogger($logdir . '/cas.log');
            phpCAS::setLogger($logger);
            phpCAS::setVerbose(true);
        }

        if (!DOKU_BASE == "/") {
            $service_base_url = str_replace(DOKU_BASE, "", DOKU_URL);
        } else {
            $service_base_url = DOKU_URL;
        }

        // Configuration du client CAS
        phpCAS::client(
            $server_version,
            $this->getOption('server'),
            (int)$this->getOption('port'),
            $this->getOption('rootcas'),
            $service_base_url
        );

        if ($this->getConf('autologin')) {
            phpCAS::setCacheTimesForAuthRecheck(-1);
        } else {
            phpCAS::setCacheTimesForAuthRecheck(1);
        }

        // Gestion de l'autorité de certification du certificat du serveur CAS pour la bibliothèque php_curl
        $cas_cacert_file = DOKU_CONF . 'authssocas.cacert.pem';
        if ($this->getOption('cacert')) {
            if (!io_saveFile($cas_cacert_file, $this->getOption('cacert'))) {
                msg('The ' . $cas_cacert_file . ' file is not writable. Please inform the Wiki-Admin', -1);
            }
            phpCAS::setCasServerCACert($cas_cacert_file);
        } else {
            phpCAS::setNoCasServerValidation();
        }

        // Gestion de la déconnexion sur le serveur CAS
        if ($this->getOption('handlelogoutrequest')) {
            phpCAS::handleLogoutRequests(true, $this->getOption('handlelogoutrequestTrustedHosts'));
        } else {
            phpCAS::handleLogoutRequests(false);
        }
    }

    /**
     *
     * Récupère les options
     *  Transforme en tableau les URL de notification de la déconnexion pour les serveurs CAS
     *
     * @param $optionName
     * @return array|mixed|string[]|null
     */
    private function getOption($optionName): mixed
    {
        if (isset($this->options[$optionName])) {
            switch ($optionName) {
                case 'handlelogoutrequestTrustedHosts':
                    $arr = explode(',', $this->options[$optionName]);
                    foreach ($arr as $key => $item) {
                        $arr[$key] = trim($item);
                    }
                    return $arr;
                default:
                    return $this->options[$optionName];
            }
        }
        return NULL;
    }

    /**
     *
     * Transfert de la demande de connexion au serveur CAS
     *
     * @return void
     * @noinspection PhpUnused
     */
    public function logIn(): void
    {
        global $ID;
        $login_url = DOKU_URL . 'doku.php?id=' . $ID;

        phpCAS::setFixedServiceURL($login_url);
        phpCAS::forceAuthentication();
    }

    /**
     *
     * Déconnexion de l'utilisateur avec prise en compte de la déconnexion générale du CAS
     *
     * @return void
     */
    public function logOff(): void
    {
        global $ID;
        global $USERINFO;

        @session_start();
        session_destroy();

        $this->auth_log($USERINFO, "logout");
        if ($this->getOption('handlelogoutrequest')) {
            $logout_url = DOKU_URL . 'doku.php?id=' . $ID;
            @phpCAS::logoutWithRedirectService($logout_url);
        } else {
            phpCAS::handleLogoutRequests();
            unset($_SESSION);
        }
    }

    public function trustExternal($user, $pass, $sticky = false): bool
    {
        global $USERINFO;

        if (!empty($_SESSION[DOKU_COOKIE]['auth']['info'])) {
            $USERINFO['name'] = $_SESSION[DOKU_COOKIE]['auth']['info']['name'];
            $USERINFO['mail'] = $_SESSION[DOKU_COOKIE]['auth']['info']['mail'];
            $USERINFO['grps'] = $_SESSION[DOKU_COOKIE]['auth']['info']['grps'];
            $_SERVER['REMOTE_USER'] = $_SESSION[DOKU_COOKIE]['auth']['user'];
            return true;
        }

        if (phpCAS::isAuthenticated() or ($this->getOption('autologin') and phpCAS::checkAuthentication())) {

            $USERINFO = $this->cas_user_attributes(phpCAS::getAttributes());
            $this->auth_log($USERINFO, "login");
            $_SESSION[DOKU_COOKIE]['auth']['user'] = $USERINFO['uid'];
            $_SESSION[DOKU_COOKIE]['auth']['info'] = $USERINFO;
            $_SERVER['REMOTE_USER'] = $USERINFO['uid'];
            return true;
        }

        return false;
    }

    /**
     *
     * Renvoi les informations de l'utilisateur fournit par le CAS
     *
     * @param $attributes
     * @return array
     */
    private function cas_user_attributes($attributes): array
    {
        return array(
            'uid' => $attributes[$this->getOption('uid_attribut')] ?? '',
            'name' => $attributes[$this->getOption('name_attribut')] ?? '',
            'mail' => $attributes[$this->getOption('mail_attribut')] ?? '',
            'grps' => $this->cas_user_groups($attributes),
        );
    }

    /**
     *
     * Log user connection if the log file is defined
     *
     * format : DATE|TIME|ACTION|USER|CLIENT_IP|REAL_CLIENT_IP|USERINFO
     * ACTION : login ou logout
     * REAL_CLIENT_IP : si null, il y a un tiret
     * USERINFO : si null, il y a un tiret
     *
     * @param $userinfo
     * @param string $action
     * @return void
     */
    private function auth_log($userinfo, string $action): void
    {
        if (!is_null($this->logfileuser)) {
            $date = (new DateTime('now'))->format('Ymd|H:i:s');
            $real_client_ip = ($this->getOption('http_header_real_ip') ? ($_SERVER[$this->getOption('http_header_real_ip')] ?? '-') : '-');
            $client_ip = $_SERVER['REMOTE_ADDR'];

            $utilisateur = $userinfo['uid'] ?? ($_SESSION[DOKU_COOKIE]['auth']['user'] ?? '-');
            $informations = $userinfo ? json_encode($userinfo, JSON_UNESCAPED_UNICODE) : '-';

            $userline = $date . "|" .
                $action . "|" .
                $utilisateur . '|' .
                $client_ip . '|' .
                $real_client_ip . '|' .
                $informations .
                PHP_EOL;


            $this->write_log($userline);
        }
    }

    /**
     *
     * Renvoi les groupes de l'utilisateur fournis par le CAS
     * et s'assure que la valeur est bien de type array
     *
     * @param $attributes
     * @return array
     */
    private function cas_user_groups($attributes): array
    {
        global $conf;
        $raw_groups = $attributes[$this->getOption('group_attribut')] ?: array();
        $user_groups = array();

        Logger::debug("authssocas: raw user groups '" . implode(',', (array)$raw_groups) . "' - Group separator : '" . $this->getOption('group_attribut_separator') . "' - defaultgroup : '{$conf['defaultgroup']}'");
        if (!$this->getOption('group_attribut_separator')) {
            # Sans configuration de group_attribut_separator : la valeur retournée par CAS doit être un tableau.
            if (!is_array($raw_groups)) {
                $user_groups = array($raw_groups);
            } else {
                $user_groups = $raw_groups;
            }
        } else {
            # Avec une configuration `group_attribut_separator` : la valeur retournée par CAS doit être une chaîne de caractères.
            if (is_array($raw_groups)) {
                $user_groups = $raw_groups;
            } elseif (is_string($raw_groups)) {
                $user_groups = explode($this->getOption('group_attribut_separator'), $raw_groups);
            }
        }

        # Toujours ajouter le groupe par défaut (comme le font les autres plugins d'authentification).
        if ($conf['defaultgroup'] && !in_array($conf['defaultgroup'], $user_groups)) {
            $user_groups[] = $conf['defaultgroup'];
        }
        return $user_groups;
    }

    /**
     * @param string $userline
     * @return void
     */
    public function write_log(string $userline): void
    {
        if (!io_saveFile($this->logfileuser, $userline, true)) {
            msg($this->getLang('writefail'), -1);
        }
    }
}
