<?php
namespace App\Security;

use Nette\Security\SimpleIdentity;

// ! aktuálně nepoužíváno
interface IAuthorizationScope {
	public function getIdentityRoles(SimpleIdentity $identity): array;
}