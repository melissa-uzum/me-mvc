<?php

namespace App\Sustainability\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "measurement")]
class Measurement
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column]
    private int $year;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $value;

    #[ORM\Column(length: 20)]
    private string $unit;

    #[ORM\Column(length: 100)]
    private string $country;

    #[ORM\Column(length: 10, options: ["default" => "original"])]
    private ?string $source = 'original';

    #[ORM\ManyToOne(targetEntity: Indicator::class, inversedBy: 'measurements')]
    #[ORM\JoinColumn(nullable: false)]
    private Indicator $indicator;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYear(): int
    {
        return $this->year;
    }
    public function setYear(int $year): void
    {
        $this->year = $year;
    }

    public function getValue(): float
    {
        return $this->value;
    }
    public function setValue(float $value): void
    {
        $this->value = $value;
    }

    public function getUnit(): string
    {
        return $this->unit;
    }
    public function setUnit(string $unit): void
    {
        $this->unit = $unit;
    }

    public function getCountry(): string
    {
        return $this->country;
    }
    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }
    public function setSource(?string $source): void
    {
        $this->source = $source;
    }

    public function getIndicator(): Indicator
    {
        return $this->indicator;
    }
    public function setIndicator(Indicator $indicator): void
    {
        $this->indicator = $indicator;
    }
}
