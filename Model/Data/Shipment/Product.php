<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Model\Data\Shipment;

use Edifference\Sendy\Api\Data\Shipment\ProductInterface;
use Magento\Framework\DataObject;

class Product extends DataObject implements ProductInterface
{
    /**
     * Getter for description.
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->getData(self::DESCRIPTION);
    }

    /**
     * Setter for description.
     *
     * @param string|null $description
     * @return ProductInterface
     */
    public function setDescription(?string $description): ProductInterface
    {
        return $this->setData(
            self::DESCRIPTION,
            $description
        );
    }

    /**
     * Getter for description_en.
     *
     * @return string|null
     */
    public function getDescriptionEn(): ?string
    {
        return $this->getData(self::DESCRIPTION_EN);
    }

    /**
     * Setter for description_en.
     *
     * @param string|null $description
     * @return ProductInterface
     */
    public function setDescriptionEn(?string $description): ProductInterface
    {
        return $this->setData(
            self::DESCRIPTION_EN,
            $description
        );
    }

    /**
     * Getter for Sku.
     *
     * @return string|null
     */
    public function getSku(): ?string
    {
        return $this->getData(self::SKU);
    }

    /**
     * Setter for Sku.
     *
     * @param string|null $sku
     * @return ProductInterface
     */
    public function setSku(?string $sku): ProductInterface
    {
        return $this->setData(
            self::SKU,
            $sku
        );
    }

    /**
     * Getter for quantity.
     *
     * @return string|null
     */
    public function getQuantity(): ?int
    {
        return $this->getData(self::QUANTITY);
    }

    /**
     * Setter for quantity.
     *
     * @param integer|null $quantity
     * @return ProductInterface
     */
    public function setQuantity(?int $quantity): ProductInterface
    {
        return $this->setData(
            self::QUANTITY,
            $quantity
        );
    }

    /**
     * Getter for unit_weight.
     *
     * @return float|null
     */
    public function getUnitWeight(): ?float
    {
        return $this->getData(self::UNIT_WEIGHT);
    }

    /**
     * Setter for unit_weight.
     *
     * @param float|null $weight
     * @return ProductInterface
     */
    public function setUnitWeight(?float $weight): ProductInterface
    {
        return $this->setData(
            self::UNIT_WEIGHT,
            $weight
        );
    }

    /**
     * Getter for unit_price.
     *
     * @return float|null
     */
    public function getUnitPrice(): ?float
    {
        return $this->getData(self::UNIT_PRICE);
    }

    /**
     * Setter for unit_price.
     *
     * @param float|null $unitPrice
     * @return ProductInterface
     */
    public function setUnitPrice(?float $unitPrice): ProductInterface
    {
        return $this->setData(
            self::UNIT_PRICE,
            $unitPrice
        );
    }

    /**
     * Getter for hs_tariff_number.
     *
     * @return string|null
     */
    public function getHsTariffNumber(): ?string
    {
        return $this->getData(self::HS_TARIFF_NUMBER);
    }

    /**
     * Setter for hs_tariff_number.
     *
     * @param string|null $number
     * @return ProductInterface
     */
    public function setHsTariffNumber(?string $number): ProductInterface
    {
        return $this->setData(
            self::HS_TARIFF_NUMBER,
            $number
        );
    }

    /**
     * Getter for origin_country_code.
     *
     * @return string|null
     */
    public function getOriginCountryCode(): ?string
    {
        return $this->getData(self::ORIGIN_COUNTRY_CODE);
    }

    /**
     * Setter for origin_country_code.
     *
     * @param string|null $originCountryCode
     * @return ProductInterface
     */
    public function setOriginCountryCode(?string $originCountryCode): ProductInterface
    {
        return $this->setData(
            self::ORIGIN_COUNTRY_CODE,
            $originCountryCode
        );
    }
}
