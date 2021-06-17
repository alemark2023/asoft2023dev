<?php


    namespace App\Imports;

    use App\Models\Tenant\Item;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Support\Collection;
    use Maatwebsite\Excel\Concerns\Importable;
    use Maatwebsite\Excel\Concerns\ToCollection;


    /**
     * Class CatalogImport
     *
     * @package App\Imports
     *
     */
    class CatalogImport implements ToCollection {
        use Importable;

        protected $data;

        public function collection(Collection $rows) {
            $total = count($rows);
            $registered = 0;
            $i = 0;
            unset($rows[0]);
            $data = [];
            foreach ($rows as $row) {
                /*
 0 => 'Cod_Prod'
  1 => 'Nom_Prod'
  2 => 'Concent'
  3 => 'Nom_Form_Farm'
  4 => 'Nom_Form_Farm_Simplif'
  5 => 'Presentac'
  6 => 'Fracciones'
  7 => 'Fec_Vcto_Reg_Sanitario'
  8 => 'Num_RegSan'
  9 => 'Nom_Titular'
  10 => 'Situacion'
*/
                $Cod_Prod = trim($row[0]);
                $Nom_Prod = $row[1];
                $Concent = $row[2];
                $Nom_Form_Farm = $row[3];
                $Nom_Form_Farm_Simplif = $row[4];
                $Presentac = $row[5];
                $Fracciones = $row[6];
                $Fec_Vcto_Reg_Sanitario = $row[7];
                $Num_RegSan = $row[8];
                $Nom_Titular = $row[9];
                $Situacion = $row[10];
                if (
                    !empty($Cod_Prod) &&
                    !empty($Nom_Prod) &&
                    !empty($Concent) &&
                    !empty($Nom_Form_Farm) &&
                    !empty($Nom_Form_Farm_Simplif) &&
                    !empty($Presentac) &&
                    !empty($Fracciones) &&
                    !empty($Fec_Vcto_Reg_Sanitario) &&
                    !empty($Num_RegSan) &&
                    !empty($Nom_Titular) &&
                    !empty($Situacion) &&
                    $Cod_Prod !== 'Cod_Prod'
                ) {
                    $item = Item::orWhere(function (Builder $q) use ($Cod_Prod) {
                        $q->Where('internal_id', $Cod_Prod);
                        $q->WhereNotNull('internal_id');
                    })->orWhere(function (Builder $q) use ($Cod_Prod) {
                        $q->Where('cod_digemid', $Cod_Prod);
                        $q->WhereNotNull('cod_digemid');
                    })->first();
                    if (empty($item)) {
                        $item = new Item();
                    }
                    \Log::debug('Escribiendo elemento '.($registered +1));
                    $item->fillFormDigemid($row);
                    if(empty($item->id)){
                        $item->save();
                    }else{
                        $item->push();
                    }
                    \Log::debug($item->id);
                    \Log::debug($item->toJson());

                    $val =  Item::find($item->id);
                    $c = $val->getConnection();
                    dd([
                           $c,$item,$val
                       ]);
                    ++$registered;

                }
            }
            $this->data = compact('total', 'registered');

        }

        public function getData() {
            return $this->data;
        }
    }
