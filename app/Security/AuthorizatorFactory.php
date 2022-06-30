<?php

namespace App\Security;

use App\Model\Entity\Article;
use App\Model\Entity\User;
use Nette\Security\Permission;

class AuthorizatorFactory {
	const ROLE_ADMIN = "admin"; 
	const ROLE_USER = "user"; 
	const ROLE_GUEST = "guest"; 

	const RESOURCE_ARTICLE = Article::class;
	const RESOURCE_USER = User::class;

	const ACTION_VIEW = "view";
	const ACTION_CREATE = "create";
	const ACTION_UPDATE = "update";
	const ACTION_DELETE = "delete";

	const ACTION_ALL = [
		self::ACTION_VIEW,
		self::ACTION_CREATE,
		self::ACTION_UPDATE,
		self::ACTION_DELETE
	];

	public static Permission $acl;

	public static function create(): Permission {
		$acl = new Permission();

		// add resources
		$acl->addResource(self::RESOURCE_USER);
		$acl->addResource(self::RESOURCE_ARTICLE);

		//add roles
		$acl->addRole(self::ROLE_ADMIN);
		$acl->addRole(self::ROLE_USER);
		$acl->addRole(self::ROLE_GUEST);
		$acl->addRole(Article::ROLE_AUTHOR);
		$acl->addRole(User::ROLE_OWNER);

		//admin has total control
		$acl->allow(self::ROLE_ADMIN, self::RESOURCE_USER);
		$acl->allow(self::ROLE_ADMIN, self::RESOURCE_ARTICLE);

		AuthorizatorFactory::$acl = $acl;

		//if user equals author -> allow deletion
		self::allow(Article::ROLE_AUTHOR, Article::ACTION_DELETE);

		//user can delete himself
		self::allow(User::ROLE_OWNER, User::ACTION_DELETE);

		return $acl;
	}

	private static function allow(string $role, array $action) {
		list($resource, $privilege) = $action;
		self::$acl->allow($role, $resource, $privilege);
	}
}