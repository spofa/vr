<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%scene}}".
 *
 * @property integer $id
 * @property integer $pro_id
 * @property string $name
 * @property string $title
 * @property string $thumburl
 */
class Scene extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%scene}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'pro_id'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['title', 'thumburl'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pro_id' => '所属项目ID',
            'name' => '场景名称',
            'title' => '场景标题',
            'thumburl' => '场景缩略图',
        ];
    }


    //获取场景信息 组成所谓的XML_DATA （实际上为JSON）
    public static function getSeneInfo($proId)
    {

        $where = " and prj.productid=".$proId;
        $sql = "select scene.*,view.*,hotspots.* from leju_product as prj
                RIGHT  join leju_scene as scene on prj.productid = scene.pro_id ".$where."
                RIGHT  JOIN  leju_view as view on view.scene_id =  scene.id
                RIGHT  JOIN  leju_hotspots as hotspots on hotspots.scene_id = scene.id";

        $data =  Scene::findBySql($sql);

        return json_encode($data);
    }

    //获取场景的name id 信息 有可能多个；
    public static function getSceneName($proId)
    {

       $data =  Scene::findBySql("select name,id from leju_scene where pro_id =".$proId);
        return $data;
    }


    //更新对应的HOTSPOTS表
    public static function editInfo($id,$arr)
    {
        $model = Scene::findOne($id);

        if(count($arr))
        {
            foreach($arr as $key => $value)
            {
                if($key != "hname")
                {
                    $model->$key = $value;
                }
            }
            $model->save();
        }

    }

}
