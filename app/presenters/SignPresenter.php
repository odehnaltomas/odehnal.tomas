<?php

namespace App\Presenters;

use Nette;
use App\Forms;


class SignPresenter extends BasePresenter
{
	/** @var Forms\ISignInFormFactory @inject */
	public $signInFormFactory;

    /** @var  Forms\ISignUpFormFactory @inject*/
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
	 * @return Forms\SignUpForm
	 */
	protected function createComponentSignUpForm()
	{
		$control = $this->signUpFormFactory->create();
        $control->onSuccess[] = function() {
            $this->redirect('this');
        };
        return $control;
	}


	public function actionOut()
	{
		$this->getUser()->logout();
	}

}
