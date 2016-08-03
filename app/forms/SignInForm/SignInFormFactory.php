<?php

/**
 * Created by PhpStorm.
 * User: Tomas
 * Date: 03.08.2016
 * Time: 17:17
 */
namespace App\Forms;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Security\User;


class SignInForm extends Control
{

    public $onSuccess;

    /** @var User  */
    private $user;

    /** @var FormFactory  */
    private $formFactory;


    public function __construct(User $user, FormFactory $formFactory)
    {
        parent::__construct();
        $this->user = $user;
        $this->formFactory = $formFactory;
    }


    protected function createComponentSignInForm() {
        $form = $this->formFactory->create();

        $form->addText('username', 'Login:')
            ->setRequired();
        $form->addPassword('password', 'Heslo:')
            ->setRequired();
        $form->addCheckbox('remember', 'Zapamatovat');
        $form->addSubmit('send', 'Přihlásit');

        $form->onSuccess[] = [$this, 'processForm'];

        return $form;
    }


    public function processForm(Form $form, $values) {

        try {
            $this->user->setExpiration($values->remember ? '14 days' : '2 minutes');
            $this->user->login($values->username, $values->password);
            $this->onSuccess();
        } catch(AuthenticationException $e) {
            $form->addError($e->getMessage());
        }
    }


    public function render() {
        $template = $this->template;
        $template->setFile(__DIR__ . '/signInForm.latte');
        $template->render();
    }
}


interface ISignInForm
{
    /** @return SignInForm */
    public function create();

}