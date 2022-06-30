<?php
declare(strict_types = 1);

namespace App\Security;

use Nette\Security\SimpleIdentity;

interface IAuthorizationScope {
	public function getIdentityRoles(SimpleIdentity $identity): array;
}