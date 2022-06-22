<?php
namespace App\Presenters;

use App\Model\AuthorizatorFactory;
use App\Model\CustomAuthorizator;
use App\Model\Entity\Article;
use App\Model\Entity\User;
use App\Model\IAuthorizationScope;
use Doctrine\ORM\EntityManagerInterface;
use Nette;
use Nette\Application\ForbiddenRequestException;
use Nette\Application\UI\Form;
use Nette\Security\Authorizator;

final class HomepagePresenter extends Nette\Application\UI\Presenter
{
	public function __construct(
		private EntityManagerInterface $em,
	) {
	}

	public function beforeRender() {
		$this->template->users = $this->em->getRepository(User::class)->findAll();
		$this->template->articles = $this->em->getRepository(Article::class)->findAll();
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

	public function handleCreateArticle() {
		$currentUser = User::getById($this->em, $this->user->getId());
		$article = new Article();
		$article->setOwner($currentUser);
		$article->save($this->em);
	}

	public function handleLogout(){
		$this->user->logout(true);
	}

	public function handleLogin($email){
		$this->user->login($email);
	}

	public function handleDelete($id) {
		$toDelete = $this->em->getReference(User::class, intval($id));
		$this->em->remove($toDelete);
		$this->em->flush();
	}

	public function handleView($id) {

	}

	public function handleDeleteArticle($id) {
		$toDelete = $this->em->getReference(Article::class, intval($id));
		//TODO REQUIRE ACCESS
		$this->em->remove($toDelete);
		$this->em->flush();
	}
}
