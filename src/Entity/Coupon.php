<?php

namespace App\Entity;

use App\Repository\CouponRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CouponRepository::class)]
#[ORM\Index(name: "coupon_index", columns: ['used', 'code'])]
class Coupon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private string $code = "";

    #[ORM\Column]
    private bool $used = false;

    #[ORM\Column]
    private CouponType $type = CouponType::Fixed;

    #[ORM\Column]
    private int $value = 0;

    public function __construct(string $code, CouponType $type, int $value)
    {
        $this->code = $code;
        $this->type = $type;
        $this->value = $value;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function isUsed(): bool
    {
        return $this->used;
    }

    public function setUsed(): void
    {
        $this->used = true;
    }

    public function getType(): CouponType
    {
        return $this->type;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function isFixed(): bool
    {
        return $this->type == CouponType::Fixed;
    }
}

