<?php
if(IS_LOCAL){
    return [
        'adminEmail' => 'novavid@admin.ru',
        'supportEmail' => 'novavid@support.ru',
        'path_env' => '',
        'domain'=>'mirovid.ru',
        'vk'=>[
            'main' => array('group_id' => 999999, 'name' => 'mirovid'),

            'link_verify_callback' => /*FULL_HOST .*/ "http://mirovid.ru/webhook/vk",
            'redirect' => (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] ? REQUEST_SCHEME . "://{$_SERVER['HTTP_HOST']}" : FULL_HOST) . "/main/default/verify",

            'app4auth' => [
                7347580 => ['secret' => 'wqeN11FQ0ITl0noIR9uO'], //Mirovid.ru #1
            ],
            'app4user' => array('id' => 5856877, 'secret' => '4cgh2Ofb8uMwbwd8LGFE', 'tech' => '05c8b86d05c8b86d059447811e0591e600005c805c8b86d5d7c98772e53b6e5674481b1'),
            'app4subscribe' => array('id' => 5898182, 'secret' => '488MU3wjAyLS2YdfCR3W', 'tech' => '0633a3aa0633a3aa066f5c464d066a5c6c006330633a3aa5e8780310720cf8eec0b6387'),
            'app4subscribe2' => array('id' => 6747989, 'secret' => 'VLbA0sp8Eb9niA9GjKFu', 'tech' => '70531e6f70531e6f70531e6f0d7035e93a7705370531e6f2bb40d552e0de9b58943773d'),
        ],
        'name'=>'MIROVID'
    ];
} else {
    return [
        'adminEmail' => 'novavid@admin.ru',
        'supportEmail' => 'novavid@support.ru',
        'path_env' => '',
        'domain'=>'mirovid.ru',
        'vk'=>[
            'main' => array('group_id' => 999999, 'name' => 'mirovid'),

            'link_verify_callback' => /*FULL_HOST .*/ "http://mirovid.ru/webhook/vk",
            'redirect' => (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] ? REQUEST_SCHEME . "://{$_SERVER['HTTP_HOST']}" : FULL_HOST) . "/main/default/verify",

            'app4auth' => [
                8209106 => ['secret' => 'nAiXixFZXO9PTpD2E0ud'], //Mirovid.ru #1
            ],
            'app4user' => array('id' => 5856877, 'secret' => '4cgh2Ofb8uMwbwd8LGFE', 'tech' => '05c8b86d05c8b86d059447811e0591e600005c805c8b86d5d7c98772e53b6e5674481b1'),
            'app4subscribe' => array('id' => 5898182, 'secret' => '488MU3wjAyLS2YdfCR3W', 'tech' => '0633a3aa0633a3aa066f5c464d066a5c6c006330633a3aa5e8780310720cf8eec0b6387'),
            'app4subscribe2' => array('id' => 6747989, 'secret' => 'VLbA0sp8Eb9niA9GjKFu', 'tech' => '70531e6f70531e6f70531e6f0d7035e93a7705370531e6f2bb40d552e0de9b58943773d'),
        ],
        'name'=>'MIROVID'
    ];
}