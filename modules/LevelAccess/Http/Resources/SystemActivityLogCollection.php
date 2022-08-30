<?php

namespace Modules\LevelAccess\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Modules\LevelAccess\Models\SystemActivityLog;


class SystemActivityLogCollection extends ResourceCollection
{

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $transaction_types = SystemActivityLog::getTransactionTypes();

        return $this->collection->transform(function($row, $key) use($transaction_types) {
            return $row->getRowResourceAccess($transaction_types);
        });

    }
    
}
