<?php

/*
 * Instagram Bundle for Contao Open Source CMS.
 *
 * Copyright (C) 2011-2019 Codefog
 *
 * @author  Codefog <https://codefog.pl>
 * @author  Kamil Kuzminski <https://github.com/qzminski>
 * @license MIT
 */

namespace Codefog\InstagramBundle\EventListener;

use Contao\Controller;
use Contao\DataContainer;
use Contao\Input;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;

class ModuleListener
{
    const SESSION_KEY = 'instagram-module-id';

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * ModuleListener constructor.
     */
    public function __construct(RouterInterface $router, SessionInterface $session)
    {
        $this->router = $router;
        $this->session = $session;
    }

    /**
     * On submit callback.
     */
    public function onSubmitCallback(DataContainer $dc)
    {
        if ('cfg_instagram' === $dc->activeRecord->type && $dc->activeRecord->cfg_instagramAppId && Input::post('cfg_instagramRequestToken')) {
            $this->requestAccessToken($dc->activeRecord->cfg_instagramAppId);
        }
    }

    /**
     * On the request token save.
     *
     * @return null
     */
    public function onRequestTokenSave()
    {
        return null;
    }

    /**
     * Request the Instagram access token.
     *
     * @param string $clientId
     */
    private function requestAccessToken($clientId)
    {
        $this->session->set(self::SESSION_KEY, Input::get('id'));
        $this->session->save();

        $data = [
            'app_id' => $clientId,
            'redirect_uri' => $this->router->generate('instagram_auth', [], RouterInterface::ABSOLUTE_URL),
            'response_type' => 'code',
            'scope' => 'user_profile,user_media',
        ];
print_r($data);exit;
        Controller::redirect('https://api.instagram.com/oauth/authorize/?'.http_build_query($data));
    }
}
