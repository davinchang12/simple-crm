<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{

    public $success, $message;

    public function __construct($success, $message, $resource)
    {
        parent::__construct($resource);
        $this->success = $success;
        $this->message = $message;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'success' => $this->success,
            'message' => $this->message,
            'data' => $this->resource,
        ];

        // return [
        //     'id' => $this->id,
        //     'user_id' => $this->user_id,
        //     'client_id' => $this->client_id,
        //     'title' => $this->title,
        //     'description' => $this->description,
        //     'deadline' => $this->deadline,
        //     'status' => $this->status,
        //     'created_at' => $this->created_at,
        //     'updated_at' => $this->updated_at,
        // ];
    }
}
