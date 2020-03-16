<?php

namespace Modules\Document\Traits; 

use App\Models\Tenant\Item;

trait SearchTrait
{ 

    public function getItemsServices($request){

        return Item::where('description','like', "%{$request->input}%")
                    ->orWhere('internal_id','like', "%{$request->input}%")
                    ->orWhereHas('category', function($query) use($request) {
                        $query->where('name', 'like', '%' . $request->input . '%');
                    })
                    ->orWhereHas('brand', function($query) use($request) {
                        $query->where('name', 'like', '%' . $request->input . '%');
                    })
                    ->where('unit_type_id','ZZ')
                    ->whereNotIsSet()
                    ->whereIsActive()
                    ->orderBy('description')
                    ->get();

    }
    
    public function getItemsNotServices($request){

        return Item::where('description','like', "%{$request->input}%")
                    ->orWhere('internal_id','like', "%{$request->input}%")
                    ->orWhereHas('category', function($query) use($request) {
                        $query->where('name', 'like', '%' . $request->input . '%');
                    })
                    ->orWhereHas('brand', function($query) use($request) {
                        $query->where('name', 'like', '%' . $request->input . '%');
                    })
                    ->whereWarehouse()
                    ->whereNotIsSet()
                    ->whereIsActive()
                    ->orderBy('description')
                    ->get();

    }

    
    public function getItemsServicesById($id){

        return Item::where('id', $id)
                    ->where('unit_type_id','ZZ')
                    ->whereNotIsSet()
                    ->whereIsActive()
                    ->get();

    }
    
    public function getItemsNotServicesById($id){

        return Item::where('id', $id)
                    ->whereWarehouse()
                    ->whereNotIsSet()
                    ->whereIsActive()
                    ->get();

    }

    public function getFullDescription($row, $warehouse){

        $desc = ($row->internal_id)?$row->internal_id.' - '.$row->description : $row->description;
        $category = ($row->category) ? "{$row->category->name}" : "";
        $brand = ($row->brand) ? "{$row->brand->name}" : "";



        if($row->unit_type_id != 'ZZ')
        {
            $warehouse_stock = 0;
            if($row->warehouses && $warehouse)
            {
                $wr = $row->warehouses->where('warehouse_id', $warehouse->id)->first();
                if($wr)
                {
                    $warehouse_stock = number_format($wr->stock, 2);
                }
            }
              
            $stock = ($row->warehouses && $warehouse) ? "{$warehouse_stock}" : "";
        }
        else{
            $stock = '';
        }

        $desc = "{$desc} - {$brand}";

        return [
            'full_description' => $desc,
            'brand' => $brand,
            'category' => $category,
            'stock' => $stock,
        ];
    }

}
