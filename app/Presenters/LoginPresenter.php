<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Security\CustomAuthenticator;
use Nette;
use Nette\ComponentModel\IComponent;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;

final class LoginPresenter extends Nette\Application\UI\Presenter
{
	public function __construct(
		private CustomAuthenticator $ca
	){	
	}

	protected function createComponentLoginForm(): Form
	{
		$form = new Form();
		$form->addEmail("email", "Email:");
		$form->addSubmit("login", "Login");
		$form->onSuccess[] = [$this, "onLogin"];

		return $form;
	}

	public function onLogin(Form $form, $data) {
		try {
			$this->getUser()->login($data->email, "");
			$this->redirect("Homepage:");
		} catch(AuthenticationException $e) {
			$this->flashMessage($data->email);
		}
	}
}
