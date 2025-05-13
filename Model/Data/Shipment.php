<?php

namespace Edifference\Sendy\Model\Data;

use Edifference\Sendy\Api\Data\ShipmentInterface;
use Magento\Framework\DataObject;

class Shipment extends DataObject implements ShipmentInterface
{
    /**
     * Getter for ShopId.
     *
     * @return string|null
     */
    public function getShopId(): ?string
    {
        return $this->getData(self::SHOP_ID);
    }

    /**
     * Setter for ShopId.
     *
     * @param string|null $shopId
     * @return ShipmentInterface
     */
    public function setShopId(?string $shopId): ShipmentInterface
    {
        return $this->setData(
            self::SHOP_ID,
            $shopId
        );
    }

    /**
     * Getter for PreferenceId.
     *
     * @return string|null
     */
    public function getPreferenceId(): ?string
    {
        return $this->getData(self::PREFERENCE_ID);
    }

    /**
     * Setter for PreferenceId.
     *
     * @param string|null $preferenceId
     * @return ShipmentInterface
     */
    public function setPreferenceId(?string $preferenceId): ShipmentInterface
    {
        return $this->setData(
            self::PREFERENCE_ID,
            $preferenceId
        );
    }

    /**
     * Getter for CompanyName.
     *
     * @return string|null
     */
    public function getCompanyName(): ?string
    {
        return $this->getData(self::COMPANY_NAME);
    }

    /**
     * Setter for CompanyName.
     *
     * @param string|null $companyName
     * @return ShipmentInterface
     */
    public function setCompanyName(?string $companyName): ShipmentInterface
    {
        return $this->setData(
            self::COMPANY_NAME,
            $companyName
        );
    }

    /**
     * Getter for Contact.
     *
     * @return string|null
     */
    public function getContact(): ?string
    {
        return $this->getData(self::CONTACT);
    }

    /**
     * Setter for Contact.
     *
     * @param string|null $contact
     * @return ShipmentInterface
     */
    public function setContact(?string $contact): ShipmentInterface
    {
        return $this->setData(
            self::CONTACT,
            $contact
        );
    }

    /**
     * Getter for VatNumber.
     *
     * @return string|null
     */
    public function getVatNumber(): ?string
    {
        return $this->getData(self::VAT_NUMBER);
    }

    /**
     * Setter for VatNumber.
     *
     * @param string|null $vatNumber
     * @return ShipmentInterface
     */
    public function setVatNumber(?string $vatNumber): ShipmentInterface
    {
        return $this->setData(
            self::VAT_NUMBER,
            $vatNumber
        );
    }

    /**
     * Getter for Street.
     *
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->getData(self::STREET);
    }

    /**
     * Setter for Street.
     *
     * @param string|null $street
     * @return ShipmentInterface
     */
    public function setStreet(?string $street): ShipmentInterface
    {
        return $this->setData(
            self::STREET,
            $street
        );
    }

    /**
     * Getter for Number.
     *
     * @return string|null
     */
    public function getNumber(): ?string
    {
        return $this->getData(self::NUMBER);
    }

    /**
     * Setter for Number.
     *
     * @param string|null $number
     * @return ShipmentInterface
     */
    public function setNumber(?string $number): ShipmentInterface
    {
        return $this->setData(
            self::NUMBER,
            $number
        );
    }

    /**
     * Getter for Addition.
     *
     * @return string|null
     */
    public function getAddition(): ?string
    {
        return $this->getData(self::ADDITION);
    }

    /**
     * Setter for Addition.
     *
     * @param string|null $addition
     * @return ShipmentInterface
     */
    public function setAddition(?string $addition): ShipmentInterface
    {
        return $this->setData(
            self::ADDITION,
            $addition
        );
    }

    /**
     * Getter for Comment.
     *
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->getData(self::COMMENT);
    }

    /**
     * Setter for Comment.
     *
     * @param string|null $comment
     * @return ShipmentInterface
     */
    public function setComment(?string $comment): ShipmentInterface
    {
        return $this->setData(
            self::COMMENT,
            $comment
        );
    }

