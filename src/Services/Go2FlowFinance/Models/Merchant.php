<?php

namespace Go2Flow\PSPClient\Services\Go2FlowFinance\Models;

class Merchant
{

    /**
     * @var \Illuminate\Support\Collection
     */
    private $merchant;

    public function __construct()
    {
        $this->merchant = collect([]);
    }

    /**
     * @return string
     */
    public function getId() :string
    {
        return $this->merchant->id;
    }

    /**
     * @param string $send_welcome_mail
     * @return $this
     */
    public function setSendWelcomeMail(string $send_welcome_mail): static
    {
        $this->merchant->put('send_welcome_mail', $send_welcome_mail);

        return $this;
    }

    /**
     * @param int $activate_psp_36
     * @return $this
     */
    public function setActivatePSP36(int $activate_psp_36): static
    {
        $this->merchant->put('activate_psp_36', $activate_psp_36);

        return $this;
    }

    /**
     * @param string $subdomain
     * @return $this
     */
    public function setSubdomain(string $subdomain): static
    {
        $this->merchant->put('subdomain', $subdomain);

        return $this;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): static
    {
        $this->merchant->put('email', $email);

        return $this;
    }

    /**
     * @param string $language
     * @return $this
     */
    public function setLanguage(string $language): static
    {
        $this->merchant->put('language', $language);

        return $this;
    }

    /**
     * @param string $reference
     * @return $this
     */
    public function setReference(string $reference): static
    {
        $this->merchant->put('reference', $reference);

        return $this;
    }

    /**
     * @param Personal $personal
     * @return $this
     */
    public function setMerchantData(Personal $personal): static
    {
        $this->merchant->put('merchant_data', $personal->toArray());

        return $this;
    }

    /**
     * @return string
     */
    public function toJson(): string
    {
        return $this->merchant->toJson();
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->merchant->toArray();
    }
}
