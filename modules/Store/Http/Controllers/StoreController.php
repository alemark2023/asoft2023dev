<?php

namespace Modules\Store\Http\Controllers;

use App\Models\Tenant\BankAccount;
use App\Models\Tenant\Cash;
use App\Models\Tenant\Catalogs\ChargeDiscountType;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Company;
use App\Models\Tenant\Configuration;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Item;
use App\Models\Tenant\ItemMovement;
use App\Models\Tenant\PaymentMethodType;
use App\Models\Tenant\Person;
use App\Models\Tenant\User;
use App\Traits\OfflineTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Inventory\Models\Warehouse;

class StoreController extends Controller
{
    use OfflineTrait;

    public function getQuotationTables()
    {
        $company = Company::query()->first();
        $establishments = Establishment::query()
            ->where('id', auth()->user()->establishment_id)
            ->get();
        $configuration = Configuration::query()
            ->select('destination_sale')
            ->first();
        $document_type_03_filter = config('tenant.document_type_03_filter');

        $payment_method_types = PaymentMethodType::query()
            ->orderBy('id', 'desc')
            ->get();

        $payment_destinations = $this->getPaymentDestinations();

        $sellers = User::query()
            ->select('id', 'name', 'type')
            ->getSellers(false)->get();

        return [
            'company' => $company,
            'establishments' => $establishments,
            'configuration' => $configuration,
            'document_type_03_filter' => $document_type_03_filter,
            'currency_types' => func_get_currency_types(),

            'payment_method_types' => $payment_method_types,
            'payment_destinations' => $payment_destinations,
            'sellers' => $sellers,
        ];
    }

    public function getItemTables()
    {
        $config = Configuration::query()
            ->select('allow_edit_unit_price_to_seller', 'amount_plastic_bag_taxes', 'edit_name_product',
                'seller_can_create_product', 'show_last_price_sale')
            ->first();
        return [
            'config' => $config,
            'operation_types' => func_get_operation_types(),
            'affectation_igv_types' => func_get_affectation_igv_types(),
            'system_isc_types' => func_get_system_isc_types(),
            'discount_types' => func_get_discount_types(),
            'charge_types' => func_get_charge_types(),
            'attribute_types' => func_get_attribute_types(),
            'is_client' => $this->getIsClient()
        ];
    }

    public function searchItem($id)
    {
        $records = Item::query()
            ->with('category', 'brand', 'item_unit_types')
            ->where('id', $id)
            ->get();

        return $this->getTransformItems($records);
    }

    public function searchItems(Request $request)
    {
        $search = $request->input('search', '');

//        $establishment_id = auth()->user()->establishment_id;
//        $warehouse = Warehouse::where('establishment_id', $establishment_id)->first();

        $query = Item::query()
            ->with('category', 'brand', 'item_unit_types')
            ->where('text_filter', 'like', "%{$search}%")
            ->whereWarehouse()
            ->whereIsActive()
            ->orderBy('description')
            ->take(48)
            ->get();

        return $this->getTransformItems($query);
    }

    private function getTransformItems($query)
    {
        $establishment_id = auth()->user()->establishment_id;
        $warehouse = Warehouse::query()->where('establishment_id', $establishment_id)->first();

        return $query->transform(function ($row) use ($warehouse) {
            $has_presentations = (count($row->item_unit_types) > 0);
            $w = $row->warehouses->where('warehouse_id', $warehouse->id)->first();

            $url = 'storage' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'items' . DIRECTORY_SEPARATOR . $row->image;
            $image_url = asset($url);
            if (!file_exists(public_path($url))) {
                $image_url = '';
            }

            $name = func_str_to_upper_utf8($row->description);

            return [
                'id' => $row->id,
                'name' => $name,
                'name_product_pdf' => "<p>{$name}</p>",
                'currency_type_id' => $row->currency_type_id,
                'currency_type_symbol' => $row->currency_type->symbol,
                'internal_id' => $row->internal_id,
                'unit_price' => floatval($row->sale_unit_price),
                'unit_type_id' => $row->unit_type_id,
                'affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                'has_igv' => $row->has_igv,
                'image_url' => $image_url,
                'stock' => floatval($w->stock),
                'category_name' => optional($row->category)->name,
                'brand_name' => optional($row->brand)->name,
                'model' => func_str_to_upper_utf8($row->model),
                'has_icbper' => $row->has_plastic_bag_taxes,
//                'factor_icbper' => $row->amount_plastic_bag_taxes,
                'has_isc' => $row->has_isc,
                'system_isc_type_id' => $row->system_isc_type_id,
                'percentage_isc' => floatval($row->percentage_isc),
                'has_presentations' => $has_presentations,
                'presentations' => $row->item_unit_types,
                'unit_price_label' => $row->currency_type->symbol . ' ' . floatval($row->sale_unit_price),
                'presentation_name' => 'UNIDAD',
                'is_set' => $row->is_set,
                'quantity_factor' => 1,
                'warehouse_id' => $warehouse->id,
                'warehouse_name' => $warehouse->description,
                'calculate_quantity' => $row->calculate_quantity,
                'series_enabled' => $row->series_enabled
            ];
        });
    }

    public function searchCustomer($id)
    {
        $records = Person::query()
            ->with('addresses')
            ->select('id', 'name', 'number', 'identity_document_type_id', 'address', 'district_id')
            ->where('id', $id)
            ->get();

        return $this->getTransformCustomers($records);
    }

    public function searchCustomers(Request $request)
    {
        $search = $request->input('search', '');

        $records = Person::query()
            ->with('addresses')
            ->select('id', 'name', 'number', 'identity_document_type_id', 'address', 'district_id')
            ->where('type', 'customers')
            ->where('text_filter', 'like', "%$search%")
            ->take(20)
            ->get();

        return $this->getTransformCustomers($records);
    }

    private function getTransformCustomers($query)
    {
        return $query->transform(function ($row) {
            $addresses = [];
            if ($row->address !== '' && !is_null($row->district_id)) {
                $addresses[] = [
                    'id' => 0,
                    'address' => func_get_full_address($row),
                    'is_main' => true
                ];
            }

            foreach ($row->addresses as $ad) {
                if ($ad->address !== '' && !is_null($ad->district_id)) {
                    $addresses[] = [
                        'id' => $ad->id,
                        'address' => func_get_full_address($ad),
                        'is_main' => false
                    ];
                }
            }

            return [
                'id' => $row->id,
                'description' => $row->number . ' - ' . $row->name,
                'identity_document_type_id' => $row->identity_document_type_id,
                'addresses' => $addresses,
            ];
        });
    }

    public function getPaymentDestinations()
    {
        $bank_accounts = BankAccount::query()->get()->transform(function ($row) {
            return [
                'id' => $row->id,
                'cash_id' => null,
                'description' => "{$row->bank->description} - {$row->currency_type_id} - {$row->description}",
            ];
        });

        $cash = Cash::query()
            ->where('user_id', auth()->id())
            ->where('state', true)
            ->first();
        if ($cash) {
            $bank_accounts->push([
                'id' => 'cash',
                'cash_id' => $cash->id,
                'description' => ($cash->reference_number) ? "CAJA GENERAL - {$cash->reference_number}" : "CAJA GENERAL",
            ]);
        }

        return $bank_accounts;
    }
}
