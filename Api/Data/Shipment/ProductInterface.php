<?php

namespace Edifference\Sendy\Api\Data\Shipment;

use Edifference\Sendy\Api\Data\ShipmentInterface;

interface ProductInterface
{
    /**
     * String constants for property names
     */
    public const DESCRIPTION = "description";
    public const DESCRIPTION_EN = "description_en";
    public const SKU = "sku";
    public const QUANTITY = "quantity";
    public const UNIT_WEIGHT = "unit_weight";
    public const UNIT_PRICE = "unit_price";
    public const HS_TARIFF_NUMBER = "hs_tariff_number";
    public const ORIGIN_COUNTRY_CODE = "origin_country_code";

    /**
     * Getter for description.
     *
     * @return string|null
     */
    public function getDescription(): ?string;

    /**
     * Setter for description.
     *
     * @param string|null $description
     * @return ProductInterface
     */
    public function setDescription(?string $description): ProductInterface;

    /**
     * Getter for description_en.
     *
     * @return string|null
     */
    public function getDescriptionEn(): ?string;

    /**
     * Setter for description_en.
     *
     * @param string|null $description
     * @return ProductInterface
     */
    public function setDescriptionEn(?string $description): ProductInterface;

    /**
     * Getter for Sku.
     *
     * @return string|null
     */
    public function getSku(): ?string;

    /**
     * Setter for Sku.
     *
     * @param string|null $sku
     * @return ProductInterface
     */
    public function setSku(?string $sku): ProductInterface;

    /**
     * Getter for quantity.
     *
     * @return string|null
     */
    public function getQuantity(): ?int;

    /**
     * Setter for quantity.
     *
     * @param integer|null $quantity
     * @return ProductInterface
     */
    public function setQuantity(?int $quantity): ProductInterface;

    /**
     * Getter for unit_weight.
     *
     * @return float|null
     */
    public function getUnitWeight(): ?float;

    /**
     * Setter for unit_weight.
     *
     * @param float|null $weight
     * @return ProductInterface
     */
    public function setUnitWeight(?float $weight): ProductInterface;

    /**
     * Getter for unit_price.
     *
     * @return float|null
     */
    public function getUnitPrice(): ?float;

    /**
     * Setter for unit_price.
     *
     * @param float|null $unitPrice
     * @return ProductInterface
     */
    public function setUnitPrice(?float $unitPrice): ProductInterface;

    /**
     * Getter for hs_tariff_number.
     *
     * @return string|null
     */
    public function getHsTariffNumber(): ?string;

    /**
     * Setter for hs_tariff_number.
     *
     * @param string|null $number
     * @return ProductInterface
     */
    public function setHsTariffNumber(?string $number): ProductInterface;

    /**
     * Getter for origin_country_code.
     *
     * @return string|null
     */
    public function getOriginCountryCode(): ?string;

    /**
     * Setter for origin_country_code.
     *
     * @param string|null $originCountryCode
     * @return ProductInterface
     */
    public function setOriginCountryCode(?string $originCountryCode): ProductInterface;
}
