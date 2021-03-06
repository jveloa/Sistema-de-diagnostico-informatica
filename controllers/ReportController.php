<?php

namespace app\controllers;

use app\models\TiempoEstudioCarreraForm;
use app\models\FactoresCarreraForm;
use app\models\ArtesForm;
use app\models\db\RespSobreFuturo;
use app\models\DeportesForm;
use app\models\EgresadoForm;
use app\models\EgresadoNotasForm;
use app\models\EstadisticasCursoForm;
use app\models\EstudianteIndiceForm;
use app\models\EstudiantesFormasEstudiosForm;
use app\models\EstudiantesHabitosForm;
use app\models\EstudiantesHorasEstudiosForm;
use app\models\EstudiantesNoComputadoraForm;
use app\models\EstudiantesNotasIndiceForm;
use app\models\OpcionesDeCarreraForm;
use app\models\RazonesIngresoForm;
use app\models\ResponsabilidadesForm;
use app\models\ViaIngresoForm;
use Yii;
use yii\data\SqlDataProvider;
use yii\web\Controller;
use \yii\helpers\BaseArrayHelper;


class ReportController extends Controller
{
    ///Ferrer
    public function actionEgresado()
    {
        $model = new EgresadoForm;


        if ($model->load(Yii::$app->request->post())) {
            $valorRespuesta = $model->egresoid;
            $valorCurso = $model->cursoid;
            return $this->render('egresado',
                ['seleccionCurso' => $valorCurso,
                    'seleccionEgresado' => $valorRespuesta,
                    'mymodel' => $model,
                ]);
        }


        return $this->render('egresado',
            ['seleccionCurso' => "",
                'seleccionEgresado' => "",
                'mymodel' => $model,
            ]);

    }

    public function actionEgresadonotas()
    {
        $model1 = new EgresadoNotasForm;

        $dataProvider = '';

        if ($model1->load(Yii::$app->request->post())) {
            $valorRespuesta = $model1->egresoid;
            $valorCurso = $model1->cursoid;

            if ($valorRespuesta != "" && $valorCurso != "") {
                //condicion si hay seleccion
                $sql = "SELECT 'Índice Académico' as Tipo, Max(indice_academico) as Máximo, Min(indice_academico) as Mínimo, round(Avg(indice_academico),2) as Promedio FROM estudiante where id_egresado=:id_egresado and id_curso=:cursoid union SELECT 'Matemática' as Tipo, Max(nota_matematica) as Máximo, Min(nota_matematica) as Mínimo, round(Avg(nota_matematica),2) as Promedio FROM estudiante where id_egresado=:id_egresado and id_curso=:cursoid union SELECT 'Español' as Tipo, Max(nota_espannol) as Máximo, Min(nota_espannol) as Mínimo, round(Avg(nota_espannol),2) as Promedio FROM estudiante where id_egresado=:id_egresado and id_curso=:cursoid union SELECT 'Historia' as Tipo, Max(nota_historia) as Máximo, Min(nota_historia) as Mínimo, round(Avg(nota_historia),2) as Promedio FROM estudiante where id_egresado=:id_egresado and id_curso=:cursoid";


                $dataProvider = new SqlDataProvider([
                    'sql' => $sql,
                    'params' => [':id_egresado' => $valorRespuesta,
                        ':cursoid' => $valorCurso],

                ]);
            }

            return $this->render('egresadonotas',
                ['seleccionCurso' => $valorCurso,
                    'seleccionEgresado' => $valorRespuesta,
                    'dataProvider' => $dataProvider,
                    'mymodel' => $model1]);
        }
        //$sql = "SELECT 'Índice Académico' as Tipo, Max(indice_academico) as Máximo, Min(indice_academico) as Mínimo, round(Avg(indice_academico),2) as Promedio FROM estudiante union SELECT 'Matemática' as Tipo, Max(nota_matematica) as Máximo, Min(nota_matematica) as Mínimo, round(Avg(nota_matematica),2) as Promedio FROM estudiante union SELECT 'Español' as Tipo, Max(nota_espannol) as Máximo, Min(nota_espannol) as Mínimo, round(Avg(nota_espannol),2) as Promedio FROM estudiante union SELECT 'Historia' as Tipo, Max(nota_historia) as Máximo, Min(nota_historia) as Mínimo, round(Avg(nota_historia),2) as Promedio FROM estudiante";
        $sql = "SELECT '' AS Tipo, '' AS Máximo, '' AS Mínimo, '' AS Promedio";

        $dataProvider = new SqlDataProvider([
            'sql' => $sql,

        ]);
        return $this->render('egresadonotas',
            ['seleccionCurso' => "",
                'seleccionEgresado' => '',
                'dataProvider' => $dataProvider,
                'mymodel' => $model1]);
//cuando no hay variable de post
    }

