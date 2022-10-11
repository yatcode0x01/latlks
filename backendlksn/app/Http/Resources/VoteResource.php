<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Choice;
use App\Models\User;
use App\Models\Poll;
use App\Models\Division;

class VoteResource extends JsonResource
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
            'id' => $this->id,
            'user' => User::where('id', '=', $this->user_id)->get(),
            'division' => Division::where('id', '=', $this->division_id)->get(),
            'poll' => Poll::where('id', '=', $this->poll_id)->get(),
            'choice' => Choice::where('id', '=', $this->choice_id)->get(),
            'created_at' => $this->created_at,
            'updated_at' => $this->update_at,
            'deleted_at' => $this->deleted_at
        ];
    }
}
