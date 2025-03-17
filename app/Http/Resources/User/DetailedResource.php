<?php

namespace App\Http\Resources\User;

use Carbon\Carbon;
use Illuminate\Http\Request;

class DetailedResource extends SimpleResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
        $data['login'] = $this->login;
        $data['created_at'] = Carbon::parse($this->created_at)->toDateTimeString();

        return $data;
    }
}
