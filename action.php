<?php
/**
 * CAS authentication plugin
 *
 * @licence   GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author    Xylle, Fabian Bircher
 * @version   0.0.3
 *
 */


use dokuwiki\Extension\ActionPlugin;

class action_plugin_authssocas extends ActionPlugin
{
    // https://www.dokuwiki.org/devel:plugins
    // https://www.dokuwiki.org/devel:plugin_file_structure
    // https://www.dokuwiki.org/devel:plugin_info
    // https://www.dokuwiki.org/devel:auth_plugins
    // https://www.dokuwiki.org/devel:events_list
    // https://www.dokuwiki.org/devel:common_plugin_functions
    // https://www.dokuwiki.org/devel:metadata



    public function register(Doku_Event_Handler $controller): void
    {
        // Gestion des événements
        // Création du formulaire de connexion
        $controller->register_hook('FORM_LOGIN_OUTPUT', 'BEFORE', $this, 'handle_login_form');
        // Connexion et déconnexion
        $controller->register_hook('ACTION_ACT_PREPROCESS', 'BEFORE', $this, 'handle_action');
    }

    /**
     *
     * Suppression du formulaire par défaut et création de celui pour le CAS
     *
     * @param Doku_Event $event
     * @return void
     */
    public function handle_login_form(Doku_Event $event): void
    {
        global $auth;
        global $lang;
        global $ID;

        if (!is_a($auth, 'auth_plugin_authssocas')) return;

        // Création du lien avec le logo.
        if ($this->getConf('logourl') != '') {
            $caslogo = '<img src="' . $this->getConf('logourl') . '" alt="" style="vertical-align: middle;" width="100"/> ';
        } else {
            $caslogo = '';
        }

        /** @var dokuwiki\Form\Form $form */
        /** @noinspection PhpUndefinedFieldInspection */
        $form =& $event->data;


        // Suppression du formulaire de base
        for($i = $form->elementCount(); $i >= 0;){
            $form->removeElement($i);
            $i--;
        }
        $login = wl($ID, 'do=caslogin', true, '&');

        // Ajout du lien d'authentification pour le CAS
        $form->addFieldsetOpen($this->getConf('name'));
        $form->addHTML('<p style="text-align: center;"><a href="' . $login . '"><div>' . $caslogo . '</div>' . $lang['btn_login'] . '</a></p>');
        $form->addFieldsetClose();
    }


    /**
     *
     * Gestion des actions connexion et déconnexion
     *
     * @param Doku_Event $event
     * @return void
     */
    public function handle_action(Doku_Event $event): void
    {
        global $auth;
        global $ID;

        /** @noinspection PhpUndefinedFieldInspection */
        if ($event->data == 'caslogin') {
            $auth->logIn();
        }

        /** @noinspection PhpUndefinedFieldInspection */
        if ($event->data == 'logout') {
            $auth->logOff();
            // Redirige vers la page d'acceuil du wiki, sinon le lien d'administration du wiki reste visible (pour les administrateurs).
            header('Location: '. wl($ID,'',true));
        }
    }

}
