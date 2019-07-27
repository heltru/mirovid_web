<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 17.09.2018
 * Time: 1:26
 */

namespace app\modules\app\app;


use app\modules\block\models\Block;

class AppCreateRk
{
    public $company;

    public function __construct()
    {
        $this->company = new Block();


    }

    public function createRk(){

        AppAccount::Instance()->getAccount()->id;
        $this->company->account_id = AppAccount::Instance()->getAccount()->id;

        return $this->company->save();
    }

    public function getName(){
        return $this->company->name;
    }

    public function getId(){
        return $this->company->id;
    }

}