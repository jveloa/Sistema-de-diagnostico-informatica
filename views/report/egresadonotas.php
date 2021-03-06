<?php
use app\models\db\Estudiante;
use app\models\db\egresadoDe;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;

?>
<?php
$form1 = ActiveForm::begin();

$egresadoData = app\models\db\EgresadoDe::find()->all();
$listaEgresado = \yii\helpers\BaseArrayHelper::map($egresadoData, 'id', 'lugar');
$cursoData = app\models\db\Curso::find()->all();
$listaCursos = \yii\helpers\BaseArrayHelper::map($cursoData, 'id', 'curso')

?>
<div class="row">
    <div class="col">  Datos de las notas de ingreso para el lugar de egreso seleccionado</div>
</div>

<div class="p-2" style="width: 300px " >
    <?= $form1->field($mymodel, 'cursoid')->dropdownList($listaCursos,
        ['prompt'=>'Seleccione',
            'options'=>[$seleccionCurso=>['selected'=>true]]]);
    ?>
</div>

<div class="p-2" style="width: 300px " >
    <?= $form1->field($mymodel, 'egresoid')->dropdownList($listaEgresado,
        ['prompt'=>'Seleccione',
            'options'=>[$seleccionEgresado=>['selected'=>true]]]);
    ?>
</div>

<div class="form-group ">
    <?=  Html::submitButton('Seleccionar', ['class' => 'btn btn-primary']) ?>
</div>
<?php $form1 = ActiveForm::end();

if ($seleccionEgresado != "" && $seleccionCurso != "") {?>

    <?=      GridView::widget([
        'dataProvider' => $dataProvider,
        'id' => 'gv',

        'columns' => [

            'tipo',
            'máximo',
            'mínimo',
            'promedio',

        ],
    ]);


}
elseif (($seleccionEgresado == "" && $seleccionCurso != "") || ($seleccionEgresado != "" && $seleccionCurso == "")){

    echo '<script>alert("Debe seleccionar ambos campos")</script>';


}?>

