<?php

/**
 * Created by PhpStorm.
 * User: Tomas
 * Date: 03.08.2016
 * Time: 17:50
 */
namespace App\Forms;

use App\Forms\Renderers\BS3SignFormRenderer;
use App\Model\DuplicateNameException;
use App\Model\UserManager;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Mail\Message;
use Nette\Mail\SendException;
use Nette\Mail\SendmailMailer;
use Nette\Utils\Random;


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
            ->setAttribute('placeholder', 'Login')
            ->setRequired();

        $form->setRenderer(new BS3SignFormRenderer);

        $form->addPassword('password')
            ->setAttribute('placeholder', 'Heslo')
            ->setRequired();

        $form->addPassword('passwordAgain')
            ->setAttribute('placeholder', 'Heslo znovu')
            ->setRequired()
            ->addRule($form::EQUAL, 'Zadaná hesla se neshodují!', $form['password']);

        $form->addEmail('email', 'Email:')
            ->setAttribute('placeholder', 'Email')
            ->setRequired();

        $form->addSubmit('send', 'Registrovat');

        $form->onSuccess[] = [$this, 'processForm'];

        return $form;
    }


    public function processForm(Form $form, $values) {
        //TODO: Odzkoušet posílání emailu
        $token = Random::generate(32);

        $template = $this->createTemplate();
        $template->setFile(__DIR__ . '/email.latte');
        $template->code = $token;

        $mail = new Message();
        $mail->setFrom('me@example.com')
            ->addTo('tipek136@gmail.com')
            ->setSubject('Potvrzení registrace')
            ->setHtmlBody($template);

        $mailer = new SendmailMailer;
        try {
            $mailer->send($mail);

            $this->userManager->add($values->username, $values->password, $values->email, $token);
            $this->onSuccess();
        } catch (DuplicateNameException $e) {
            $form->addError($e->getMessage());
        } catch (SendException $e) {
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