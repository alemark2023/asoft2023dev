<?php

    namespace App\CoreFacturalo\Helpers\Template;


    use App\Models\Tenant\Dispatch;
    use App\Models\Tenant\Document;
    use App\Models\Tenant\DocumentFee;
    use App\Models\Tenant\DocumentPayment;
    use App\Models\Tenant\PaymentMethodType;

    class TemplateHelper
    {


        /**
         * Devuelve la condicion de pago para un Document.
         * Las condiciones son Credito o Contado.
         *
         * @param Document $document
         *
         * @return string|null
         */
        public static function getDocumentPaymentCondition(Document $document)
        {
            // Condicion de pago  Crédito / Contado
            /** @var   PaymentMethodType $paymentCondition */
            $paymentCondition = ($document->payment_condition_id === '01') ?
                PaymentMethodType::where('id', '10')->first() :
                PaymentMethodType::where('id', '09')->first();

            return $paymentCondition->description;
        }


        /**
         * Devuelve un array con los detalles de pago.
         *
         * @param Document $document
         *
         * @return array
         */
        public static function getDetailedPayment(Document $document, $dateFormat = 'Y-m-d')
        {
            $data = [];
            $payments = $document->payments;
            if ($document->payment_condition_id === '01') {
                $data['PAGOS'] = [];
                /** @var DocumentPayment $row */
                foreach ($payments as $row) {
                    $temp = [
                        'description' => $row->payment_method_type->description,
                        'reference' => $row->reference ? $row->reference . ' - ' : '',
                        'symbol' => $document->currency_type->symbol,
                        'amount' => $row->payment + $row->change,
                    ];

                    $data['PAGOS'][] = $temp;
                }
            } else {
                $data['CUOTA'] = [];
                /**
                 * @var int          $key
                 * @var  DocumentFee $quote
                 */
                foreach ($document->fee as $key => $quote) {
                    $temp = [
                        'description' => (empty($quote->getStringPaymentMethodType()) ? 'Cuota #' . ($key + 1) : $quote->getStringPaymentMethodType()),
                        'reference' => $quote->date->format($dateFormat),
                        'amount' => $quote->amount,
                        'symbol' => $quote->symbol,
                    ];
                    $data['CUOTA'][] = $temp;

                }

            }
            return $data;
        }

        /**
         * Devuelve las guias de un documento, Primero las guias que esten escritas y luego las guias relacionadas
         *
         * @param Document $document
         *
         * @return array
         */
        public static function getGuides(Document $document)
        {
            $data = [];
            foreach ($document->guides as $guide) {
                $type = '';
                if (isset($guide->document_type_description)) {
                    $type = $guide->document_type_description;
                } else {
                    if ($guide->document_type_id) {
                        $type = $guide->document_type_id;
                    }
                }
                if (!isset($data[$type])) $data[$type] = [];
                $data[$type][] = $guide;
            }
            $type = 'model';
            if ($document->dispatch) {
                /** @var Dispatch $dispatch */
                $dispatch = $document->dispatch;
                if (!isset($data[$type])) $data[$type] = [];
                $data[$type][] = $dispatch->series . "-" . $dispatch->number;
            }

            return $data;
        }

    }
