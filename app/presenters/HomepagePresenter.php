<?php

namespace App\Presenters;

use App\Forms\IComponent;
use Nette;
use App\Model;


class HomepagePresenter extends BasePresenter
{

	public function renderDefault()
	{
		$this->template->anyVariable = 'any value';
	}

}
