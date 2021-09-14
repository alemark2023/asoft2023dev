<?php

    namespace App\Http\Controllers;

    use App\Models\Tenant\Configuration;
    use App\Models\Tenant\Item;
    use Illuminate\Database\Query\Builder;
    use Illuminate\Http\Request;
    use Illuminate\Support\Collection;
    use Modules\Inventory\Models\Warehouse as ModuleWarehouse;

    /**
     * Tener en cuenta como base modules/Document/Traits/SearchTrait.php
     * Class SearchItemController
     *
     * @package App\Http\Controllers
     * @mixin Controller
     */
    class SearchItemController extends Controller
    {


        /**
         * @param Request|null $request
         *
         * @return Item[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Builder[]|Collection|mixed
         */
        public static function getServiceItem(Request $request = null)
        {
            self::validateRequest($request);
            /** @var Item $item */
            return self::getAllItemBase($request, true)
                ->orderBy('description')
                ->get();
        }

        /**
         * @param Request|null $request
         */
        protected static function validateRequest(&$request)
        {
            if ($request == null) $request = new Request();

        }

        /**
         * @param Request|null $request
         *
         * @return \Illuminate\Database\Eloquent\Builder
         */
        public static function getAllItemBase(Request $request = null, $service = false,$id = 0)
        {
            self::validateRequest($request);
            $search_item_by_series = Configuration::first()->isSearchItemBySeries();

            $items_id = ($request->has('items_id')) ? $request->items_id : null;
            $id = (int)$id;
            $search_by_barcode = $request->has('search_by_barcode') && (bool)$request->search_by_barcode;
            $input = ($request->has('input')) ? $request->input : null;

            $item = Item::whereIsActive()
                ->whereTypeUser();

            if ($service == false) {
                $item->WhereNotService()
                    ->with('warehousePrices');
            } else {
                $item
                    ->WhereService()
                    ->with(['item_lots'])
                    ->whereNotIsSet();

            }

            if ($search_item_by_series) {
                self::getItemsBySerie($item, $request);
            }

            if ($items_id != null) {
                $item->whereIn('id', $items_id);
            } elseif ($id != 0) {
                $item->where('id', $id);
            } else {

                if ($search_by_barcode === true) {
                    $item
                        ->where('barcode', $input)
                        ->limit(1);
                } elseif ($input != null) {
                    $item
                        ->where('description', 'like', "%{$input}%")
                        ->orWhere('internal_id', 'like', "%{$input}%")
                        ->orWhereHas('category', function ($query) use ($input) {
                            $query->where('name', 'like', '%' . $input . '%');
                        })
                        ->orWhereHas('brand', function ($query) use ($input) {
                            $query->where('name', 'like', '%' . $input . '%');
                        })
                        ->OrWhereJsonContains('attributes', ['value' => $input]);

                } else {
                    $item->take(20);
                }


            }

            $item->orderBy('description');
            return $item;
        }

        /**
         * @param              $item
         * @param Request|null $request
         */
        protected static function getItemsBySerie(&$item, Request $request = null)
        {

            self::validateRequest($request);
            $warehouse = ModuleWarehouse::select('id')->where('establishment_id', auth()->user()->establishment_id)->first();
            $input = ($request->has('input')) ? (bool)$request->input : null;
            $item->wherehas('item_lots', function ($query) use ($warehouse, $input) {
                $query->where('has_sale', false);
                $query->where('warehouse_id', $warehouse->id);
                $query->where('series', $input);
                return $query;
            })->take(1);

        }

        /**
         * @param Request|null $request
         *
         * @return Item[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Builder[]|Collection|mixed
         */
        public static function getAllItem(Request $request = null)
        {


            self::validateRequest($request);
            return self::getNotServiceItem($request);
        }

        /**
         * @param Request|null $request
         * @param false        $search_item_by_series
         *
         * @return Item[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Builder[]|Collection|mixed
         */
        public static function getNotServiceItem(Request $request = null, $id = 0)
        {

            self::validateRequest($request);
            $search_by_barcode = $request->has('search_by_barcode') && (bool)$request->search_by_barcode;
            $input = ($request->has('input')) ? (bool)$request->input : null;

            $item = self::getAllItemBase($request,false,$id);


            if ($search_by_barcode === false && $input != null) {
                $item->whereWarehouse();
            }


            return $item->orderBy('description')->get();
        }


        /**
         * @param Request|null $request
         *
         * @return \Illuminate\Database\Eloquent\Collection|Collection
         */
        public static function getNotServiceItemToModal(Request $request = null)
        {
            $establishment_id = auth()->user()->establishment_id;
            $warehouse = ModuleWarehouse::where('establishment_id', $establishment_id)->first();
            self::validateRequest($request);
            return self::getNotServiceItem($request)->transform(function ($row) use ($warehouse) {
                /** @var Item $row */
                return $row->getDataToItemModal($warehouse);
            });
        }
    }
