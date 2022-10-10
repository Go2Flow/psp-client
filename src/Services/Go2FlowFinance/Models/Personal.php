<?php

namespace Go2Flow\PSPClient\Services\Go2FlowFinance\Models;

class Personal
{

    private $personal;

    public function __construct()
    {
        $this->personal = collect([]);
    }

    /**
     * @param string $company
     * @return $this
     */
    public function setCompany(string $company): static
    {
        $this->personal->put('company', $company);

        return $this;
    }

    /**
     * @param string $salutation
     * @return $this
     */
    public function setSalutation(string $salutation): static
    {
        $this->personal->put('salutation', $salutation);

        return $this;
    }

    /**
     * @param string $first_name
     * @return $this
     */
    public function setFirstName(string $first_name): static
    {
        $this->personal->put('first_name', $first_name);

        return $this;
    }

    /**
     * @param string $last_name
     * @return $this
     */
    public function setLastName(string $last_name): static
    {
        $this->personal->put('last_name', $last_name);

        return $this;
    }

    /**
     * @param string $address
     * @return $this
     */
    public function setAddress(string $address): static
    {
        $this->personal->put('address', $address);

        return $this;
    }

    /**
     * @param string $zip
     * @return $this
     */
    public function setZip(string $zip): static
    {
        $this->personal->put('zip', $zip);

        return $this;
    }

    /**
     * @param string $city
     * @return $this
     */
    public function setCity(string $city): static
    {
        $this->personal->put('city', $city);

        return $this;
    }

    /**
     * @param $country
     * @return $this
     */
    public function setCountry($country): static
    {
        $this->personal->put('country', $country);

        return $this;
    }

    /**
     * @param string $phone_prefix
     * @return $this
     */
    public function setPhonePrefix(string $phone_prefix): static
    {
        $this->personal->put('phone_prefix', $phone_prefix);

        return $this;
    }

    /**
     * @param $phone_number
     * @return $this
     */
    public function setPhoneNumber($phone_number): static
    {
        $this->personal->put('phone_number', $phone_number);

        return $this;
    }

    /**
     * @param int $business
     * @return $this
     */
    public function setBusiness(int $business): static
    {
        $this->personal->put('business', $business);

        return $this;
    }

    /**
     * @param int $legal_form
     * @return $this
     */
    public function setLegalForm(int $legal_form): static
    {
        $this->personal->put('legal_form', $legal_form);

        return $this;
    }

    /**
     * @param int $employees
     * @return $this
     */
    public function setEmployees(int $employees): static
    {
        $this->personal->put('employees', $employees);

        return $this;
    }

    /**
     * @param int $field_of_competence
     * @return $this
     */
    public function setFieldOfCompetence(int $field_of_competence): static
    {
        $this->personal->put('field_of_competence', $field_of_competence);

        return $this;
    }

    /**
     * @param string|null $vat_nr
     * @return $this
     */
    public function setVatNr(?string $vat_nr): static
    {
        $this->personal->put('vat_nr', $vat_nr);

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
