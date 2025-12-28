<?php

namespace App\Events\Cache\Updates;

use App\Interfaces\Cache\Events\ICacheEvent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModelCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private Model $model;
    private mixed $data;

    /**
     * Create a new event instance.
     */
    public function __construct(Model $model, mixed $data) {
        $this->model = $model;
        $this->data = $data;
    }

    public function getModel(): Model {
        return $this->model;
    }

    public function getData(): mixed {
        return $this->data;
    }

    public function getCacheKey(): string {
        $key = class_basename($this->model);
        $key .= ":{$this->data->id}";
        return $key;
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
}
