<?php

namespace App\Imports;

use App\Models\Tenant\Item;
use App\Models\Tenant\Warehouse;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Modules\Item\Models\Category;
use Modules\Item\Models\Brand;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Modules\Inventory\Models\InventoryTransaction;
use Modules\Inventory\Models\Inventory;
use Modules\Inventory\Models\ItemWarehouse;
use Exception;
use Modules\Item\Models\ItemLotsGroup;


class StockImport implements ToCollection
{
    use Importable;

    protected $data;

    public function collection(Collection $rows)
    {
            $total = count($rows);
            $warehouse_id_de = request('warehouse_id');
            $registered = 0;
            unset($rows[0]);
            foreach ($rows as $row) {
                $internal_id = ($row[0])?:null;
                $quantity_real = ($row[1]) ? : null;

                $item = Item::where('internal_id', $internal_id)
                                    ->first();

                $itemWarehouse=ItemWarehouse::where('item_id',$item->id)
                ->where('warehouse_id',$warehouse_id_de)
                ->update([
                    'stock' => $quantity_real,
                ]);

                $registered += 1;
            }
            $this->data = compact('total', 'registered', 'warehouse_id_de');

    }

    public function getData()
    {
        return $this->data;
    }
}
