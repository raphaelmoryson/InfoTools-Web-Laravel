<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// app/Http/Requests/StoreLeadRequest.php
class StoreLeadRequest extends FormRequest {
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'name'  => ['required','string','max:120'],
            'email' => ['required','email','max:190'],
            'phone' => ['nullable','string','max:30'],
            'message' => ['nullable','string','max:2000'],
            'desired_date' => ['nullable','date','after:today'],
        ];
    }
}

