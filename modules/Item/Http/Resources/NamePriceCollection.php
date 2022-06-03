<?php

    namespace Modules\Item\Http\Resources;

    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\ResourceCollection;
    use Modules\Item\Models\ListPrice;

    class NamePriceCollection extends ResourceCollection
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
            return $this->collection->transform(function (ListPrice $row, $key) {
                $number=$key+1;
                return [
                    'id' => $row->id,
                    'name' => "Precio {$number}",
                    'price' => $row->price,
                ];
            });

        }

    }
