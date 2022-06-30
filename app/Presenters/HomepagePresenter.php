<?php
namespace App\Presenters;

use App\Model\Entity\Article;
use App\Model\Entity\User;
use App\Security\AuthorizatorFactory;
use App\Security\CustomAuthorizator;
use App\Security\IAuthorizationScope;
use Doctrine\ORM\EntityManagerInterface;
use Nette;
use Nette\Application\UI\Form;
use Nette\Security\Authorizator;

final class HomepagePresenter extends Nette\Application\UI\Presenter
{
	public function __construct(
		private EntityManagerInterface $em,
		private CustomAuthorizator $authorizator
	) {
	}

	public function beforeRender() {
		$this->template->users = $this->em->getRepository(User::class)->findAll();
		$this->template->articles = $this->em->getRepository(Article::class)->findAll();
		if($this->user->isLoggedIn()){
			$loggedUser = User::getById($this->em, $this->user->getId());
			$this->template->loggedUser = $loggedUser;	
		}
		$this->template->acl = AuthorizatorFactory::$acl;
		$this->template->authorizator = $this->authorizator;
		$this->authorizator->setAuthorizator(AuthorizatorFactory::$acl);
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

	public function isAllowed(IAuthorizationScope $scope, array $action): bool {
		return $this->authorizator->isAllowed($this->user->getIdentity(), $scope, $action);
	}
}