    /**
     * Getter for PostalCode.
     *
     * @return string|null
     */
    public function getPostalCode(): ?string
    {
        return $this->getData(self::POSTAL_CODE);
    }

    /**
     * Setter for PostalCode.
     *
     * @param string|null $postalCode
     * @return ShipmentInterface
     */
    public function setPostalCode(?string $postalCode): ShipmentInterface
    {
        return $this->setData(
            self::POSTAL_CODE,
            $postalCode
        );
    }

    /**
     * Getter for City.
     *
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->getData(self::CITY);
    }

    /**
     * Setter for City.
     *
     * @param string|null $city
     * @return ShipmentInterface
     */
    public function setCity(?string $city): ShipmentInterface
    {
        return $this->setData(
            self::CITY,
            $city
        );
    }

    /**
     * Getter for Phone.
     *
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->getData(self::PHONE);
    }

    /**
     * Setter for Phone.
     *
     * @param string|null $phone
     * @return ShipmentInterface
     */
    public function setPhone(?string $phone): ShipmentInterface
    {
        return $this->setData(
            self::PHONE,
            $phone
        );
    }

    /**
     * Getter for Email.
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->getData(self::EMAIL);
    }

    /**
     * Setter for Email.
     *
     * @param string|null $email
     * @return ShipmentInterface
     */
    public function setEmail(?string $email): ShipmentInterface
    {
        return $this->setData(
            self::EMAIL,
            $email
        );
    }

    /**
     * Getter for Country.
     *
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->getData(self::COUNTRY);
    }

    /**
     * Setter for Country.
     *
     * @param string|null $country
     * @return ShipmentInterface
     */
    public function setCountry(?string $country): ShipmentInterface
    {
        return $this->setData(
            self::COUNTRY,
            $country
        );
    }

    /**
     * Getter for Reference.
     *
     * @return string|null
     */
    public function getReference(): ?string
    {
        return $this->getData(self::REFERENCE);
    }

    /**
     * Setter for Reference.
     *
     * @param string|null $reference
     * @return ShipmentInterface
     */
    public function setReference(?string $reference): ShipmentInterface
    {
        return $this->setData(
            self::REFERENCE,
            $reference
        );
    }

    /**
     * Getter for Weight.
     *
     * @return float|null
     */
    public function getWeight(): ?float
    {
        return $this->getData(self::WEIGHT) === null ? null : (float)$this->getData(self::WEIGHT);
    }

    /**
     * Setter for Weight.
     *
     * @param float|null $weight
     * @return ShipmentInterface
     */
    public function setWeight(?float $weight): ShipmentInterface
    {
        return $this->setData(
            self::WEIGHT,
            $weight
        );
    }

    /**
     * Getter for Amount.
     *
     * @return int|null
     */
    public function getAmount(): ?int
    {
        return $this->getData(self::AMOUNT) === null ? null : (int)$this->getData(self::AMOUNT);
    }

    /**
     * Setter for Amount.
     *
     * @param int|null $amount
     * @return ShipmentInterface
     */
    public function setAmount(?int $amount): ShipmentInterface
    {
        return $this->setData(
            self::AMOUNT,
            $amount
        );
    }

    /**
     * Getter for OrderDate.
     *
     * @return string|null
     */
    public function getOrderDate(): ?string
    {
        return $this->getData(self::ORDER_DATE);
    }

    /**
     * Setter for OrderDate.
     *
     * @param string|null $orderDate
     * @return ShipmentInterface
     */
    public function setOrderDate(?string $orderDate): ShipmentInterface
    {
        return $this->setData(
            self::ORDER_DATE,
            $orderDate
        );
    }

    /**
     * Getter for ShippingMethod.
     *
     * @return string|null
     */
    public function getShippingMethodId(): ?string
    {
        return $this->getData(self::SHIPPING_METHOD_ID);
    }

