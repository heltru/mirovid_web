<?php

namespace app\commands;


use Yii;
use yii\console\Controller;

/**
 * RBAC generator
 *  //https://anart.ru/yii2/2016/04/11/yii2-rbac-ponyatno-o-slozhnom.html
 */
class RbacController extends Controller
{
    /**
     * Generates roles
     */
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        $auth->removeAll(); //На всякий случай удаляем старые данные из БД...

        // Создадим роли админа и редактора новостей
        $admin = $auth->createRole('admin');
        $client = $auth->createRole('client');

        // запишем их в БД
        $auth->add($admin);
        $auth->add($client);


        // Создаем наше правило, которое позволит проверить автора комента
        //  $authorRule = new AuthorRule();
        // Запишем его в БД
        //   $auth->add($authorRule);

        // Создаем разрешения. Например, просмотр админки viewAdminPage и фейковый автор updateNews
        //  $viewAdminPage = $auth->createPermission('viewAdminPage');
        //   $viewAdminPage->description = 'Просмотр админки';

        //    $updateComments = $auth->createPermission('updateComments');
        //   $updateComments->description = 'Редактирование комента';

        // Создадим еще новое разрешение «Редактирование собственного комента» и ассоциируем его с правилом AuthorRule
        //     $updateOwnComments = $auth->createPermission('updateOwnComments');
        //    $updateOwnComments->description = 'Редактирование собственного коммента';

        // Указываем правило AuthorRule для разрешения updateOwnNews.
        //  $updateOwnComments->ruleName = $authorRule->name;

        // Запишем эти разрешения в БД
        //  $auth->add($viewAdminPage);
        //   $auth->add($updateComments);
        //   $auth->add($updateOwnComments);

        // Теперь добавим наследования. Для роли editor мы добавим разрешение updateNews,
        // а для админа добавим наследование от роли editor и еще добавим собственное разрешение viewAdminPage

        //    $auth->addChild($updateOwnComments,$updateComments);

        // Роли «Редактор фековых комментов» присваиваем разрешение «Редактирование собственной новости»
        //  $auth->addChild($fakeuser,$updateOwnComments);

        // админ имеет собственное разрешение - «Редактирование коммента»
        // $auth->addChild($admin, $updateComments);

        // админ наследует роль редактора новостей. Он же админ, должен уметь всё! :D
        $auth->addChild($admin, $client);

        // Еще админ имеет собственное разрешение - «Просмотр админки»
        //    $auth->addChild($admin, $viewAdminPage);

        // Назначаем роль admin пользователю с ID 1
        $auth->assign($admin, 1);
        //   $auth->assign($admin, 25);

        // Назначаем роль editor пользователю с ID 2
        // $auth->assign($fakeuser, 25);

        $this->stdout('Done!' . PHP_EOL);
    }
}