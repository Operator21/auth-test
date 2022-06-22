<?php

namespace App\Model\Entity;

use App\Security\AuthorizatorFactory;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Nette\Security\Resource;

#[Entity]
#[Table(name: "article")]
class Article extends CEntity implements Resource  {
	#[Id, Column(unique: true, type: "integer"), GeneratedValue]
	private int $id;

	#[ManyToOne(targetEntity: User::class), JoinColumn(name: "user_id", referencedColumnName: "id")]
	private User $owner;

	#[Column(type: "string")]
	private string $content;

	public function getId(): int {
		return $this->id;
	}

	public function getResourceId(): string {
		return AuthorizatorFactory::RESOURCE_ARTICLE;
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