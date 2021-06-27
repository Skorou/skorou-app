<?php


namespace App\Service;

use App\Entity\FacebookUser;
use Facebook\Facebook;

class FacebookService
{
    private $client;

    public function __construct(
        string $fbAppId,
        string $fbAppSecret,
        string $fbGraphVersion
    )
    {
        $this->client = new Facebook([
            'app_id' => $fbAppId,
            'app_secret' => $fbAppSecret,
            'default_graph_version' => $fbGraphVersion,
        ]);

    }

    public function getUser(string $token): FacebookUser
    {
        $user = new FacebookUser();

        try {
            $fbUser = $this->client->get("/me?fields=name,email", $token);
            $data = $fbUser->getDecodedBody();
            $user
                ->setName($data['name'])
                ->setEmail($data['email']);
        } catch (\Throwable $exception) {
            // handle exception here
        }

        return $user;
    }
}