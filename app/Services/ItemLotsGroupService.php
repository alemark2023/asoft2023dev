<?php

namespace App\Services;
use Modules\Item\Models\ItemLotsGroup;

class ItemLotsGroupService
{

    public function getLote($item)
    {
        $result = '';
        $record = ItemLotsGroup::where('item_id', $item)->first();

        if($record)
        {
            $result = $record->code;
        }

        return $result;
    }


}
