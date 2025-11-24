<?php


namespace app\modules\quanly\controllers;


use app\modules\quanly\base\QuanlyBaseController;
use app\modules\quanly\models\aphu\DonghoKh;
use app\modules\quanly\models\aphu\NhamayNuoc;
use app\modules\quanly\models\aphu\VanMangluoi;
use app\modules\quanly\models\capnuocgd\GdDonghoKhGd;
use app\modules\quanly\models\capnuocgd\GdDonghoTongGd;
use app\modules\quanly\models\capnuocgd\GdOngcai;
use app\modules\quanly\models\capnuocgd\GdVanphanphoi;
use app\modules\quanly\models\Ktvhxh;
use app\modules\quanly\models\aphu\OngPhanphoi;
use app\modules\quanly\models\capnuocgd\GdSuco;
use app\modules\quanly\models\capnuocgd\GdTrambom;
use app\modules\quanly\models\capnuocgd\GdTramcuuhoa;
use app\modules\quanly\models\capnuocgd\GdHamkythuat;
use app\modules\quanly\models\capnuocgd\DMA;
use Yii;

class DashboardController extends QuanlyBaseController
{
    public function actionIndex()
    {
        $thongke['dongho_kh'] = GdDonghoKhGd::find()->count();
        $thongke['nhamay_nuoc'] = GdDonghoTongGd::find()->count();
        $thongke['van_mangluoi'] = GdVanphanphoi::find()->count();
        $thongke['ong_phanphoi'] = GdOngcai::find()->select('shape_leng')->sum('shape_leng');
        $thongke['ong_phanphoi'] = round($thongke['ong_phanphoi']/1000);
        $thongke['suco'] = GdSuco::find()->count();
        $thongke['pccc'] = GdTramcuuhoa::find()->count();
        $thongke['trambom'] = GdTrambom::find()->count();
        $thongke['ham'] = GdHamkythuat::find()->count();

        $dataMap = [];

        $geojsonDma = DMA::find()->select(['st_asgeojson(ST_Transform(geom, 4326))', 'madma'])->orderBy('geom')->asArray()->all();

        //dd($geojsonDma);
        foreach($geojsonDma as $i => $item){
            $geojson = json_decode($item['st_asgeojson']);
            //dd($geojson->coordinates[0][0]);
            $dataMap[] = [
                "coordinates" => json_encode($geojson->coordinates[0][0]),
                "name" => $item['madma'],
                "id" => $item['madma'],
            ];
        }

        $sovanDma = Yii::$app->db
        ->createCommand('
        SELECT id, madma as name, sovan as value  FROM "v2_4326_DMA"  order by madma
        ')
        ->queryAll();


        //dd(($sovanDma));



        return $this->render('index', [
            'thongke' => $thongke,
            'dataMap' => $dataMap,
            'sovanDma' => $sovanDma,
        ]);
    }

    public function actionGeojson(){
        $dmas = Yii::$app->db->createCommand('SELECT st_asgeojson(geom) as geometry, madma as ten, id  FROM "v2_4326_DMA" order by madma')->queryAll();

        $g  = [];

        foreach ($dmas as $i => $dma) {
            $geometry = json_decode($dma['geometry'], true);
            $g[$i] = [
                'type' => 'Feature',
                'id' => $dma['id'],
                'properties' => [
                    'name' => $dma['ten'],
                ],
                'geometry' => [
                    'type' => $geometry['type'],
                    'coordinates' => $geometry['coordinates'],
                ]
            ];
        }

        $e = [
            'type' => 'FeatureCollection',
            'features' => $g
        ];

        //dd($e);
        return json_encode($e, JSON_UNESCAPED_UNICODE);

        //dd($results);
    }

    public function actionChitietdma($id){

        

      
       

       return $this->renderAjax('chitietdma', [
           'id'=>$id,
           
       ]); 
   }


}