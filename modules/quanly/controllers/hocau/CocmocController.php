<?php

namespace app\modules\quanly\controllers\hocau;

use Yii;
use app\modules\quanly\models\hocau\Cocmoc;
use app\modules\quanly\models\hocau\CocmocSearch;
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
 * CocmocController implements the CRUD actions for Cocmoc model.
 */
class CocmocController extends QuanlyBaseController
{

    public $title = "Cọc mốc";

    /**
     * Lists all Cocmoc models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CocmocSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'categories' => CategoriesService::getCategories(),
        ]);
    }


    /**
     * Displays a single Cocmoc model.
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

                $filename = basename($item); // HDSD 1.2.pdf
                $filename = str_replace(' ', '_', $filename); // HDSD_1.2.pdf

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
     * Creates a new Cocmoc model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Cocmoc();

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

                    $file[] = 'uploads/cocmoc/'.$model->id.'/'.$item->baseName.'.'.$item->extension;
                    $path = 'uploads/cocmoc/'.$model->id.'/';

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
     * Updates an existing Cocmoc model.
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

                    $file[] = 'uploads/cocmoc/'.$model->id.'/'.$item->baseName.'.'.$item->extension;
                    $path = 'uploads/cocmoc/'.$model->id.'/';

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
     * Delete an existing Cocmoc model.
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
                    'title'=> "Xóa Cocmoc #".$id,
                    'content'=>$this->renderAjax('delete', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Đóng',['class'=>'btn btn-light float-right','data-bs-dismiss'=>"modal"]).
                        Html::button('Xóa',['class'=>'btn btn-danger float-left','type'=>"submit"])
                ];
            }else if($request->isPost && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Cocmoc #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-light float-right','data-bs-dismiss'=>"modal"]).
                        Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];
            }else{
                return [
                    'title'=> "Update Cocmoc #".$id,
                    'content'=>$this->renderAjax('delete', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-light float-right','data-bs-dismiss'=>"modal"]).
                        Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
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
     * Finds the Cocmoc model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Cocmoc the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cocmoc::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
