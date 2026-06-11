<?php

namespace Vendor\HetznerCloud\DTOs;

class Action
{
    public int $id;

    public string $command;

    public string $status;

    public int $progress;

    public string $started;

    public ?string $finished = null;

    public array $resources = [];

    public ?array $error = null;

    /**
     * Create a new DTO instance from array data.
     */
    public static function fromArray(array $data): self
    {
        $action = new self;
        $action->id = (int) ($data['id'] ?? 0);
        $action->command = (string) ($data['command'] ?? '');
        $action->status = (string) ($data['status'] ?? '');
        $action->progress = (int) ($data['progress'] ?? 0);
        $action->started = (string) ($data['started'] ?? '');
        $action->finished = isset($data['finished']) ? (string) $data['finished'] : null;
        $action->resources = (array) ($data['resources'] ?? []);
        $action->error = isset($data['error']) ? (array) $data['error'] : null;

        return $action;
    }
}
