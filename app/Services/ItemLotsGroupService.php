<?php

namespace App\Services;
use Modules\Item\Models\ItemLotsGroup;

class ItemLotsGroupService
{

    public function getLote($id)
    {
        $result = '';

        if(is_array($id)) {

            foreach ($id as $item) {
                $result .= "/" . $item->code . " V:" . $item->date_of_due;
            }
        }
        else {
            $record = ItemLotsGroup::where('id', $id)->first();

            if($record)
            {
                $result = $record->code . " V:" . $record->date_of_due;
            }
        }
        

        return $result;
    }


}
