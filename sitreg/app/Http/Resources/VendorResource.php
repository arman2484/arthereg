<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray($request)
  {
    return [
      'id' => (string) $this->id,
      'first_name' => (string) $this->first_name,
      'last_name' => (string) $this->last_name,
      'dob' => (string) $this->dob,
      'gender' => (string) $this->gender,
      'email' => (string) $this->email,
      'mobile' => (string) $this->mobile,
      'country_code' => (string) $this->country_code,
      'device_token' => (string) $this->device_token,
      'image' =>  url('assets/images/users_images/' . $this->image),
    ];
  }
  public function showWithDetails($data)
  {
    return [
      'id' => (string) $this->id,
      'first_name' => (string) $this->first_name,
      'last_name' => (string) $this->last_name,
      'dob' => (string) $this->dob,
      'gender' => (string) $this->gender,
      'email' => (string) $this->email,
      'mobile' => (string) $this->mobile,
      'country_code' => (string)$this->country_code,
      'device_token' => (string) $this->device_token,
       'image' => $this->image? url('assets/images/users_images/' . $this->image) : "",

    ];
  }
}
