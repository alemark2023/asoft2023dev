<?php

    namespace Modules\Suscription\Http\Requests;


    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Validation\Rule;


    /**
     * Class PlanSuscriptionRequest
     *
     * @package Modules\Suscription\Http\Requests
     * @mixin FormRequest
     */
    class PlanSuscriptionRequest extends FormRequest
    {

        /**
         * @return bool
         */
        public function authorize()
        {
            return true;
        }

        /**
         * @return \string[][]
         */
        public function rules()
        {

            return [
                'description' => [
                    'required',
                ],
                'name' => [
                    'required',
                ],
                'periods' => [
                    'required',
                ],
            ];
        }

        /**
         * @return array
         */
        public function messages()
        {
            return [
                'description.required' => 'El campo Descripción es obligatorio.',
                'name.required' => 'El campo Nombre es obligatorio.',
                'periods.required' => 'El campo Periodo es obligatorio.',
            ];
        }
    }
