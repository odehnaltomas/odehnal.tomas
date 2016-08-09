<?php
/**
 * Created by PhpStorm.
 * User: Tomas
 * Date: 06.08.2016
 * Time: 18:57
 */

namespace App\Presenters;


use App\Model\UserManager;

class CheckPresenter extends BasePresenter
{

    /** @var UserManager  */
    private $userManager;


    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }


    public function actionDefault($code) {
        $this->template->code = $code;
        //TODO: Dodělat kontrolu emailu
        if(empty($code) || strlen($code) != 32)
            $this->template->alert = array(
                "message" => "Ověřovací kód není ve správném tvaru. Registrace nemohla být dokončena.",
                "type" => "danger"
            );

        if($this->user->getId())
            $this->template->alert = array(
                "message" => "Již jste zaregistrovaný a přihlášený.",
                "type" => "warning"
            );

        if($this->userManager->updateRegisterUser($code)) {
            $this->template->alert = array(
                "message" => "Vaše registrace byla úspěšně dokončena. Nyní se můžete přihlásit.",
                "type" => "success"
            );
        } else {
            $this->template->alert = array(
                "message" => "Registrace nemohla být dokončena. Zřejmě máte špatný ověřovací kód.",
                "type" => "danger"
            );
        }
    }
}