<?php

namespace App\Events\Cache\Queries;

use App\Interfaces\Cache\Events\ICacheEvent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GetModelById
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private Model $model;
    private int | string $id;
    private mixed $data;

    /**
     * Create a new event instance.
     */
    public function __construct(Model $model, int | string $id, mixed $data = null) {
        $this->model = $model;
        $this->id = $id;
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }

    public function setData(mixed $value) {
        $this->data = $value;
    }

    public function getData(): mixed {
        return $this->data;
    }

    public function getModel() {
        return $this->model;
    }

    public function getCacheKey(): string {
        $key = class_basename($this->model);
        $key .= ":$this->id";
        return $key;
    }
}
