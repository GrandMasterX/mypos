<?php
namespace grandmasterx\mypos\api;

/**
 * Class Customer
 * @package grandmasterx\mypos\api
 */
class Customer
{

    /**
     * @var
     */
    private $email;

    /**
     * @var
     */
    private $phone;

    /**
     * @var
     */
    private $firstName;

    /**
     * @var
     */
    private $lastName;

    /**
     * @var
     */
    private $country;

    /**
     * @var
     */
    private $city;

    /**
     * @var
     */
    private $zip;

    /**
     * @var
     */
    private $address;

    /**
     * Customer Email address
     * @param string $email
     * @return Customer
     */
    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    /**
     * Customer Email address
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Customer Phone number
     * @param string $phone
     * @return Customer
     */
    public function setPhone($phone) {
        $this->phone = $phone;
        return $this;
    }

    /**
     * Customer Phone number
     * @return string
     */
    public function getPhone() {
        return $this->phone;
    }

    /**
     * Customer first name
     * @param string $firstName
     * @return Customer
     */
    public function setFirstName($firstName) {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * Customer first name
     * @return string
     */
    public function getFirstName() {
        return $this->firstName;
    }

    /**
     * Customer last name
     * @param string $lastName
     * @return Customer
     */
    public function setLastName($lastName) {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * Customer last name
     * @return string
     */
    public function getLastName() {
        return $this->lastName;
    }

    /**
     * Customer country code ISO 3166-1
     * @param type $country
     * @return Customer
     */
    public function setCountry($country) {
        $this->country = $country;
        return $this;
    }

    /**
     * Customer country code ISO 3166-1
     * @return string
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * Customer city
     * @param string $city
     * @return Customer
     */
    public function setCity($city) {
        $this->city = $city;
        return $this;
    }

    /**
     * Customer city
     * @return string
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * Customer ZIP code
     * @param string $zip
     * @return Customer
     */
    public function setZip($zip) {
        $this->zip = $zip;
        return $this;
    }

    /**
     * Customer ZIP code
     * @return string
     */
    public function getZip() {
        return $this->zip;
    }

    /**
     * Customer address
     * @param string $address
     * @return Customer
     */
    public function setAddress($address) {
        $this->address = $address;
        return $this;
    }

    /**
     * Customer address
     * @return string
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * Validate all set customer details
     * @return boolean
     * @throws IPC_Exception
     */
    public function validate() {
        if ($this->getEmail() == null || !Helper::isValidEmail($this->getEmail())) {
            throw new IPC_Exception('Invalid Email');
        }
        return true;
    }
}
