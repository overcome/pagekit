<?php

namespace Pagekit\Auth\Event;

use Pagekit\Auth\UserInterface;

class AuthenticateEvent extends Event
{
    /**
     * @var string[]
     */
    protected $credentials;

    /**
     * Constructor.
     *
     * @param string[]      $credentials
     * @param UserInterface $user
     */
    public function __construct(array $credentials, UserInterface $user = null)
    {
        parent::__construct($user);

        $this->credentials = $credentials;
    }

    /**
     * Gets the login credentials.
     *
     * @return string[]
     */
    public function getCredentials()
    {
        return $this->credentials;
    }
}
