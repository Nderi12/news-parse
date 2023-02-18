<?php

declare(strict_types=1);

namespace App\Security;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface, PasswordUpgraderInterface
{
    /**
     * Symfony calls this method if you use features like switch_user
     * or remember_me.
     *
     * If you're not using these features, you do not need to implement
     * this method.
     *
     * @throws UserNotFoundException if the user is not found
     */
    public function loadUserByIdentifier($identifier): UserInterface
    {
        $username          = $identifier;
        $users_in_system   = self::getUsersInSystem();
        $notFoundException = new UserNotFoundException('No such user');
        
        if(!isset($users_in_system[$username])) {
            throw $notFoundException;
        }

        $user_data = $users_in_system[$username];

        if(!$user_data) {
            throw $notFoundException;
        }

        $user = new User();
        $user->setUsername($user_data['username']);
        $user->setPassword($user_data['password']);
        $user->setRoles($user_data['roles']);

        return $user;
    }

    /**
     * @deprecated since Symfony 5.3, loadUserByIdentifier() is used instead
     */
    public function loadUserByUsername($username): UserInterface
    {
        return $this->loadUserByIdentifier($username);
    }

    /**
     * Refreshes the user after being reloaded from the session.
     *
     * When a user is logged in, at the beginning of each request, the
     * User object is loaded from the session and then this method is
     * called. Your job is to make sure the user's data is still fresh by,
     * for example, re-querying for fresh User data.
     *
     * If your firewall is "stateless: true" (for a pure API), this
     * method is not called.
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        return $this->loadUserByIdentifier($user->getUsername());

        // Return a User object after making sure its data is "fresh".
        // Or throw a UsernameNotFoundException if the user no longer exists.
        //throw new \Exception('TODO: fill in refreshUser() inside '.__FILE__);
    }

    /**
     * Tells Symfony to use this provider for this User class.
     */
    public function supportsClass(string $class): bool
    {
        return User::class === $class || is_subclass_of($class, User::class);
    }

    /**
     * Upgrades the hashed password of a user, typically for using a better hash algorithm.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        // TODO: when hashed passwords are in use, this method should:
        // 1. persist the new password in the user storage
        // 2. update the $user object with $user->setPassword($newHashedPassword);
    }

    protected static function getUsersInSystem(): array
    {
        $admin_username     = $_SERVER['ADMIN_USERNAME'];
        $admin_password     = $_SERVER['ADMIN_PASSWORD'];
        $moderator_username = $_SERVER['MODERATOR_USERNAME'];
        $moderator_password = $_SERVER['MODERATOR_PASSWORD'];
        $user_username      = $_SERVER['USER_USERNAME'];
        $user_password      = $_SERVER['USER_PASSWORD'];

        
        return [
            $admin_username => [
                'username' => $admin_username, 
                'password' => $admin_password,
                'roles'    => ['ROLE_ADMIN']
            ],
            $moderator_username => [
                'username' => $moderator_username, 
                'password' => $moderator_password, 
                'roles'    => ['ROLE_MODERATOR']
            ],
            $user_username => [
                'username' => $user_username, 
                'password' => $user_password, 
                'roles'    => []
            ]
        ];
    }
}
