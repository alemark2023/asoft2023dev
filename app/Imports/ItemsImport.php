<?php

namespace App\Imports;

use App\Models\Tenant\Item;
use App\Models\Tenant\Warehouse;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Modules\Item\Models\Category;
use Modules\Item\Models\Brand;
use PhpOffice\PhpSpreadsheet\Shared\Date;


class ItemsImport implements ToCollection
{
    use Importable;

    protected $data;

    public function collection(Collection $rows)
    {
            $total = count($rows);
            $registered = 0;
            unset($rows[0]);
            foreach ($rows as $row)
            {
                $description = $row[0];
                $item_type_id = '01';
                $internal_id = ($row[1])?:null;
                $item_code = ($row[2])?:null;
                $unit_type_id = $row[3];
                $currency_type_id = $row[4];
                $sale_unit_price = $row[5];
                $sale_affectation_igv_type_id = $row[6];
                // $has_igv = (strtoupper($row[7]) === 'SI')?true:false;

                $affectation_igv_types_exonerated_unaffected = ['20','21','30','31','32','33','34','35','36','37'];

                if(in_array($sale_affectation_igv_type_id, $affectation_igv_types_exonerated_unaffected)) {

                    $has_igv = true;

                }else{

                    $has_igv = (strtoupper($row[7]) === 'SI')?true:false;

                }

                $purchase_unit_price = ($row[8])?:0;
                $purchase_affectation_igv_type_id = ($row[9])?:null;
                $stock = $row[10];
                $stock_min = $row[11];
                $category_name = $row[12];
                $brand_name = $row[13];

                $name = $row[14];
                $second_name = $row[15];

                $lot_code = $row[16];
                $date_of_due = $row[17];


                if($internal_id) {
                    $item = Item::where('internal_id', $internal_id)
                                    ->first();
                } else {
                    $item = null;
                }

                // $establishment_id = auth()->user()->establishment->id;
                // $warehouse = Warehouse::where('establishment_id', $establishment_id)->first();

                if(!$item) {

                    $category = $category_name ? Category::updateOrCreate(['name' => $category_name]):null;
                    $brand = $brand_name ? Brand::updateOrCreate(['name' => $brand_name]):null;
                    // dd($row, $lot_code ,$date_of_due, $category, $brand);

                    if($lot_code && $date_of_due){

                        $_date_of_due = Date::excelToDateTimeObject($date_of_due)->format('Y-m-d');
                        
                        // dd($lot_code, $date_of_due, $x);

                        $new_item = Item::create([
                            'description' => $description,
                            'item_type_id' => $item_type_id,
                            'internal_id' => $internal_id,
                            'item_code' => $item_code,
                            'unit_type_id' => $unit_type_id,
                            'currency_type_id' => $currency_type_id,
                            'sale_unit_price' => $sale_unit_price,
                            'sale_affectation_igv_type_id' => $sale_affectation_igv_type_id,
                            'has_igv' => $has_igv,
                            'purchase_unit_price' => $purchase_unit_price,
                            'purchase_affectation_igv_type_id' => $purchase_affectation_igv_type_id,
                            'stock' => $stock,
                            'stock_min' => $stock_min,
                            'category_id' => optional($category)->id,
                            'brand_id' => optional($brand)->id,
                            'name' => $name,
                            'second_name' => $second_name,
    
                            'lots_enabled' => true,
                            'lot_code' => $lot_code,
                            'date_of_due' => $_date_of_due,
                            // 'warehouse_id' => $warehouse->id
                        ]);
                        
                        $new_item->lots_group()->create([
                            'code'  => $lot_code,
                            'quantity'  => $stock,
                            'date_of_due'  => $_date_of_due,
                        ]);

                    }else{

                        Item::create([
                            'description' => $description,
                            'item_type_id' => $item_type_id,
                            'internal_id' => $internal_id,
                            'item_code' => $item_code,
                            'unit_type_id' => $unit_type_id,
                            'currency_type_id' => $currency_type_id,
                            'sale_unit_price' => $sale_unit_price,
                            'sale_affectation_igv_type_id' => $sale_affectation_igv_type_id,
                            'has_igv' => $has_igv,
                            'purchase_unit_price' => $purchase_unit_price,
                            'purchase_affectation_igv_type_id' => $purchase_affectation_igv_type_id,
                            'stock' => $stock,
                            'stock_min' => $stock_min,
                            'category_id' => optional($category)->id,
                            'brand_id' => optional($brand)->id,
                            'name' => $name,
                            'second_name' => $second_name,
                            // 'warehouse_id' => $warehouse->id
                        ]);

                    }


                    $registered += 1;

                }else{

                    $item->update([
                        'description' => $description,
                        'item_type_id' => $item_type_id,
                        'internal_id' => $internal_id,
                        'item_code' => $item_code,
                        'unit_type_id' => $unit_type_id,
                        'currency_type_id' => $currency_type_id,
                        'sale_unit_price' => $sale_unit_price,
                        'sale_affectation_igv_type_id' => $sale_affectation_igv_type_id,
                        'has_igv' => $has_igv,
                        'purchase_unit_price' => $purchase_unit_price,
                        'purchase_affectation_igv_type_id' => $purchase_affectation_igv_type_id,
                        'stock_min' => $stock_min,
                        'name' => $name,
                        'second_name' => $second_name,
                    ]);

                    $registered += 1;

                }
            }
            $this->data = compact('total', 'registered');

    }

    public function getData()
    {
        return $this->data;
    }
}
