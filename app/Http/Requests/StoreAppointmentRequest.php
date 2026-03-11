<?php

// app/Http/Requests/StoreAppointmentRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool { return true; } // policy au niveau controller
    public function rules(): array {
        return [
            'customer_id'   => ['required','exists:customers,id'],
            'start_at'      => ['required','date','after:now'],
            'end_at'        => ['nullable','date','after:start_at'],
            'subject'       => ['required','string','max:150'],
            'notes'         => ['nullable','string','max:5000'],
        ];
    }
}
