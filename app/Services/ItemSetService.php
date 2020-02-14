<?php

namespace App\Services;
use App\Models\Tenant\ItemSet;

class ItemSetService
{

    public function getItemsSet($item)
    {
        $records = ItemSet::with('individual_item')->where('item_id', $item)->get();
        $result = array();

        foreach ($records as $row) {
            array_push($result, $row->individual_item->description);
        }

        return $result;
    }


}
