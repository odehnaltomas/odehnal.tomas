<?php

namespace App\Forms;

use Kdyby\Translation\Translator;
use Nette;
use Nette\Application\UI\Form;


class FormFactory
{
	use Nette\SmartObject;

    /** @var Translator  */
    protected $translator;


    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }


    /**
	 * @return Form
	 */
	public function create()
	{
		$form = new Form;
        $form->setTranslator($this->translator);
        $form->getElementPrototype()->novalidate = 'novalidate';
        return $form;
	}

}
