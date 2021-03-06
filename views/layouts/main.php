<?php

/* @var $this \yii\web\View */
/* @var $content string */
    
    use app\assets\AppAsset;
    use app\widgets\Alert;
    use yii\bootstrap4\Breadcrumbs;
    use yii\bootstrap4\Html;
    use yii\bootstrap4\Nav;
    use yii\bootstrap4\NavBar;
    
    AppAsset::register($this);
$this->beginPage()

?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header class="p-4">
    <?php
    NavBar::begin([
        'brandLabel' => 'Diagnóstico a estudiantes de nuevo ingreso',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => [
            ['label' => 'Inicio', 'url' => ['/site/index']],
            
            [
                    'label'=>'Reportes',
                    'items'=>[
                        /// Ferrer
                        ['label' => 'Estudiantes por lugar de egreso', 'url'=> ['/report/egresado']],
                        ['label' => 'Datos de ingreso por lugar de egreso', 'url'=> ['/report/egresadonotas']],
                        ['label' => 'Estudiantes por índice y notas de ingreso ', 'url'=> ['/report/estudiante_indice']],
                        ['label' => 'Estudiantes que no cuentan con computadora', 'url'=> ['/report/estudiantes_no_computadora']],
                        ['label' => 'Listado de estudiantes por índice y notas de ingreso ', 'url'=> ['/report/estudiantes_notas_indice']],
                        ['label' => 'Datos de ingreso por curso seleccionado ', 'url'=> ['/report/estadisticas_curso']],
                        ['label' => 'Por ciento de formas de estudios por curso académico', 'url'=> ['/report/estudiantes_formas_estudios']],
                        ['label' => 'Por ciento de horas de estudios por curso académico', 'url'=> ['/report/estudiantes_horas_estudios']],


                        /// Junior
                        ['label' => 'Estudiantes por lugar de ingreso ', 'url'=> ['/report/via_ingreso']],
                        ['label' => 'Estudiantes por nivel de interés de pertenecer a organizaciones', 'url'=> ['/report/responsabilidades']],
                        ['label' => 'Estudiantes por deporte que práctican', 'url'=> ['/report/estudiantes_deportes']],
                        ['label' => 'Estudiantes por manifestaciones artísticas que práctican', 'url'=> ['/report/estudiantes_artes']],
                        ['label' => 'Deportes y manifestaciones artísticas que practican', 'url'=> ['/report/estudiantes_habitos']],

                    ],
            ],

            Yii::$app->user->isGuest ? (
                ['label' => 'Usuario', 'url' => ['/site/login']]
            ) : (
                '<li>'
                 .Html::beginForm(['/site/logout'], 'post', ['class' => 'form-inline'])
                 .Html::submitButton(
                     'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
        
    ]);
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer class="footer mt-auto py-3 text-muted">
    <div class="container">
        <p class="float-left">&copy; Facultad de Informática <?= date('Y') ?></p>
        <p class="float-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
