<?php

namespace app\modules\zapros\models;

use Yii;

/**
 * This is the model class for table "cl_element".
 *
 * @property int $id
 * @property string $name
 * @property string|null $shortname
 * @property int $parent_id
 * @property int $classification_id
 * @property int|null $table_name
 * @property int|null $table_id
 * @property int|null $ord
 * @property string|null $comment
 * @property int $fldel
 */
class ClElement extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cl_element';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string'],
            [['parent_id', 'classification_id', 'table_name', 'table_id', 'ord', 'fldel'], 'integer'],
            [['shortname'], 'string', 'max' => 128],
            [['comment'], 'string', 'max' => 4096],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'shortname' => 'Shortname',
            'parent_id' => 'Parent ID',
            'classification_id' => 'Classification ID',
            'table_name' => 'Table Name',
            'table_id' => 'Table ID',
            'ord' => 'Ord',
            'comment' => 'Comment',
            'fldel' => 'Fldel',
        ];
    }
}
