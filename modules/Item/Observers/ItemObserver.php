<?php

namespace Modules\Item\Observers;

use App\Models\Tenant\Item;

class ItemObserver
{
    public function saving(Item $item)
    {
        $text = [];
        $text[] = $item->description;
        if(!is_null($item->internal_id) && $item->internal_id !== '') {
            $text[] = $item->internal_id;
        }
        if($item->category) {
            $text[] = $item->category->name;
        }
        if($item->brand) {
            $text[] = $item->brand->name;
        }
        if($item->barcode) {
            $text[] = $item->barcode;
        }
        if($item->second_name) {
            $text[] = $item->second_name;
        }
        if($item->line) {
            $text[] = $item->line;
        }

        $item->text_filter = join('|', $text);
    }
}
