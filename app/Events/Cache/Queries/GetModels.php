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

class GetModels
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private Model $model;
    private array $options;
    private mixed $data;
    public function __construct(Model $model, array $options, mixed $data = null)
    {
        $this->model = $model;
        $this->options = $options;
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

    public function getData(): mixed {
        return $this->data;
    }

    public function setData(mixed $value) {
        $this->data = $value;
    }

    public function getModel() {
        return $this->model;
    }

    public function getCacheKey(): string {
        $key = class_basename($this->model);
        $key .= !empty($this->options['perpage'])
            ? ":limit:{$this->options['perpage']}:offset:" . (($this->options['page'] ?? 1) - 1) * $this->options['perpage']
            : ":all";
        $key .= !empty($this->options['orderBy']) ? ":orderBy:" . json_encode($this->options['orderBy']) : "";
        return $key;
    }
}
