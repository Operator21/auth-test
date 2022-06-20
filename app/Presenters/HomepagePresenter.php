<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Model\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Nette;
use Nette\Application\UI\Form;

final class HomepagePresenter extends Nette\Application\UI\Presenter
{
	static $users;

	public function __construct(
		private EntityManagerInterface $em
	) {
	}

	public function beforeRender() {
		$this->template->users = $this->em->getRepository(User::class)->findAll();
		if($this->user->isLoggedIn())
			$this->template->loggedUser = User::getById($this->em, $this->user->getId());
	}

	protected function createComponentCreateUserForm(): Form
	{
		$form = new Form();
		$form->addSubmit("save", "Create user");
		$form->onSuccess[] = [$this, "createUser"];

		return $form;
	}

	public function handleCreateUser() {
		$u = new User();
		$u->setEmail(User::randomEmail());
		$u->setPassword("1234");
		$u->setRole("user");
		$u->save($this->em);
	}

	public function handleLogout(){
		$this->user->logout(true);
	}

	public function handleLogin($email){
		$this->user->login($email);
	}
}
