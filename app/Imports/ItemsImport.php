<?php

namespace App\Imports;

use App\Models\Tenant\Item;
use App\Models\Tenant\Warehouse;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Modules\Item\Models\Category;
use Modules\Item\Models\Brand;


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


                if($internal_id) {
                    $item = Item::where('internal_id', $internal_id)
                                    ->first();
                } else {
                    $item = null;
                }

                // $establishment_id = auth()->user()->establishment->id;
                // $warehouse = Warehouse::where('establishment_id', $establishment_id)->first();

                if(!$item) {

                    $category = Category::updateOrCreate(['name' => $category_name]);
                    $brand = Brand::updateOrCreate(['name' => $brand_name]);


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
                        'category_id' => $category->id,
                        'brand_id' => $brand->id,
                        // 'warehouse_id' => $warehouse->id
                    ]);

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
