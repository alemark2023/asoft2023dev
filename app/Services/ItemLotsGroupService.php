<?php

namespace App\Services;
use Modules\Item\Models\ItemLotsGroup;

class ItemLotsGroupService
{

    public function getLote($id)
    {
        $result = '';
        $record = ItemLotsGroup::where('id', $id)->first();

        if($record)
        {
            $result = $record->code . " V:" . $record->date_of_due;
        }

        return $result;
    }


}
