<?php

namespace App\Model\Entity;

use App\Security\AuthorizatorFactory;
use App\Security\IAuthorizationScope;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Nette\Security\SimpleIdentity;

#[Entity]
#[Table(name: "article")]
class Article extends CEntity implements IAuthorizationScope  {
	const ROLE_AUTHOR = Article::class . ":author";

	const ACTION_DELETE = [self::class, "delete"];

	#[Id, Column(unique: true, type: "integer"), GeneratedValue]
	private int $id;

	#[ManyToOne(targetEntity: User::class), JoinColumn(name: "user_id", referencedColumnName: "id")]
	private User $owner;

	#[Column(type: "string")]
	private string $content;

	public function getId(): int {
		return $this->id;
	}

	public function getIdentityRoles(SimpleIdentity $identity): array {
		if ($this->getOwner()->getId() === $identity->getId()) {
			return [self::ROLE_AUTHOR];
		}
		return [];
	}

	public function getContent(): string{
		return $this->content;
	}

	public function getOwner(): User{
		return $this->owner;
	}

	public function setOwner(User $owner){
		$this->owner = $owner;
	}

	public function setContent(string $content){
		$this->content = $content;
	}
}