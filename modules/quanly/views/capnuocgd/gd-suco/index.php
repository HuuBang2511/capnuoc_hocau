<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap4\Modal;
use app\widgets\crud\CrudAsset;
use app\widgets\gridview\GridView;
use app\widgets\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\quanly\models\capnuocgd\GdSucoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = (isset($const['title'])) ? $const['title'] : 'Sự cố - Điểm bể';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);
$this->registerCss('
    @media (max-width: 767px) {
        .table-responsive .table th,
        .table-responsive .table td {
            white-space: nowrap;
        }
        .table-responsive .table tbody tr td:before {
            content: attr(data-label);
            display: inline-block;
            font-weight: bold;
            margin-right: 5px;
        }
    }
');
?>
<div class="gd-suco-index">
    <div id="table-responsive">
        <?=$this->render('_search', ['model' => $searchModel, 'categories' => $categories])?>
        <?php $fullExportMenu = ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => $searchModel->getExportColumns(),
            'target' => ExportMenu::TARGET_BLANK,
            'pjaxContainerId' => 'kv-pjax-container',
            'exportContainer' => [
                'class' => 'btn-group mr-2'
            ],
            'exportConfig' => [
                ExportMenu::FORMAT_TEXT => false,
                ExportMenu::FORMAT_HTML => false,
                ExportMenu::FORMAT_EXCEL => false,
                ExportMenu::FORMAT_PDF => false,
            ],
            'columnSelectorOptions' => ['class' => 'btn btn-outline-info','label' => 'Chọn cột'],
            'dropdownOptions' => [
                'label' => 'XUẤT FILE',
                'itemsBefore' => [
                    '<div class="dropdown-header">Xuất tất cả dữ liệu</div>',
                ],
            ],
        ]) ?>
        <?=GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'columns' => require(__DIR__.'/_columns.php'),
            'toolbar'=> [
                $fullExportMenu,
                ['content'=>
                    Html::a('<i class="fa fa-plus"></i> Thêm mới', ['create'],
                    ['data-pjax'=>0,'title'=> 'Thêm mới Sự cố - Điểm bể','class'=>'btn btn-success'])
                ],
            ],          
            'striped' => true,
            'condensed' => true,
//            'responsive' => false,
            'responsiveWrap' => false,
            'panelPrefix' => 'block ',
            'toolbarContainerOptions' => ['class' => 'float-right'],
            'summaryOptions' => ['class' => 'float-right'],
            'panel' => [
                'type' => 'block-themed',
                'headingOptions' => ['class' => 'block-header'] ,
                'summaryOptions' => ['class' => 'block-options'],
                'titleOptions' => ['class' => 'block-title'] ,
                'heading' => '<i class="fa fa-list"></i> ' .  $this->title ,
            ],
            'tableOptions' => ['class' => 'table table-striped'],
            'layout' => "{items}\n{pager}",
        ])?>
    </div>
</div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
