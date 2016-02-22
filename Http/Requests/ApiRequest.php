<?php namespace Modules\Documents\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiRequest extends FormRequest {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$this->sanitize();
		
		return [];
	}

	/**
	 * Sanitize input with special rules
	 *
	 * @param $request
	 * @return mixed
	 */
	public function sanitize()
	{
		$input = $this->all();

		if(empty($input['parent_uid']) || strtolower($input['parent_uid']) == 'null'){
			$input['parent_uid'] = null;
		}

		$this->replace($input);
	}

}
