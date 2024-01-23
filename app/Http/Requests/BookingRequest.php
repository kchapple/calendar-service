<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    public function rules() {
        return [
            'startDate' => 'required|date',
            'endDate' => 'required|date',
            'timezone' => 'required'
        ];
    }
}
