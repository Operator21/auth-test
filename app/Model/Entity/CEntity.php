<?php
namespace App\Model\Entity;

use Doctrine\ORM\EntityManagerInterface;

class CEntity {
	public function save(EntityManagerInterface $em) {
		$em->persist($this);
		$em->flush();
	}
}