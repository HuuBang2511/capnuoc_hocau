<?php

namespace app\modules\quanly\controllers\capnuocgd;

use app\modules\quanly\base\QuanlyBaseController;
use app\modules\services\CategoriesService;
use Yii;
use app\modules\quanly\models\capnuocgd\GdSuco;
use app\modules\quanly\models\capnuocgd\GdSucoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\modules\quanly\base\UploadFile;

use yii\web\UploadedFile;



/**
 * GdSucoController implements the CRUD actions for GdSuco model.
 */
class GdSucoController extends QuanlyBaseController
{

    public $title = "Sự cố - Điểm bể";

    /**
     * Lists all GdSuco models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GdSucoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'categories' => CategoriesService::getDanhmuc_suco(),
        ]);
    }


    /**
     * Displays a single GdSuco model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new GdDonghoKhGd model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new GdSuco();

        $hinhanh = new UploadFile();

        if ($model->load($request->post()) && $hinhanh->load($request->post())) {
            $model->save();

            $hinhanh->imageupload = UploadedFile::getInstances($hinhanh, 'imageupload');

            //dd($hinhanh);

            if($hinhanh->imageupload != null){
                $filehinhanh = [];
                foreach($hinhanh->imageupload as $i => $item){
                    if(strpos($item->name, "'") == true){
                        $item->name = str_replace("'","_",$item->name);
                    }

                    $filehinhanh[] = 'uploads/files/gd-suco/'.$model->id.'/'.$item->baseName.'.'.$item->extension;
                    $path = 'uploads/files/gd-suco/'.$model->id.'/';
                    $hinhanh->uploadFile($path, $item);
                }

                $model->file = json_encode($filehinhanh);
            }

            //dd($model);

            $model->save();

            Yii::$app->db->createCommand("UPDATE gd_suco SET geom = ST_GeomFromText('POINT($model->long"." "."$model->lat)', 4326) WHERE id = :id")
            ->bindValue(':id', $model->id)
            ->execute();
            Yii::$app->db->createCommand("UPDATE gd_suco SET geojson = st_asgeojson(ST_GeomFromText('POINT($model->long"." "."$model->lat)', 4326)) WHERE id = :id")
            ->bindValue(':id', $model->id)
            ->execute();

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'categories' => CategoriesService::getDanhmuc_suco(),
                'hinhanh' => $hinhanh,
            ]);
        }
    }

    /**
     * Updates an existing GdDonghoKhGd model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        $hinhanh = new UploadFile();

        if ($model->load($request->post()) && $hinhanh->load($request->post())) {

            $hinhanh->imageupload = UploadedFile::getInstances($hinhanh, 'imageupload');

            //dd($hinhanh);

            if($hinhanh->imageupload != null){
                $filehinhanh = [];
                foreach($hinhanh->imageupload as $i => $item){
                    if(strpos($item->name, "'") == true){
                        $item->name = str_replace("'","_",$item->name);
                    }

                    $filehinhanh[] = 'uploads/files/gd-suco/'.$model->id.'/'.$item->baseName.'.'.$item->extension;
                    $path = 'uploads/files/gd-suco/'.$model->id.'/';
                    $hinhanh->uploadFile($path, $item);
                }

                $model->file = json_encode($filehinhanh);
            }

            $model->save();

            Yii::$app->db->createCommand("UPDATE gd_suco SET geom = ST_GeomFromText('POINT($model->long"." "."$model->lat)', 4326) WHERE id = :id")
            ->bindValue(':id', $model->id)
            ->execute();
            Yii::$app->db->createCommand("UPDATE gd_suco SET geojson = st_asgeojson(ST_GeomFromText('POINT($model->long"." "."$model->lat)', 4326)) WHERE id = :id")
            ->bindValue(':id', $model->id)
            ->execute();

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'categories' => CategoriesService::getDanhmuc_suco(),
                'hinhanh' => $hinhanh,
            ]);
        }
    }

    /**
     * Delete an existing GdDonghoKhGd model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->status = 0;

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Xóa #" . $id,
                    'content' => $this->renderAjax('delete', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Đóng', ['class' => 'btn btn-light float-right', 'data-bs-dismiss' => "modal"]) .
                        Html::button('Xóa', ['class' => 'btn btn-danger float-left', 'type' => "submit"])
                ];
            } else if ($request->isPost && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Xóa thành công #" . $id,
                    'content' => '<span class="text-success">Xóa thành công</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-light float-right', 'data-bs-dismiss' => "modal"])
                ];
            } else {
                return [
                    'title' => "Update #" . $id,
                    'content' => $this->renderAjax('delete', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-light float-right', 'data-bs-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        }
    }

    
    /**
     * Finds the GdSuco model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GdSuco the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GdSuco::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