    public function actionEstudiante_indice()
    {
        $model = new EstudianteIndiceForm;

        if ($model->load(Yii::$app->request->post())) {
            //$valorRespuesta = $model->indiceChk;
            $dataProvider = '';
            $valorCurso = $model->cursoid;
            if ($model->cursoid > 0) {
                if ($model->indiceChk != 1) {
                    $valorRespuesta[] = true;
                    $model->indiceText = "";
                } else
                    $valorRespuesta[] = false;

                if ($model->habilitarChk != 1) {
                    $valorRespuesta[] = true;
                    $valorRespuesta[] = true;
                    $valorRespuesta[] = true;
                    $valorRespuesta[] = true;
                    // $model->indiceText="";
                    $model->espanolText = "";
                    $model->matematicaText = "";
                    $model->historiaText = "";
                    $model->espanolChk = '';
                    $model->matematicaChk = '';
                    $model->historiaChk = '';

                } else {


                    if ($model->espanolChk != 1) {
                        $valorRespuesta[] = true;
                        $model->espanolText = "";
                    } else
                        $valorRespuesta[] = false;

                    if ($model->matematicaChk != 1) {
                        $valorRespuesta[] = true;
                        $model->matematicaText = "";
                    } else
                        $valorRespuesta[] = false;

                    if ($model->historiaChk != 1) {
                        $valorRespuesta[] = true;
                        $model->historiaText = "";
                    } else
                        $valorRespuesta[] = false;

                    $valorRespuesta[] = false;

                }


                $valorRespuesta[] = false;


                $sql = "SELECT nombre AS Nombre,indice_academico AS Índice,nota_matematica AS Matemática,nota_espannol AS Español,nota_historia AS Historia FROM estudiante WHERE id_curso=:cursoid";

                if ($model->indiceText != '')
                    $sql = $sql . " and indice_academico >= " . $model->indiceText;


                if ($model->matematicaText != '') {
                    /*if (!(strpos($sql, 'where')))
                         $sql = $sql . " where nota_matematica >= " . $model->matematicaText;
                     else*/
                    $sql = $sql . " and nota_matematica >=" . $model->matematicaText;

                }
                if ($model->espanolText != '') {

                    /* if (!(strpos($sql, 'where')))
                         $sql = $sql . " where nota_espannol >= " . $model->espanolText;
                     else*/
                    $sql = $sql . " and nota_espannol >=" . $model->espanolText;

                }
                if ($model->historiaText != '') {

                    // if (!(strpos($sql, 'where')))
                    // $sql = $sql . " where nota_historia >= " . $model->historiaText;
                    // else
                    $sql = $sql . " and nota_historia >=" . $model->historiaText;

                }


                $dataProvider = new SqlDataProvider([
                    'sql' => $sql,
                    'pagination' => false,
                    'params' => [':cursoid' => $valorCurso],

                ]);
            } else {
                $valorRespuesta[] = true;  // disabled de indice texto
                $valorRespuesta[] = true;   // disabled de espanol texto
                $valorRespuesta[] = true;     // disabled de matematica texto
                $valorRespuesta[] = true;     // disabled de historia texto
                $valorRespuesta[] = true;     // disabled de checkbox pruebas ingreso
                $valorRespuesta[] = true;     // disabled de checkbox indice y notas y submmit

                $model->indiceChk = '';
                $model->indiceText = "";
                $model->espanolText = "";
                $model->matematicaText = "";
                $model->historiaText = "";
                $model->espanolChk = '';
                $model->matematicaChk = '';
                $model->historiaChk = '';
                $model->habilitarChk = '';

            }


            return $this->render('estudiante_indice',
                [
                    'seleccionCurso' => "",
                    'pagination' => false,
                    'dataProvider' => $dataProvider,
                    'seleccionEgresado' => $valorRespuesta,
                    'mymodel' => $model]);
        }

        $valorRespuesta[] = true;  // disabled de indice texto
        $valorRespuesta[] = true;   // disabled de espanol texto
        $valorRespuesta[] = true;     // disabled de matematica texto
        $valorRespuesta[] = true;     // disabled de historia texto
        $valorRespuesta[] = true;     // disabled de checkbox pruebas ingreso
        $valorRespuesta[] = true;     // disabled de checkbox indice y notas y submmit


        //$sql="SELECT nombre AS Nombre,indice_academico AS Índice,nota_matematica AS Matemática,nota_espannol AS Español,nota_historia AS Historia FROM estudiante";
        $sql = "SELECT '' AS Nombre, '' AS Índice, '' AS Matemática, '' AS Español, '' AS Historia";

        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'pagination' => false,

        ]);
        return $this->render('estudiante_indice',
            [
                'seleccionCurso' => "",
                'dataProvider' => $dataProvider,
                'seleccionEgresado' => $valorRespuesta,
                'mymodel' => $model]);

    }

    public function actionEstudiantes_notas_indice()
    {
        $model = new EstudiantesNotasIndiceForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->cursoid > 0) {
                $valorRespuesta = $model->cursoid;
                $valorAux[] = false;
                $valorAux[] = false;

                $sql = "SELECT nombre AS Nombre";

                if ($model->indiceChk == 1)
                    $sql = $sql . " ,indice_academico AS Índice";
                if ($model->notasChk == 1)
                    $sql = $sql . " ,nota_matematica AS matemática,nota_espannol AS Español,nota_historia AS Historia";

                $sql = $sql . " FROM estudiante WHERE id_curso=:cursoid";

                $dataProvider = new SqlDataProvider([
                    'sql' => $sql,
                    'pagination' => false,
                    'params' => [':cursoid' => $valorRespuesta],
                ]);
                return $this->render('estudiantes_notas_indice', [
                    'seleccionEgresado' => $valorRespuesta,
                    'valor' => $valorAux,
                    'dataProvider' => $dataProvider,
                    'mymodel' => $model
                ]);

            } else {
                $model->indiceChk = '';
                $model->notasChk = '';
            }
        }
        // $sql="SELECT nombre AS Nombre,indice_academico AS Índice,nota_matematica AS Matemática,nota_espannol AS Español,nota_historia AS Historia FROM estudiante";
        $sql = "SELECT '' AS Nombre,'' AS Índice,'' AS Matemática,'' AS Español,'' AS Historia";

        //$model->indiceChk=0;
        //$model->notasChk=0;

        $valorAux[] = true;
        $valorAux[] = true;


        $dataProvider = new SqlDataProvider([

            'sql' => $sql,

            'pagination' => false,

        ]);

        return $this->render('estudiantes_notas_indice', [
            'dataProvider' => $dataProvider,
            'valor' => $valorAux,
            'seleccionEgresado' => "",
            'mymodel' => $model
        ]);


    }

    public function actionEstadisticas_curso()
    {
        $model1 = new EstadisticasCursoForm();


        if ($model1->load(Yii::$app->request->post())) {
            if ($model1->cursoid > 0) {
                $valorRespuesta = $model1->cursoid;
                /* if ($valorRespuesta == "") {
                     //Cuando no hay seleccion
                     $sql = "SELECT 'Índice Académico' as Tipo, Max(indice_academico) as Máximo, Min(indice_academico) as Mínimo, round(Avg(indice_academico),2) as Promedio FROM estudiante union SELECT 'Matemática' as Tipo, Max(nota_matematica) as Máximo, Min(nota_matematica) as Mínimo, round(Avg(nota_matematica),2) as Promedio FROM estudiante union SELECT 'Español' as Tipo, Max(nota_espannol) as Máximo, Min(nota_espannol) as Mínimo, round(Avg(nota_espannol),2) as Promedio FROM estudiante union SELECT 'Historia' as Tipo, Max(nota_historia) as Máximo, Min(nota_historia) as Mínimo, round(Avg(nota_historia),2) as Promedio FROM estudiante";

                     $dataProvider = new SqlDataProvider([
                         'sql' => $sql,

                     ]);
                 } */
                //condicion si hay seleccion
                $sql = "SELECT 'Índice Académico' as Tipo, Max(indice_academico) as Máximo, Min(indice_academico) as Mínimo, round(Avg(indice_academico),2) as Promedio FROM estudiante where id_curso=:cursoid union SELECT 'Matemática' as Tipo, Max(nota_matematica) as Máximo, Min(nota_matematica) as Mínimo, round(Avg(nota_matematica),2) as Promedio FROM estudiante where id_curso=:cursoid union SELECT 'Español' as Tipo, Max(nota_espannol) as Máximo, Min(nota_espannol) as Mínimo, round(Avg(nota_espannol),2) as Promedio FROM estudiante where id_curso=:cursoid union SELECT 'Historia' as Tipo, Max(nota_historia) as Máximo, Min(nota_historia) as Mínimo, round(Avg(nota_historia),2) as Promedio FROM estudiante where id_curso=:cursoid";


                $dataProvider = new SqlDataProvider([
                    'sql' => $sql,
                    'pagination' => false,
                    'params' => [':cursoid' => $valorRespuesta],

                ]);
                return $this->render('estadisticas_curso', [
                    'seleccionCurso' => $valorRespuesta,
                    'dataProvider' => $dataProvider,
                    'mymodel' => $model1
                ]);
            }


        }
        //$sql = "SELECT 'Índice Académico' as Tipo, Max(indice_academico) as Máximo, Min(indice_academico) as Mínimo, round(Avg(indice_academico),2) as Promedio FROM estudiante union SELECT 'Matemática' as Tipo, Max(nota_matematica) as Máximo, Min(nota_matematica) as Mínimo, round(Avg(nota_matematica),2) as Promedio FROM estudiante union SELECT 'Español' as Tipo, Max(nota_espannol) as Máximo, Min(nota_espannol) as Mínimo, round(Avg(nota_espannol),2) as Promedio FROM estudiante union SELECT 'Historia' as Tipo, Max(nota_historia) as Máximo, Min(nota_historia) as Mínimo, round(Avg(nota_historia),2) as Promedio FROM estudiante";
        $sql = "SELECT '' as Tipo, '' as Máximo, '' as Mínimo, '' as Promedio";
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'pagination' => false,

        ]);
        return $this->render('estadisticas_curso', [
            'seleccionCurso' => '',
            'dataProvider' => $dataProvider,
            'mymodel' => $model1
        ]);
        //cuando no hay variable de post
    }

    public function actionEstudiantes_no_computadora()
    {
        $model = new EstudiantesNoComputadoraForm();

        if ($model->load(Yii::$app->request->post())) {

            $valorRespuesta = $model->cursoid;
            //condicion si hay seleccion
            //$sql = "SELECT nombre AS Nombre FROM estudiante INNER JOIN resp_preguntas_mas ON resp_preguntas_mas.id = estudiante.id_dispo_copumtadora WHERE resp_preguntas_mas.respuesta = 'No tengo acceso a ninguna' and estudiante.id_curso=:cursoid ";
            $sql = "SELECT nombre AS Nombre FROM estudiante WHERE id_dispo_copumtadora=21 and id_curso=:cursoid ";

            $dataProvider = new SqlDataProvider([
                'sql' => $sql,
                'pagination' => false,
                'params' => [':cursoid' => $valorRespuesta],

            ]);

            return $this->render('estudiantes_no_computadora', [
                'seleccionEgresado' => $valorRespuesta,
                'dataProvider' => $dataProvider,
                'mymodel' => $model
            ]);
        }

        $sql = "SELECT '' AS Nombre ";

        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'pagination' => false,

        ]);

        return $this->render('estudiantes_no_computadora', [
            'seleccionEgresado' => '',
            'dataProvider' => $dataProvider,
            'mymodel' => $model
        ]);
        //cuando no hay variable de post
    }

    public function actionEstudiantes_formas_estudios()
    {
        $model = new EstudiantesFormasEstudiosForm();


        if ($model->load(Yii::$app->request->post())) {
            $valorRespuesta = $model->cursoid;
            //condicion si hay seleccionestudiante.id_curso=:cursoid
            $sql = " SELECT 'Estudio individual por libro' AS Tipo, round(CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid and id_estudio_libro=9) as numeric)*100/CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid) as numeric),2) AS Mucho ,round(CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid and id_estudio_libro=10) as numeric)*100/CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid) as numeric),2) AS Poco, round(CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid and id_estudio_libro=17) as numeric)*100/CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid) as numeric),2) AS Nunca, round(CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid and id_estudio_libro=16) as numeric)*100/CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid) as numeric),2) AS No, round(CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid and id_estudio_libro=4) as numeric)*100/CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid) as numeric),2) AS Ns ";
            $sql = $sql . " union SELECT 'Apoyandose en repasadores' AS Tipo, round(CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid and id_estudio_repasadores=9) as numeric)*100/CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid) as numeric),2) AS Mucho,round(CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid and id_estudio_repasadores=10) as numeric)*100/CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid) as numeric),2) AS Poco, round(CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid and id_estudio_repasadores=17) as numeric)*100/CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid) as numeric),2) AS Nunca,round(CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid and id_estudio_repasadores=16) as numeric)*100/CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid) as numeric),2) AS No, round(CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid and id_estudio_repasadores=4) as numeric)*100/CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid) as numeric),2) AS Ns ";
            $sql = $sql . " union SELECT 'Estudio grupal' AS Tipo, round(CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid and id_estudio_grupal=9) as numeric)*100/CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid) as numeric),2) AS Mucho,round(CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid and id_estudio_grupal=10) as numeric)*100/CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid) as numeric),2) AS Poco, round(CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid and id_estudio_grupal=17) as numeric)*100/CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid) as numeric),2) AS Nunca,round(CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid and id_estudio_grupal=16) as numeric)*100/CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid) as numeric),2) AS No, round(CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid and id_estudio_grupal=4) as numeric)*100/CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid) as numeric),2) AS Ns ";
            $sql = $sql . " union SELECT 'Realizando ejercicios' AS Tipo, round(CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid and id_estudio_ejercicios=9) as numeric)*100/CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid) as numeric),2) AS Mucho,round(CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid and id_estudio_ejercicios=10) as numeric)*100/CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid) as numeric),2) AS Poco, round(CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid and id_estudio_ejercicios=17) as numeric)*100/CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid) as numeric),2) AS Nunca,round(CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid and id_estudio_ejercicios=16) as numeric)*100/CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid) as numeric),2) AS No, round(CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid and id_estudio_ejercicios=4) as numeric)*100/CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid) as numeric),2) AS Ns ";


            $dataProvider = new SqlDataProvider([
                'sql' => $sql,
                'pagination' => false,
                'params' => [':cursoid' => $valorRespuesta],

            ]);


            return $this->render('estudiantes_formas_estudios', [
                'seleccionCurso' => $valorRespuesta,
                'dataProvider' => $dataProvider,
                'mymodel' => $model
            ]);
        }
        $sql = "SELECT '' AS Tipo,'' AS mucho,'' AS poco,'' AS nunca,'' AS no,'' AS ns ";

        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'pagination' => false,

        ]);
        return $this->render('estudiantes_formas_estudios', [
            'seleccionCurso' => '',
            'dataProvider' => $dataProvider,
            'mymodel' => $model
        ]);
        //cuando no hay variable de post
    }

    public function actionEstudiantes_horas_estudios()
    {
        $model = new EstudiantesHorasEstudiosForm();


        if ($model->load(Yii::$app->request->post())) {
            $valorRespuesta = $model->cursoid;

            $sql = "SELECT 'Entre 20 o más' AS Tipo,round(CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid and id_horas_estudio=27) as numeric)*100/CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid) as numeric),2) AS Ciento";
            $sql = $sql . " union SELECT (SELECT respuesta FROM resp_sobre_futuro WHERE id=24) AS Tipo,round(CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid and id_horas_estudio=24) as numeric)*100/CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid) as numeric),2) AS Ciento";
            $sql = $sql . " union SELECT (SELECT respuesta FROM resp_sobre_futuro WHERE id=15) AS Tipo,round(CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid and id_horas_estudio=15) as numeric)*100/CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid) as numeric),2) AS Ciento";
            $sql = $sql . " union SELECT 'Entre 5 y 10 ' AS Tipo,round(CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid and id_horas_estudio=21) as numeric)*100/CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid) as numeric),2) AS Ciento";
            $sql = $sql . " union SELECT 'Entre 2 y 5 ' AS Tipo,round(CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid and id_horas_estudio=7) as numeric)*100/CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid) as numeric),2) AS Ciento";
            $sql = $sql . " union SELECT (SELECT respuesta FROM resp_sobre_futuro WHERE id=8) AS Tipo,round(CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid and id_horas_estudio=8) as numeric)*100/CAST((SELECT COUNT(*) FROM estudiante WHERE estudiante.id_curso=:cursoid) as numeric),2) AS Ciento";


            $dataProvider = new SqlDataProvider([
                'sql' => $sql,
                'pagination' => false,
                'params' => [':cursoid' => $valorRespuesta],

            ]);


            return $this->render('estudiantes_horas_estudios', [
                'seleccionCurso' => $valorRespuesta,
                'dataProvider' => $dataProvider,
                'mymodel' => $model
            ]);
        }
        $sql = "SELECT '' AS Tipo,'' AS Ciento";

        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'pagination' => false,

        ]);
        return $this->render('estudiantes_horas_estudios', [
            'seleccionCurso' => '',
            'dataProvider' => $dataProvider,
            'mymodel' => $model
        ]);
        //cuando no hay variable de post
    }

    ///Junior
    public function actionVia_ingreso()
    {
        $model = new ViaIngresoForm();
        if ($model->load(Yii::$app->request->post())) {
            $valorCurso = $model->cursoid;
            return $this->render('via_ingreso', ['model' => $model]);
        }
        return $this->render('via_ingreso', ['model' => $model]);
    }

    public function actionResponsabilidades()
    {
        $model = new ResponsabilidadesForm();
        $respFuturo = RespSobreFuturo::find()->orWhere([
            'respuesta' => 'Mucho interés',
        ])->orWhere(['respuesta' => 'Algún interés'])->orWhere(['respuesta' => 'Poco interés'])->orWhere(['respuesta' => 'Ningún interés'])->all();
        $lista = BaseArrayHelper::map($respFuturo, 'id', 'respuesta');

        if ($model->load(Yii::$app->request->post())) {
            return $this->render('responsabilidades', ['model' => $model, 'lista' => $lista]);
        }
        return $this->render('responsabilidades', ['model' => $model, 'lista' => $lista]);

    }

    public function actionEstudiantes_deportes()
    {
        $model = new DeportesForm();
        if ($model->load(Yii::$app->request->post())) {
            return $this->render('estudiantes_deporte', ['model' => $model]);
        }
        return $this->render('estudiantes_deporte', ['model' => $model]);
    }

    public function actionEstudiantes_artes()
    {
        $model = new ArtesForm();
        if ($model->load(Yii::$app->request->post())) {
            return $this->render('estudiantes_artes', ['model' => $model]);
        }
        return $this->render('estudiantes_artes', ['model' => $model]);
    }

    public function actionEstudiantes_habitos()
    {
        $model = new EstudiantesHabitosForm();
        if ($model->load(Yii::$app->request->post())) {
            return $this->render('estudiantes_habitos', ['model' => $model]);
        }
        return $this->render('estudiantes_habitos', ['model' => $model]);
    }

    public function actionOpciones_carrera()
    {
        $model = new OpcionesDeCarreraForm();
        if ($model->load(Yii::$app->request->post())) {
            return $this->render('opciones_carrera', ['model' => $model]);
        }
        return $this->render('opciones_carrera', ['model' => $model]);

    }

    public function actionFactores_carrera(){
        $model = new FactoresCarreraForm();
        if($model->load(Yii::$app->request->post())){
            return $this->render('factores_carrera', ['model'=> $model]);
        }
        return $this->render('factores_carrera', ['model' => $model]);

    }
    
    public function actionRazones_ingreso(){
        $model = new RazonesIngresoForm();
        if ($model->load(Yii::$app->request->post())) {
            return $this->render('razones_ingreso', ['model' => $model]);
        }
        return $this->render('razones_ingreso', ['model' => $model]);
        
    }
    
    public function  actionTiempo_desicion_estudio(){
        $model = new TiempoEstudioCarreraForm();
        if ($model->load(Yii::$app->request->post())) {
            return $this->render('tiempo_estudio_carrera', ['model' => $model]);
        }
        return $this->render('tiempo_estudio_carrera', ['model' => $model]);
    }
}
