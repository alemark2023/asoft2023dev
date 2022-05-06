<?php

    namespace Modules\Item\Http\Resources;

    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\ResourceCollection;
    use Modules\Item\Models\ItemPriceType;

    class ItemPriceTypeCollection extends ResourceCollection
    {
        /**
         * Transform the resource collection into an array.
         *
         * @param Request $request
         *
         * @return array
         */
        public function toArray($request)
        {
            return $this->collection->transform(function (ItemPriceType $row, $key) {

                return [
                    'id' => $row->id,
                    'name' => $row->name,
                ];
            });

        }

    }
