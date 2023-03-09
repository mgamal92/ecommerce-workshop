<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrdersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => (string) $this->id,
            'status' => $this->status,
            'email' => $this->email,
            'fname' => $this->fname,
            'lname' => $this->lname,
            'street' => $this->street,
            'building' => $this->building,
            'floor' => $this->floor,
            'apartment' => $this->apartment,
            'additional_info' => $this->additional_info,
            'phone' => $this->phone,
            'payment_method' => $this->payment_method,
            'shipping_fees' => $this->shipping_fees,
            'total_amount' => $this->total_amount,
            'order_id' => $this->order_id,
            'user_id' => $this->user_id,
            'order_items' => OrderItemsResource::collection($this->items),
            'attributes' => [
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ]
        ];
    }
}
