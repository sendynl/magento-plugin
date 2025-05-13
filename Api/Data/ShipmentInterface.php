<?php

namespace Edifference\Sendy\Api\Data;

interface ShipmentInterface
{
    /**
     * String constants for property names
     */
    public const SHOP_ID = "shop_id";
    public const PREFERENCE_ID = "preference_id";
    public const COMPANY_NAME = "company_name";
    public const CONTACT = "contact";
    public const VAT_NUMBER = "vat_number";
    public const STREET = "street";
    public const NUMBER = "number";
    public const ADDITION = "addition";
    public const COMMENT = "comment";
    public const POSTAL_CODE = "postal_code";
    public const CITY = "city";
    public const PHONE = "phone";
    public const EMAIL = "email";
    public const COUNTRY = "country";
    public const REFERENCE = "reference";
    public const WEIGHT = "weight";
    public const AMOUNT = "amount";
    public const ORDER_DATE = "order_date";
    public const SHIPPING_METHOD_ID = "shippingMethodId";
    public const OPTIONS = "options";
    public const PRODUCTS = "products";

    /**
     * Getter for ShopId.
     *
     * @return string|null
     */
    public function getShopId(): ?string;

    /**
     * Setter for ShopId.
     *
     * @param string|null $shopId
     * @return ShipmentInterface
     */
    public function setShopId(?string $shopId): ShipmentInterface;

    /**
     * Getter for PreferenceId.
     *
     * @return string|null
     */
    public function getPreferenceId(): ?string;

    /**
     * Setter for PreferenceId.
     *
     * @param string|null $preferenceId
     * @return ShipmentInterface
     */
    public function setPreferenceId(?string $preferenceId): ShipmentInterface;

    /**
     * Getter for CompanyName.
     *
     * @return string|null
     */
    public function getCompanyName(): ?string;

    /**
     * Setter for CompanyName.
     *
     * @param string|null $companyName
     * @return ShipmentInterface
     */
    public function setCompanyName(?string $companyName): ShipmentInterface;

    /**
     * Getter for Contact.
     *
     * @return string|null
     */
    public function getContact(): ?string;

    /**
     * Setter for Contact.
     *
     * @param string|null $contact
     * @return ShipmentInterface
     */
    public function setContact(?string $contact): ShipmentInterface;

    /**
     * Getter for VatNumber.
     *
     * @return string|null
     */
    public function getVatNumber(): ?string;

    /**
     * Setter for VatNumber.
     *
     * @param string|null $vatNumber
     * @return ShipmentInterface
     */
    public function setVatNumber(?string $vatNumber): ShipmentInterface;

    /**
     * Getter for Street.
     *
     * @return string|null
     */
    public function getStreet(): ?string;

    /**
     * Setter for Street.
     *
     * @param string|null $street
     * @return ShipmentInterface
     */
    public function setStreet(?string $street): ShipmentInterface;

    /**
     * Getter for Number.
     *
     * @return string|null
     */
    public function getNumber(): ?string;

    /**
     * Setter for Number.
     *
     * @param string|null $number
     * @return ShipmentInterface
     */
    public function setNumber(?string $number): ShipmentInterface;

    /**
     * Getter for Addition.
     *
     * @return string|null
     */
    public function getAddition(): ?string;

    /**
     * Setter for Addition.
     *
     * @param string|null $addition
     * @return ShipmentInterface
     */
    public function setAddition(?string $addition): ShipmentInterface;

    /**
     * Getter for Comment.
     *
     * @return string|null
     */
    public function getComment(): ?string;

    /**
     * Setter for Comment.
     *
     * @param string|null $comment
     * @return ShipmentInterface
     */
    public function setComment(?string $comment): ShipmentInterface;

    /**
     * Getter for PostalCode.
     *
     * @return string|null
     */
    public function getPostalCode(): ?string;

    /**
     * Setter for PostalCode.
     *
     * @param string|null $postalCode
     * @return ShipmentInterface
     */
    public function setPostalCode(?string $postalCode): ShipmentInterface;

    /**
     * Getter for City.
     *
     * @return string|null
     */
    public function getCity(): ?string;

    /**
     * Setter for City.
     *
     * @param string|null $city
     * @return ShipmentInterface
     */
    public function setCity(?string $city): ShipmentInterface;

    /**
     * Getter for Phone.
     *
     * @return string|null
     */
    public function getPhone(): ?string;

    /**
     * Setter for Phone.
     *
     * @param string|null $phone
     * @return ShipmentInterface
     */
    public function setPhone(?string $phone): ShipmentInterface;

    /**
     * Getter for Email.
     *
     * @return string|null
     */
    public function getEmail(): ?string;

    /**
     * Setter for Email.
     *
     * @param string|null $email
     * @return ShipmentInterface
     */
    public function setEmail(?string $email): ShipmentInterface;

    /**
     * Getter for Country.
     *
     * @return string|null
     */
    public function getCountry(): ?string;

    /**
     * Setter for Country.
     *
     * @param string|null $country
     * @return ShipmentInterface
     */
    public function setCountry(?string $country): ShipmentInterface;

    /**
     * Getter for Reference.
     *
     * @return string|null
     */
    public function getReference(): ?string;

    /**
     * Setter for Reference.
     *
     * @param string|null $reference
     * @return ShipmentInterface
     */
    public function setReference(?string $reference): ShipmentInterface;

    /**
     * Getter for Weight.
     *
     * @return float|null
     */
    public function getWeight(): ?float;

    /**
     * Setter for Weight.
     *
     * @param float|null $weight
     * @return ShipmentInterface
     */
    public function setWeight(?float $weight): ShipmentInterface;

    /**
     * Getter for Amount.
     *
     * @return int|null
     */
    public function getAmount(): ?int;

    /**
     * Setter for Amount.
     *
     * @param int|null $amount
     * @return ShipmentInterface
     */
    public function setAmount(?int $amount): ShipmentInterface;

    /**
     * Getter for OrderDate.
     *
     * @return string|null
     */
    public function getOrderDate(): ?string;

    /**
     * Setter for OrderDate.
     *
     * @param string|null $orderDate
     * @return ShipmentInterface
     */
    public function setOrderDate(?string $orderDate): ShipmentInterface;

    /**
     * Getter for ShippingMethod.
     *
     * @return string|null
     */
    public function getShippingMethodId(): ?string;

    /**
     * Setter for ShippingMethod.
     *
     * @param string|null $shippingMethod
     * @return ShipmentInterface
     */
    public function setShippingMethodId(?string $shippingMethod);

    /**
     * Getter for Options.
     *
     * @return array|null
     */
    public function getOptions(): ?array;

    /**
     * Setter for Options.
     *
     * @param array|null $options
     * @return ShipmentInterface
     */
    public function setOptions(?array $options): ShipmentInterface;

    /**
     * Getter for Products.
     *
     * @return array|null
     */
    public function getProducts(): ?array;

    /**
     * Setter for Products.
     *
     * @param array|null $products
     * @return ShipmentInterface
     */
    public function setProducts(?array $products): ShipmentInterface;
}
