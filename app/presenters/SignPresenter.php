<?php

namespace App\Presenters;

use Nette;
use App\Forms;


class SignPresenter extends BasePresenter
{
	/** @var Forms\ISignInForm @inject */
	public $signInFormFactory;

    /** @var  Forms\ISignUpForm @inject*/
    public $signUpFormFactory;



    /**
     * @return Forms\SignInForm
     */
	protected function createComponentSignInForm() {
	    $control = $this->signInFormFactory->create();
        $control->onSuccess[] = function() {
            $this->redirect('this');
        };

        return $control;
	}


	/**
	 * Sign-up form factory.
	 * @return Forms\SignUpFormFactory
	 */
	protected function createComponentSignUpForm()
	{
		$control = $this->signUpFormFactory->create();
        $control->onSuccess[] = function() {
            $this->redirect('this');
        };

        $control->onSuccessTest[] = function($token) {
            $this->redirect('emailTest', array('code' => $token));
        };
        return $control;
	}

	public function renderEmailTest($code) {
	    $this->template->code = $code;
    }


	public function actionOut()
	{
		$this->getUser()->logout();
	}

}
