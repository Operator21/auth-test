<?php

namespace App\Security;

use Nette\Security\Permission;

class AuthorizatorFactory {
	const ROLE_ADMIN = "admin"; 
	const ROLE_USER = "user"; 
	const ROLE_GUEST = "guest"; 

	const RESOURCE_ARTICLE = "article";
	const RESOURCE_USER = "user";

	const ACTION_CREATE = "create";
	const ACTION_UPDATE = "update";
	const ACTION_DELETE = "delete";

	public static function create(): Permission {
		$acl = new Permission();

		// add resources
		$acl->addResource(self::RESOURCE_USER);
		$acl->addResource(self::RESOURCE_ARTICLE);

		//add roles
		$acl->addRole(self::ROLE_ADMIN);
		$acl->addRole(self::ROLE_USER);
		$acl->addRole(self::ROLE_GUEST);

		//admin has total control
		$acl->allow(self::ROLE_ADMIN, self::RESOURCE_USER);
		$acl->allow(self::ROLE_ADMIN, self::RESOURCE_ARTICLE);

		//if user is author -> allow deletion
		$assertion = function (Permission $acl, string $role, string $resource, string $privilege): bool {
			// tady by se textové řetezce měli měnit na objekty
			$role = $acl->getQueriedRole();
			$resource = $acl->getQueriedResource();
			/************************/
			/******Zdroj problémů****/
			//Tady to umře, protože $role zůstává string z parametrů
			return $role->getId() === $resource->getOwner()->getId();
			/************************/
		};
		$acl->allow(self::ROLE_USER, self::RESOURCE_ARTICLE, self::ACTION_DELETE, $assertion);

		return $acl;
	}
}