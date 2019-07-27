<?php
use yii\helpers\Html;



echo $form->field($mem,'status')->dropDownList(\app\modules\block\models\Msg::$arrTxtStatus);
echo $form->field($mem,'copy_mem_id')->dropDownList($mem->getHierarchyMemByBlock($mem->id),['prompt'=>'']);
echo Html::a('Удалить mem',['/admin/block/default/mem-delete','id'=>$mem->id]);
?>