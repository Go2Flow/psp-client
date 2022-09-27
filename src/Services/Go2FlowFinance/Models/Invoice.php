<?php

namespace Go2Flow\PSPClient\Services\Go2FlowFinance\Models;

/**
 * @property boolean $send
 * @property string $language
 * @property string $currency
 * @property int $due_after_days
 * @property Recipient $recipient
 * @property array $service_period
 * @property array $positions
 */
class Invoice
{

    /**
     * @var \Illuminate\Support\Collection
     */
    private $invoice;
    /** @var string */
    private $merchant_id;

    public function __construct()
    {
        $this->invoice = collect([]);
    }

    /**
     * @return string
     */
    public function getMerchantId() :string
    {
        return $this->merchant_id;
    }

    public function __set($name, $value)
    {
        $this->invoice->put($name, $value);
    }

    /**
     * @param string $merchantId
     * @return $this
     */
    public function setMerchantId(string $merchantId) :self
    {
        $this->merchant_id = $merchantId;
        return $this;
    }

    /**
     * @return string
     */
    public function toJson(): string
    {
        return $this->invoice->toJson();
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->invoice->toArray();
    }
}
