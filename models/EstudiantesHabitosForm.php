<?php
    
    
    namespace app\models;
    
    
    use app\models\db\Arte;
    use app\models\db\Deporte;
    use app\models\db\Estudiante;
    use yii\base\Model;

    class EstudiantesHabitosForm extends Model{
        public $estudiante;
        public $idCurso;
        
        public function rules(){
            return [
                [
                    ['estudiante', 'idCurso'], 'required'
                ], [
                    ['idCurso'], 'integer'
                ], [
                    ['estudiante'], 'string'
                ],
            
            ];
        }
        
        public function attributeLabels(){
            return [
                'estudiante' => 'Nombre del estudiante', 'idCurso' => 'Curso'
            ];
        }
        
        public function getEstudiantes(){
            $lista = [];
            if (isset($this->estudiante)){
                $lista = Estudiante::find()->where(['ilike', 'nombre', $this->estudiante])
                                   ->andWhere(['id_curso' => $this->idCurso])->all();
            }
            return $lista;
        }
        
        public function getDeportes($carne){
            $lista = "";
            if (isset($this->estudiante)){
                $listaDeporte = Deporte::find()
                    ->innerJoin('estudiante_deporte', 'estudiante_deporte.id_deporte = deporte.id')
                    ->where(['estudiante_deporte.id_estudiante' => $carne])
                    ->all();
                foreach ($listaDeporte as $item){
                    $lista = $lista . $item->deporte . ',';
                }
            }
            return $lista;
        }
        
        public function getArtes($carne){
            $lista = "";
            $listaArte = Arte::find()
                ->innerJoin('estudiante_arte', 'estudiante_arte.id_arte = arte.id')
                ->where(['estudiante_arte.id_estudiante' => $carne])
                ->all();
            foreach ($listaArte as $item){
                $lista = $lista . $item->arte . ',';
            }
            return $lista;
            
        }
        
    }