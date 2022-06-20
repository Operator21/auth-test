<?php
namespace App\Model\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: "user")]
class User {
	#[Id, Column(unique: true, type: "integer"), GeneratedValue]
	private int $id;

	#[Column(type: "string", length: 255)]
	private string $email;

	#[Column(type: "string", length: 255)]
	private string $password;

	#[Column(type: "string", length: 5)]
	private string $role;

	public function getId(): int {
		return $this->id;
	}
	
	public function getEmail(): string {
		return $this->email;
	}

	public function getPassword(): string {
		return $this->password;
	}

	public function getRole(): string {
		return $this->role;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function setPassword($password) {
		$this->password = $password;
	}

	public function save(EntityManagerInterface $em) {
		$em->persist($this);
		$em->flush();
	}

	public static function getById(EntityManagerInterface $em, $id): ?User {
		return $em->find(User::class, $id);
	}
}