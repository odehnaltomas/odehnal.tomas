<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use Nextras\Forms\Rendering\Bs3FormRenderer;


class FormFactory
{
	use Nette\SmartObject;

	/**
	 * @return Form
	 */
	public function create()
	{
		$form = new Form;

        $form->getElementPrototype()->novalidate = 'novalidate';
        return $form;
	}

}
