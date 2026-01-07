<?php

namespace app\modules\quanly\controllers\hocau;

use Yii;
use app\modules\quanly\models\hocau\Suco;
use app\modules\quanly\models\hocau\SucoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\modules\quanly\base\QuanlyBaseController;
use app\modules\quanly\base\UploadFile;
use yii\web\UploadedFile;
use app\modules\services\CategoriesService;
/**
 * SucoController implements the CRUD actions for Suco model.
 */
class SucoController extends QuanlyBaseController
{

    public $title = "Sự cố";

    /**
     * Lists all Suco models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SucoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'categories' => CategoriesService::getCategories(),
        ]);
    }


    /**
     * Displays a single Suco model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if($model->file_dinhkem != null){
            $filedinhkem = json_decode($model->file_dinhkem);

            $files = [];

            foreach($filedinhkem as $i => $item){

                $filename = basename($item); 
                $filename = str_replace(' ', '_', $filename);

                $files[$i]['url'] = $item;
                $files[$i]['name'] = $filename;
                
            }
        }else{
            $files = null;
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
            'files' => $files,
        ]);
    }

    /**
     * Creates a new Suco model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Suco();

        $filedinhkem = new UploadFile();

        if($model->load($request->post()) && $model->save() && $filedinhkem->load($request->post())){
            
            $filedinhkem->fileupload = UploadedFile::getInstances($filedinhkem, 'fileupload');

            if($filedinhkem->fileupload != null){
                //dd($filedinhkem->fileupload);
                $file = [];
                foreach($filedinhkem->fileupload as $i => $item){
                    if(strpos($item->name, "'") == true){
                        $item->name = str_replace("'","_",$item->name);
                    }

                    $file[] = 'uploads/suco/'.$model->id.'/'.$item->baseName.'.'.$item->extension;
                    $path = 'uploads/suco/'.$model->id.'/';

                    $filedinhkem->uploadFile($path, $item);
                }

                $model->file_dinhkem = json_encode($file);
                $model->save();
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }


        return $this->render('create', [
            'model' => $model,
            'filedinhkem' => $filedinhkem,
            'categories' => CategoriesService::getCategories(),
        ]);

    }

    /**
     * Updates an existing Suco model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        $filedinhkem = new UploadFile();

        if($model->load($request->post()) && $model->save() && $filedinhkem->load($request->post())){
            
            $filedinhkem->fileupload = UploadedFile::getInstances($filedinhkem, 'fileupload');

            if($filedinhkem->fileupload != null){
                //dd($filedinhkem->fileupload);
                $file = [];
                foreach($filedinhkem->fileupload as $i => $item){
                    if(strpos($item->name, "'") == true){
                        $item->name = str_replace("'","_",$item->name);
                    }

                    $file[] = 'uploads/suco/'.$model->id.'/'.$item->baseName.'.'.$item->extension;
                    $path = 'uploads/suco/'.$model->id.'/';

                    $filedinhkem->uploadFile($path, $item);
                }

                $model->file_dinhkem = json_encode($file);
                $model->save();
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }


        return $this->render('update', [
            'model' => $model,
            'filedinhkem' => $filedinhkem,
            'categories' => CategoriesService::getCategories(),
        ]);
    }

    /**
     * Delete an existing Suco model.
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
                    'title' => "Xóa sự cố #" . $id,
                    'content' => $this->renderAjax('delete', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Đóng', ['class' => 'btn btn-light float-right', 'data-bs-dismiss' => "modal"]) .
                        Html::button('Xóa', ['class' => 'btn btn-danger float-left', 'type' => "submit"])
                ];
            } else if ($request->isPost && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Xóa sự cố thành công #" . $id,
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
     * Finds the Suco model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Suco the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Suco::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
