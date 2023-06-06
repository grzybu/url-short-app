<?php

namespace App\Entity;

use App\Repository\ShortUrlRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\Timestampable;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ShortUrlRepository::class)]
class ShortUrl
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Ignore]
    private Uuid $id;

    #[ORM\Column(length: 255)]
    private ?string $shortUrl = null;

    #[ORM\Column(length: 255)]
    private ?string $longUrl = null;

    #[ORM\Column(length: 4)]
    private ?string $shortId = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getShortUrl(): ?string
    {
        return $this->shortUrl;
    }

    public function setShortUrl(string $shortUrl): self
    {
        $this->shortUrl = $shortUrl;

        return $this;
    }

    public function getLongUrl(): ?string
    {
        return $this->longUrl;
    }

    public function setLongUrl(string $longUrl): self
    {
        $this->longUrl = $longUrl;

        return $this;
    }

    public function getShortId(): ?string
    {
        return $this->shortId;
    }

    public function setShortId(string $shortId): self
    {
        $this->shortId = $shortId;

        return $this;
    }
}
