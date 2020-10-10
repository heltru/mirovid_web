<?php

namespace app\modules\test\controllers\frontend;

use app\modules\car\models\Car;
use app\modules\helper\models\Helper;
use app\modules\test\app\SiteError;
use app\modules\test\models\CityTransport;
use app\modules\test\models\CityTransportCheck;
use app\modules\test\models\CityTransportStat;
use app\modules\user\models\User;
use app\modules\zapros\models\ClElement;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\Controller;
use Yii;
use yii\base\Model;


/**
 * Default controller for the `test` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */

    public $email = 'laneo2007@yandex.ru';

    public $str = [];

    private $data = [
        [
            "utm_source" => "yandex",
            "utm_medium" => "display",
            "utm_campaign" => "banner",
            "utm_content" => "makar",
            "utm_term" => "free"
        ],
        [
            "utm_source" => "google",
            "utm_medium" => "retargeting",
            "utm_campaign" => "general",
            "utm_content" => "ivan",
            "utm_term" => "keyword"
        ],
        [
            "utm_source" => "yandex",
            "utm_medium" => "email",
            "utm_campaign" => "general",
            "utm_content" => "makar",
            "utm_term" => "beget"
        ],
        [
            "utm_source" => "yandex",
            "utm_medium" => "display",
            "utm_campaign" => "promo",
            "utm_content" => "ivan",
            "utm_term" => "camogon"
        ],
        [
            "utm_source" => "yandex",
            "utm_medium" => "affiliate",
            "utm_campaign" => "banner",
            "utm_content" => "dima",
            "utm_term" => "beget"
        ],
        [
            "utm_source" => "google",
            "utm_medium" => "retargeting",
            "utm_campaign" => "promo",
            "utm_content" => "oleg",
            "utm_term" => "beget"
        ],
        [
            "utm_source" => "yandex",
            "utm_medium" => "social_cpc",
            "utm_campaign" => "promo",
            "utm_content" => "test_A",
            "utm_term" => "free"
        ],
        [
            "utm_source" => "facebock",
            "utm_medium" => "email",
            "utm_campaign" => "kupti_vel",
            "utm_content" => "kirov",
            "utm_term" => "sony"
        ],
        [
            "utm_source" => "google",
            "utm_medium" => "email",
            "utm_campaign" => "banner",
            "utm_content"   => "oleg",
            "utm_term" => "koptil"
        ],
    ];

    private $html_grid = [];

    private $table = [];
    private $car = 0;

    private function getAllList()
    {
        $list = [];

        foreach ($this->data as $item) {
            $par = 'nil';

            $ids = [];
            foreach ($item as $utm_type => $value) {
                $id = $value.'_'.$utm_type.'_'.implode('_',$ids);
                $list[] = [
                    'id' => $id,
                    'name' => $value,
                    'parent_id' => $par
                ];
                $ids[] = $value;
                $par = $id;
            }
        }

        return $list;
    }

    public function actionTest()
    {


        $tree = $this->radioTree();
echo '<style> td { border: 1px solid black } </style>';
        echo '<table>';
        foreach ($this->table as $item){
            echo '<tr>';

            foreach ($item as $td){
                echo ($td[1] > 1) ?  "<td  rowspan='$td[1]'>" : '<td>';
                echo $td[0];
                echo '</td>';
            }

            echo '</tr>';
        }
        echo '</table>';
        exit;
ex($this->table);

        return $this->render('test', ['data' => $this->radioTree()]);


    }


    public function radioTree()
    {

        $data = $this->makeTree();

        if ($data === null) {
            return [];
        }


        $data = $this->treeDataRec($data);
//ex($data);
        return $data;
    }
    private function treeDataRec($data)
    {
        $res = [];
        foreach ($data as $num => $item) {
            $a = ['text' => $item['text']['name'],'count'=>1];

            $this->table[$this->car][] = [ $item['text']['name'], $item['count'] ] ;

            if (isset($item['nodes'])) {
                $a['nodes'] = $this->treeDataRec($item['nodes']);

                /*
                $c = 0;
                foreach ($a['nodes'] as $node_item){
                    $c += $node_item['count'];
                }
                $a['count'] = $c;
*/

                //$a['text'] = $a['text'] . ' ' . $c;
                //$this->table[$this->car]['rowspan'] = $c;
            } else {

                $this->car++;
            }



            $res[] = $a;
        }



        return $res;
    }
    public function makeTree()
    {

        $list = $this->getAllList();
      
        $struct = [];
        foreach ($list as $item) {
            $struct[$item['parent_id']][$item['id']] = $item;
        }
  

        $data = $this->recursiveTree($struct);

        return $data;
    }
    private function recursiveTree($data, $parent = 'nil')
    {
        $res = array();
        if (!isset ($data[$parent]))
            return NULL;
        $i = 1;
        foreach ($data[$parent] as $key => $value) {
            $res[$value['id']] = array(
                'text' => array("name" => $value['name'], 'id' => $value['id']),
                'count'=>1
            );
            $res[$value['id']]['nodes'] = $this->recursiveTree($data, $value['id']);



            if ($res[$value['id']]['nodes'] == NULL){
                unset($res[$value['id']]['nodes']);
            }else {
                $c = 0;
                foreach ($res[$value['id']]['nodes'] as $node_item){
                    $c += $node_item['count'];
                }
                $res[$value['id']]['count'] = $c;
            }


        }
        return $res;
    }

  

    private function countRecursive($in)
    {
        $count = 1;
        if($in)
            $res = $in;
        else
            return $count;


        foreach ($res as $key => $value)
        {

            if(isset($value['nodes']))
                $count += $this->countRecursive($res[$key]['nodes']);
        }
        return $count;

    }







    private function recursiveTreeA($data, $parent = 'nil')
    {
        $res = array();
        if (!isset ($data[$parent]))
            return NULL;
        $i = 1;
        foreach ($data[$parent] as $key => $value) {
            $res[$value['id']] = array(
                'text' => array("name" => $value['name'], 'id' => $value['id'], 'ord' => $i++),
                'expanded' => TRUE,
            );
            $res[$value['id']]['children'] = $this->recursiveTreeA($data, $value['id']);
            if ($res[$value['id']]['children'] == NULL)
                unset($res[$value['id']]['children']);
        }
        return $res;
    }

    private function getTree()
    {
        $all = ClElement::find()->where('fldel=0 AND classification_id=4 ORDER BY parent_id, ISNULL(ord), ord, name')->all();

        $docs = array();
        foreach ($all as $key => $value)
            $docs[$value->parent_id][$value->id] = $value;//->name;

        ex($docs);
        $data = $this->recursiveTree($docs);
        return $data;
    }


    private function tree($item, $par = 'utm_source')
    {
        foreach ($item as $key => $value) {
            if ($key == $par) {
                continue;
            }

            $struct[$value] = ['text' => $value];
            $struct[$value]['child'] = $this->tree($item);


        }
    }


    private function getTree1($data, $parent = 'null')
    {
        $res = array();
        //    ex($data[$parent]);
        if (!isset ($data[$parent]))
            return NULL;
        $i = 1;
        foreach ($data[$parent] as $key => $value) {
            $res[$value] = array('text' => $value, 'ord' => $i++/*array("name"=>$value)*/);
            $res[$value]['children'] = $this->getTree($data, $value);
            if ($res[$value]['children'] == NULL)
                unset($res[$value]['children']);
        }
        return $res;
    }

    private function recursiveTree1($data, $parent = 0)
    {
        $res = array();
        if (!isset ($data[$parent]))
            return NULL;
        $i = 1;
        foreach ($data[$parent] as $key => $value) {
            $res[$value->id] = array(
                'text' => array("name" => $value->name, 'id' => $value->id, 'ord' => $i++),
                'expanded' => TRUE,
            );
            $res[$value->id]['children'] = $this->recursiveTree($data, $value->id);
            if ($res[$value->id]['children'] == NULL)
                unset($res[$value->id]['children']);
        }
        return $res;
    }


    function actionVk()
    {
        $this->layout = false;
        return $this->render('vk');
    }


    function actionTestEmail()
    {

        Yii::$app->mailer->compose()
            ->setFrom('info@mirovid.ru')
            ->setTo('mirovidweb@yandex.ru')
            ->setSubject('test')
            ->setTextBody('test')
            ->setHtmlBody('test a')
            ->send();


    }

    private
    function checkCarTimeLimit($car, $area)
    {
        $date = Helper::mysql_datetime(strtotime("+25 minutes"));
        $old = CityTransportCheck::find()->where(['>', 'date', $date])
            ->andWhere(['gn' => $car['gn']])
            ->andWhere(['area' => $area])
            ->andWhere(['number' => $car['number']])
            ->one();

        return $old === null;

    }


    function actionTestTimeF()
    {
        return $this->render('t');
    }


    public
    function actionTestTime()
    {
        set_time_limit(0);

        $sqrs = [[[58.587693, 49.621885], [58.593872, 49.636380]], [[58.621161, 49.638791], [58.627197, 49.651354]]];

        $routes = [1090, 1054, 1033, 1037, 1017, 1051, 1046, 1053, 1074, 1061, 1001, 1023, 1022, 1010, 1016, 1044, 1002, 1070, 1012, 1039, 1088, 1014, 1087, 1021, 1084, 5007, 5005, 5008, 5014, 5001, 5004, 5003];


        $t = [];

        foreach ($routes as $route) {
            $d = file_get_contents('https://cdsvyatka.com/api/kirov/map/route/' . $route . '/transport');

            $d = Json::decode($d);
            $t[] = $d;
            foreach ($d as $car) {

                foreach ($sqrs as $num_area => $sqr) {

                    $lat = $car['lat'] >= $sqr[0][0] && $car['lat'] <= $sqr[1][0];
                    $long = $car['lng'] >= $sqr[0][1] && $car['lng'] <= $sqr[1][1];

                    if ($lat && $long && $this->checkCarTimeLimit($car, $num_area)) {
                        $rec = new CityTransportCheck();
                        $rec->long = $car['lng'];
                        $rec->lat = $car['lat'];
                        $rec->gn = $car['gn'];
                        $rec->number = $car['number'];
                        $rec->date = $car['date'];
                        $rec->route_id = $route;
                        $rec->area = $num_area;
                        $rec->save();
                        if ($rec->getErrors()) {
                            ex($rec->getErrors());
                        }

                    }
                }


            }
            sleep(rand(1, 3));
        }
//ex($t);


    }

    public
    function actionTestTime1()
    {
        set_time_limit(0);

        $sqrs = [[[58.621161, 49.638791], [58.627197, 49.651354]]];

        $routes = [1090, 1054, 1033, 1037, 1017, 1051, 1046, 1053, 1074, 1061, 1001, 1023, 1022, 1010, 1016, 1044, 1002, 1070, 1012, 1039, 1088, 1014, 1087, 1021, 1084, 5007, 5005, 5008, 5014, 5001, 5004, 5003];


        foreach ($routes as $route) {
            $d = file_get_contents('https://cdsvyatka.com/api/kirov/map/route/' . $route . '/transport');

            $d = Json::decode($d);
            foreach ($d as $car) {

                foreach ($sqrs as $num_area => $sqr) {

                    $lat = $car['lat'] >= $sqr[0][0] && $car['lat'] <= $sqr[1][0];
                    $long = $car['lng'] >= $sqr[0][1] && $car['lng'] <= $sqr[1][1];

                    if ($lat && $long && $this->checkCarTimeLimit($car, 1)) {
                        $rec = new CityTransportCheck();
                        $rec->long = $car['lng'];
                        $rec->lat = $car['lat'];
                        $rec->gn = $car['gn'];
                        $rec->number = $car['number'];
                        $rec->date = $car['date'];
                        $rec->route_id = $route;
                        $rec->area = 1;
                        $rec->save();
                        if ($rec->getErrors()) {
                            ex($rec->getErrors());
                        }

                    }
                }


            }
            sleep(rand(1, 3));
        }

    }


    function actionTest9()
    {

        $this->layout = false;
        return $this->render('a');

        /*
        Yii::$app->mailer->compose(['text' => '@app/modules/user/mails/test'])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
            ->setTo($this->email)
            ->setSubject('Email confirmation for ' . Yii::$app->name)
            ->send();
        mail('test@test.com','Email confirmation for ' . Yii::$app->name,'test');
        echo '123';
        */
        /*
        Yii::$app->mailer->compose()
            ->setFrom('89991002878@mail.ru')
            // ->setTo('757537s@mail.ru')
            ->setTo('laneo2007@yandex.ru')
            ->setSubject('Заказ звонка с сайта novaferm.ru')
            //->setTextBody('Ваша заявка №'.$model->id.' принята. В течении недели мы свяжимся с вами, по телефону или по почте.')
            ->setHtmlBody(' ФИО ')
            ->send();
        */
        /*
        $user = new User();
        $user->username ='123';
        $user->email = 'test@test.test';
        $user->setPassword(3423);
        $user->status = User::STATUS_WAIT;

        mail($this->email,
            'Email confirmation for ' . Yii::$app->name,
            Yii::$app->getView()->renderFile('@app/modules/user/mails/emailConfirm.php',['user' => $user])
        );*/
    }


}
