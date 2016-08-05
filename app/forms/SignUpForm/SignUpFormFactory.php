<?php

/**
 * Created by PhpStorm.
 * User: Tomas
 * Date: 03.08.2016
 * Time: 17:50
 */
namespace App\Forms;

use App\Model\DuplicateNameException;
use App\Model\UserManager;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;


class SignUpFormFactory extends Control
{
    public $onSuccess;

    /** @var FormFactory  */
    private $formFactory;

    /** @var UserManager  */
    private $userManager;


    public function __construct(FormFactory $formFactory, UserManager $userManager)
    {
        parent::__construct();
        $this->formFactory = $formFactory;
        $this->userManager = $userManager;
    }


    public function createComponentSignUpForm() {
        $form = $this->formFactory->create();

        $form->addText('username')
            ->setRequired();

        $form->addPassword('password', 'Heslo:')
            ->setRequired();

        $form->addPassword('passwordAgain', 'Heslo znovu:')
            ->setRequired()
            ->addRule($form::EQUAL, 'Zadaná hesla se neshodují!', $form['password']);

        $form->addEmail('email', 'Email:')
            ->setRequired();

        $form->addText('first_name', 'Křestní jméno:');

        $form->addText('last_name', 'Příjmení:');

        $form->addSubmit('send', 'Registrovat');

        $form->onSuccess[] = [$this, 'processForm'];

        return $form;
    }


    public function processForm(Form $form, array $values) {
        try {
            $this->userManager->addUser($values);
            $this->onSuccess();
        } catch (DuplicateNameException $e) {
            $form->addError($e->getMessage());
        }
    }


    public function render() {
        $template = $this->template;
        $template->setFile(__DIR__ . '/signUpForm.latte');
        $template->render();
    }
}


interface ISignUpForm {

    /** @return SignUpFormFactory */
    public function create();
}