<?php

namespace App\Security;

use App\Service\FacebookService;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class FacebookAuthenticator extends AbstractFormLoginAuthenticator
{
    public const LOGIN_ROUTE = 'app_login';

    private $csrfTokenManager;
    private $passwordEncoder;
    private $urlGenerator;
    private $fbService;

    public function __construct(
        FacebookService $fbService,
        UrlGeneratorInterface $urlGenerator,
        CsrfTokenManagerInterface $csrfTokenManager,
        UserPasswordEncoderInterface $passwordEncoder
    )
    {
        $this->fbService = $fbService;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder  = $passwordEncoder;
    }

    public function supports(Request $request)
    {
        $route = $request->attributes->get('_route');
        $isLoginOrRegister = in_array($route, ['app_login', 'app_register']);
        return $isLoginOrRegister
            && $request->isMethod('POST')
            && $request->get('fbAuthResponse');
    }

    public function getCredentials(Request $request)
    {
        return json_decode($request->get('fbAuthResponse'), true);
    }

    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
        $providerKey
    )
    {
        return new RedirectResponse(
            $this->urlGenerator->generate('app_landing')
        );
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (empty($credentials['accessToken'])) {
            throw new CustomUserMessageAuthenticationException('Your message here');
        }

        $fbUser = $this->fbService->getUser($credentials['accessToken']);

        if (empty($fbUser->getEmail())) {
            throw new CustomUserMessageAuthenticationException('Your message here');
        }

        return $userProvider->loadUserByUsername($fbUser->getEmail());
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // Check the user's password or other credentials and return true or false
        // If there are no credentials to check, you can just return true
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
