<?php

namespace app\modules\quanly\controllers\aphu;

use app\modules\quanly\base\QuanlyBaseController;
use Yii;
use app\modules\quanly\models\aphu\OngNuoctho;
use app\modules\quanly\models\aphu\SearchOngNuoctho;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * OngNuocthoController implements the CRUD actions for OngNuoctho model.
 */
class OngNuocthoController extends QuanlyBaseController
{

    public $title = "OngNuoctho";

    /**
     * Lists all OngNuoctho models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchOngNuoctho();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single OngNuoctho model.
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
     * Creates a new OngDichvu model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new OngNuoctho();

        if ($model->load($request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing OngDichvu model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($model->load($request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }

    }

    /**
     * Delete an existing OngDichvu model.
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

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Xóa Cung đường đến trường".$id,
                    'content'=>$this->renderAjax('delete', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Đóng',['class'=>'btn btn-light float-right','data-bs-dismiss'=>"modal"]).
                        Html::button('Xóa',['class'=>'btn btn-danger float-left','type'=>"submit"])
                ];
            }else if($request->isPost && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Xóa thành công #".$id,
                    'content' => '<span class="text-success">Xóa thành công</span>',
                    'footer'=> Html::button('Close',['class'=>'btn btn-light float-right','data-bs-dismiss'=>"modal"])
                ];
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('delete', [
                    'model' => $model,
                    'const' => $this->const,
                ]);
            }
        }
    }



    /**
     * Finds the OngNuoctho model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OngNuoctho the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OngNuoctho::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
