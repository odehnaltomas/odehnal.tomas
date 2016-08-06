<?php
/**
 * Created by PhpStorm.
 * User: Tomas
 * Date: 05.08.2016
 * Time: 18:06
 */

namespace App\Forms\Renderers;


use Nette\Forms\Form;
use Nette\Forms\IControl;
use Nette\Forms\Rendering\DefaultFormRenderer;
use Nette\Forms\Controls;

class BS3SignFormRenderer extends DefaultFormRenderer
{

    private $controlsInit = FALSE;


    public function __construct()
    {
        $this->wrappers['controls']['container'] = NULL;
        $this->wrappers['pair']['container'] = 'div class=form-group';
        $this->wrappers['pair']['.error'] = 'has-error';
        $this->wrappers['control']['container'] = NULL;
        $this->wrappers['label']['container'] = NULL;
        $this->wrappers['control']['description'] = 'span class=help-block';
        $this->wrappers['control']['errorcontainer'] = 'span class=help-block';
        $this->wrappers['error']['container'] = null;
        $this->wrappers['error']['item'] = 'div class="alert alert-danger"';
        $this->wrappers['control']['.submit'] = 'btn-block';
    }


    public function render(Form $form, $mode = NULL)
    {
        if ($this->form !== $form) {
            $this->form = $form;
        }

        $s = '';
        if (!$mode || $mode === 'begin') {
            $this->controlsInit();
            $s .= $this->renderBegin();
        }
        if (!$mode || strtolower($mode) === 'ownerrors') {
            $this->controlsInit();
            $s .= $this->renderErrors();

        } elseif ($mode === 'errors') {
            $this->controlsInit();
            $s .= $this->renderErrors(NULL, FALSE);
        }
        if (!$mode || $mode === 'body') {
            $this->controlsInit();
            $s .= $this->renderBody();
        }
        if (!$mode || $mode === 'end') {
            $this->controlsInit();
            $s .= $this->renderEnd();
        }
        return $s;
    }


    public function renderLabel(IControl $control)
    {
        return $this->getWrapper('label container')->setHtml(NULL);
    }


    private function controlsInit() {
        if ($this->controlsInit) {
            return;
        }

        $this->controlsInit = TRUE;
        foreach ($this->form->getControls() as $control) {
            if ($control instanceof Controls\Button) {
                $control->getControlPrototype()->addClass(empty($usedPrimary) ? 'btn btn-primary' : 'btn btn-default');
                $usedPrimary = TRUE;

            } elseif ($control instanceof Controls\TextBase || $control instanceof Controls\SelectBox || $control instanceof Controls\MultiSelectBox) {
                $control->getControlPrototype()->addClass('form-control');

            } elseif ($control instanceof Controls\Checkbox || $control instanceof Controls\CheckboxList || $control instanceof Controls\RadioList) {
                $control->getSeparatorPrototype()->setName('div')->addClass($control->getControlPrototype()->type);
            }
        }
    }

}