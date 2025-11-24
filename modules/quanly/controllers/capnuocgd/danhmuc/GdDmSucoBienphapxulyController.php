<?php

namespace app\modules\quanly\controllers\capnuocgd\danhmuc;



use app\modules\quanly\base\DanhmucBaseController;
use Yii;
use app\modules\quanly\models\capnuocgd\danhmuc\GdDmSucoBienphapxuly;
use app\modules\quanly\models\capnuocgd\danhmuc\GdDmSucoBienphapxulySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * GdDmSucoBienphapxulyController implements the CRUD actions for GdDmSucoBienphapxuly model.
 */
class GdDmSucoBienphapxulyController extends DanhmucBaseController
{

    public $title = "Danh mục sự cố biện pháp xử lý";

    /**
     * Lists all GdDmSucoBienphapxuly models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GdDmSucoBienphapxulySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single GdDmSucoBienphapxuly model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Danh mục sự cố biện pháp xử lý #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> Html::button('Đóng',['class'=>'btn btn-light float-right','data-bs-dismiss'=>"modal"]).
                            Html::a('Cập nhật',['update','id'=>$id],['class'=>'btn btn-primary float-left','role'=>'modal-remote'])
                ];
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new GdDmSucoBienphapxuly model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new GdDmSucoBienphapxuly();

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Thêm mới Danh mục sự cố biện pháp xử lý",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Lưu',['class'=>'btn btn-primary float-left','type'=>"submit"]).
                            Html::button('Đóng',['class'=>'btn btn-light float-right','data-bs-dismiss'=>"modal"])
                ];
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Thêm mới Danh mục sự cố biện pháp xử lý",
                    'content'=>'<span class="text-success">Thêm mới Danh mục sự cố biện pháp xử lý thành công</span>',
                    'footer'=> Html::button('Đóng',['class'=>'btn btn-light float-right','data-bs-dismiss'=>"modal"]).
                            Html::a('Tiếp tục thêm mới',['create'],['class'=>'btn btn-primary float-left','role'=>'modal-remote'])
                ];
            }else{
                return [
                    'title'=> "Create new Danh mục sự cố biện pháp xử lý",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Đóng',['class'=>'btn btn-light float-right','data-bs-dismiss'=>"modal"]).
                                Html::button('Lưu',['class'=>'btn btn-primary float-left','type'=>"submit"])

                ];
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }

    }

    /**
     * Updates an existing GdDmSucoBienphapxuly model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Cập nhật Danh mục sự cố biện pháp xử lý #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Đóng',['class'=>'btn btn-light float-right','data-bs-dismiss'=>"modal"]).
                                Html::button('Lưu',['class'=>'btn btn-primary float-left','type'=>"submit"])
                ];
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Danh mục sự cố biện pháp xử lý #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Đóng',['class'=>'btn btn-light float-right','data-bs-dismiss'=>"modal"]).
                            Html::a('Lưu',['update','id'=>$id],['class'=>'btn btn-primary float-left','role'=>'modal-remote'])
                ];
            }else{
                 return [
                    'title'=> "Cập nhật Danh mục sự cố biện pháp xử lý #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Đóng',['class'=>'btn btn-light float-right','data-bs-dismiss'=>"modal"]).
                                Html::button('Lưu',['class'=>'btn btn-primary float-left','type'=>"submit"])
                ];
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing GdDmSucoBienphapxuly model.
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
     * Finds the GdDmSucoBienphapxuly model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GdDmSucoBienphapxuly the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GdDmSucoBienphapxuly::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