    /**
     * Setter for ShippingMethod.
     *
     * @param string|null $shippingMethod
     * @return ShipmentInterface
     */
    public function setShippingMethodId(?string $shippingMethod): ShipmentInterface
    {
        return $this->setData(
            self::SHIPPING_METHOD_ID,
            $shippingMethod
        );
    }

    /**
     * Getter for Options.
     *
     * @return array|null
     */
    public function getOptions(): ?array
    {
        return $this->getData(self::OPTIONS);
    }

    /**
     * Setter for Options.
     *
     * @param array|null $options
     * @return ShipmentInterface
     */
    public function setOptions(?array $options): ShipmentInterface
    {
        return $this->setData(
            self::OPTIONS,
            $options
        );
    }

    /**
     * Getter for Products.
     *
     * @return array|null
     */
    public function getProducts(): ?array
    {
        return $this->getData(self::PRODUCTS);
    }

    /**
     * Setter for Products.
     *
     * @param array|null $products
     * @return ShipmentInterface
     */
    public function setProducts(?array $products): ShipmentInterface
    {
        return $this->setData(
            self::PRODUCTS,
            $products
        );
    }

    /**
     * Parse an address string and set the correct street parts
     *
     * @param string $address
     * @return ShipmentInterface
     */
    public function parseAndSetAddress(string $address): ShipmentInterface
    {
        $parts = explode(' ', trim($address));
        $partsCount = count($parts);
        if ($partsCount === 1) {
            return $this;
        }
        if ($partsCount == 2) {
            if (is_numeric(end($parts))) {
                // House number should be 1
                $this->parseAndSetNumber(end($parts));
                $this->setStreet($parts[0]);
                return $this;
            }
            if (preg_match('/^([0-9]+)([a-zA-Z]+)$/', end($parts))) {
                // House number should be 1a
                $numberPart = end($parts);
                $this->setStreet(rtrim(substr($address, 0, (0 - strlen($numberPart)))));
                $this->parseAndSetNumber($numberPart);
                return $this;
            }
            if (preg_match('/^([0-9]+)([\-\\\+\/]+?)([0-9a-zA-Z]+)$/', end($parts))) {
                // House number should be 1-1someting
                $numberPart = end($parts);
                $this->setStreet(rtrim(substr($address, 0, (0 - strlen($numberPart)))));
                $this->parseAndSetNumber($numberPart);
            }
            return $this;
        }
        if (is_numeric(end($parts))) {
            // House number 1
            $numberPart = end($parts);
            $this->setStreet(rtrim(substr($address, 0, (0 - strlen($numberPart)))));
            $this->parseAndSetNumber($numberPart);
            return $this;
        }
        if (preg_match('/^([0-9]+)([\-\\\+\/]?)([0-9a-zA-Z]+)$/', end($parts))) {
            // House number 1a
            $numberPart = end($parts);
            $this->setStreet(rtrim(substr($address, 0, (0 - strlen($numberPart)))));
            $this->parseAndSetNumber($numberPart);
            return $this;
        }
        if (preg_match('/^([0-9]+)([a-zA-Z\s\-]+)$/', $parts[$partsCount - 2] . ' ' . $parts[$partsCount - 1])) {
            // House number 1 a
            $numberPart = $parts[$partsCount - 2] . ' ' . $parts[$partsCount - 1];
            $this->setStreet(rtrim(substr($address, 0, (0 - strlen($numberPart)))));
            $this->parseAndSetNumber($numberPart);
            return $this;
        }
        $this->setStreet(array_shift($parts));
        $this->parseAndSetNumber(implode(' ', $parts));
        return $this;
    }

    /**
     * Parse an number string and set the correct number parts
     *
     * @param string $number
     * @return ShipmentInterface
     */
    public function parseAndSetNumber(string $number): ShipmentInterface
    {
        if (ctype_digit($number)) {
            $this->setNumber($number);
            return $this;
        }
        $addition = ltrim($number, '0123456789');
        $this->setNumber(substr($number, 0, (0 - strlen($addition))));
        $this->setAddition(trim($addition));
        return $this;
    }
}
