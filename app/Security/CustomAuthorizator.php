<?php 
declare(strict_types = 1);

namespace App\Security;

use Nette\Security\Authorizator;
use Nette\Security\SimpleIdentity;

class CustomAuthorizator {
	private $authorizator;

	public function setAuthorizator(){
		$this->authorizator = AuthorizatorFactory::$acl;
	}

	public function isAllowed(SimpleIdentity $identity, IAuthorizationScope $scope, array $action) {
		//dump($identity, $scope, $action);
		list($resource, $privilege) = $action;
		foreach ($this->getRoles($identity, $scope) as $role) {
			if ($this->authorizator->isAllowed($role, $resource, $privilege)) {
				return true;
			}
		}

		return false;
	}

	private function getRoles(SimpleIdentity $identity, IAuthorizationScope $scope)
	{
		foreach ($identity->getRoles() as $role) {
			yield $role;
		}
		foreach ($scope->getIdentityRoles($identity) as $role) {
			yield $role;
		}
	}
}