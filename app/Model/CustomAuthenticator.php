<?php
namespace App\Model;

use App\Model\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Nette;
use Nette\Security\AuthenticationException;
use Nette\Security\Authenticator;
use Nette\Security\IIdentity;
use Nette\Security\SimpleIdentity;

class CustomAuthenticator implements Authenticator {
	public function __construct(
		private EntityManagerInterface $em
	) {	
	}

	public function authenticate(string $email, $password = ""): IIdentity
	{
		$user = $this->em->getRepository(User::class)->findOneBy(["email" => $email]);

		if(!$user)
			throw new AuthenticationException("User doesn't exist");	

		return new SimpleIdentity($user->getId(), $user->getRole());	
	}
}