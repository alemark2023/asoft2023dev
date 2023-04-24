<?php

namespace Modules\DocumentaryProcedure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActionRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
			'name' => ['required', 'max:50', Rule::unique('tenant.documentary_actions', 'name')->ignore($this->id)],
            'description' => 'max:250',
			'active'      => 'required|boolean',
		];
	}
}
