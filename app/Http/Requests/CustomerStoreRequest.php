<?php

namespace App\Http\Requests;

use App\Models\Customer;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerStoreRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $customerId = $this->route('customer');

        $customer = $customerId ? Customer::find($customerId) : null;

        return [
            'name' => 'required|string|max:100',
            'email' => [
                'required',
                'email',
                $customer
                    ? Rule::unique('users', 'email')->ignore($customer->user->id)
                    : Rule::unique('users', 'email'),
            ],
            'phone' => 'required|string|max:25',
            'type' => 'required|in:regular,exclusive',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama maksimal 100 karakter.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan, silakan pakai email lain.',

            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.string' => 'Nomor telepon harus berupa teks.',
            'phone.max' => 'Nomor telepon maksimal 25 karakter.',

            'type.required' => 'Tipe customer wajib dipilih.',
            'type.in' => 'Tipe customer harus berupa regular atau exclusive.',

            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status harus berupa active atau inactive.',
        ];
    }
}
