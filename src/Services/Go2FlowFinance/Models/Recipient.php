<?php

namespace Go2Flow\PSPClient\Services\Go2FlowFinance\Models;

/**
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 */
class Recipient
{

    /**
     * @var \Illuminate\Support\Collection
     */
    private $recipient;

    public function __construct()
    {
        $this->recipient = collect([]);
    }

    public function __set($name, $value)
    {
        $this->recipient->put($name, $value);
    }

    /**
     * @return string
     */
    public function toJson(): string
    {
        return $this->recipient->toJson();
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->recipient->toArray();
    }
}
