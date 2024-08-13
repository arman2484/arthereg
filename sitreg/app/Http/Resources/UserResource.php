<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
      'email / mobile' => $this->email ?? $this->mobile,
      'image' =>  url('/assets/images/users_images/' . $this->image),
    ];
  }
  public function showWithDetails($data)
  {
    $imageUrl = !empty($this->image) ? url('/assets/images/users_images/' . $this->image) : '';
    return [
      'id' => (string) $this->id,
      'first_name' => (string) $this->first_name,
      'last_name' => (string) $this->last_name,
      'username' => (string)$this->username,
      'dob' => (string) $this->dob,
      'gender' => (string) $this->gender,
      'email' => (string) $this->email,
      'mobile' => (string) $this->mobile,
      'image' => $imageUrl,
      'created_at' => (string) $this->created_at,
    ];
  }
}
