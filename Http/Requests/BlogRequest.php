<?php

namespace Modules\Blog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class BlogRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        $this->merge([
            'author_id' => Auth::id(),
            'tags' => explode(",", $this->get("tags"))
        ]);
    }

    public function rules(): array
    {
        return match ($this->method()) {
            "POST" => $this->getPostRules(),
            "PUT" => $this->getPutRules()
        };
    }

    private function getPostRules(): array
    {
        return [
            "title" => "required",
            "subtitle" => "required",
            "magazine_id" => "required|exists:magazines,id",
            "summary" => "required",
            "content" => "required",
            "tags" => "required",
            "photo_id" => 'required'
        ];
    }

    private function getPutRules(): array
    {
        return [
            "title" => "required",
            "subtitle" => "required",
            "magazine_id" => "required|exists:magazines,id",
            "summary" => "required",
            "content" => "required",
            "tags" => "required"
        ];
    }
}
