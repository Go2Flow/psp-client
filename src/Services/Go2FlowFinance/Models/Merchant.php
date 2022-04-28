<?php

namespace Go2Flow\PSPClient\Services\Go2FlowFinance\Models;

class Merchant
{

    private $merchant;

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
        $this->merchant->send_welcome_mail = $send_welcome_mail;

        return $this;
    }

    /**
     * @param int $activate_psp_36
     * @return $this
     */
    public function setActivatePSP36(int $activate_psp_36): static
    {
        $this->merchant->activate_psp_36 = $activate_psp_36;

        return $this;
    }

    /**
     * @param string $subdomain
     * @return $this
     */
    public function setSubdomain(string $subdomain): static
    {
        $this->merchant->subdomain = $subdomain;

        return $this;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): static
    {
        $this->merchant->email = $email;

        return $this;
    }

    /**
     * @param string $language
     * @return $this
     */
    public function setLanguage(string $language): static
    {
        $this->merchant->language = $language;

        return $this;
    }

    /**
     * @param string $reference
     * @return $this
     */
    public function setReference(string $reference): static
    {
        $this->merchant->reference = $reference;

        return $this;
    }

    /**
     * @param Personal $personal
     * @return $this
     */
    public function setMerchantData(Personal $personal): static
    {
        $this->merchant->merchant_data = $personal;

        return $this;
    }

    /**
     * @return string
     */
    public function toJson(): string
    {
        return collect($this->personal)->toJson();
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return collect($this->personal)->toArray();
    }
}
