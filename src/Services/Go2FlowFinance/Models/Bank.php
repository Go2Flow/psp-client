<?php

namespace Go2Flow\PSPClient\Services\Go2FlowFinance\Models;

class Bank
{

    /**
     * @var \Illuminate\Support\Collection
     */
    private $bank;
    /** @var string */
    private $merchant_id;
    /** @var string */
    private $currency;

    public function __construct()
    {
        $this->bank = collect([]);
    }

    /**
     * @return string
     */
    public function getMerchantId() :string
    {
        return $this->merchant_id;
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
    public function getCurrency() :string
    {
        return strtoupper($this->currency);
    }

    /**
     * @param string $currency
     * @return $this
     */
    public function setCurrency(string $currency) :self
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @param string $country
     * @return $this
     */
    public function setCountry(string $country): static
    {
        $this->bank->put('country', $country);

        return $this;
    }

    /**
     * @param string $iban
     * @return $this
     */
    public function setIban(string $iban): static
    {
        $this->bank->put('iban', $iban);

        return $this;
    }

    /**
     * @param string|null $bic
     * @return $this
     */
    public function setBic(?string $bic): static
    {
        $this->bank->put('bic', $bic);

        return $this;
    }

    /**
     * @param string $holderName
     * @return $this
     */
    public function setHolderName(string $holderName): static
    {
        $this->bank->put('holder_name', $holderName);

        return $this;
    }

    /**
     * @param bool $default
     * @return $this
     */
    public function setIsDefault(bool $default = true): static
    {
        $this->bank->put('is_default', $default);

        return $this;
    }

    /**
     * @return string
     */
    public function toJson(): string
    {
        return $this->bank->toJson();
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->bank->toArray();
    }
}
