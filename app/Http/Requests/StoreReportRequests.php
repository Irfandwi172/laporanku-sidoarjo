<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'location' => 'required|string|max:500',
            'reporter_name' => 'required|string|max:100',
            'reporter_phone' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Judul laporan wajib diisi',
            'title.max' => 'Judul laporan maksimal 255 karakter',
            'description.required' => 'Deskripsi laporan wajib diisi',
            'description.max' => 'Deskripsi laporan maksimal 2000 karakter',
            'location.required' => 'Lokasi wajib diisi',
            'location.max' => 'Lokasi maksimal 500 karakter',
            'reporter_name.required' => 'Nama pelapor wajib diisi',
            'reporter_name.max' => 'Nama pelapor maksimal 100 karakter',
            'reporter_phone.regex' => 'Format nomor telepon tidak valid',
            'reporter_phone.min' => 'Nomor telepon minimal 10 digit',
            'reporter_phone.max' => 'Nomor telepon maksimal 20 karakter',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus JPEG, PNG, atau JPG',
            'image.max' => 'Ukuran gambar maksimal 2MB'
        ];
    }

    protected function prepareForValidation()
    {
        // Clean phone number before validation
        if ($this->reporter_phone) {
            $this->merge([
                'reporter_phone' => preg_replace('/[^0-9]/', '', $this->reporter_phone)
            ]);
        }
    }
}