<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationVoter extends Voter
{
    public const ACCESS = 'ACCESS';

    protected function supports(string $attribute, mixed $subject): bool
    {
	    return $attribute === self::ACCESS;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if ($user instanceof UserInterface) {
            return true;
        }

        return false;
    }
}
