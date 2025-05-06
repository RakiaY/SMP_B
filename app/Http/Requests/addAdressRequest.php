<?php

namespace App\Http\Requests;
use App\Enums\AddressType;
use Illuminate\Validation\Rule;


use Illuminate\Foundation\Http\FormRequest;

class addAdressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
        'addresses' => 'required|array|size:2',
        'addresses.*.address_type' => ['required', Rule::enum(AddressType::class)],
        'addresses.*.city' => 'required|string|max:100',
        'addresses.*.street' => 'required|string|max:255',
        'addresses.*.zipcode' => 'required|string|max:20',
        ];
    }
}
