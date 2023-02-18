<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PostRepository;


/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post implements \JsonSerializable
{
  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private string $title;

  /**
   * @ORM\Column(type="text", length=65536)
   */
  private string $description;

  /**
   * @ORM\Column(type="datetime")
   */
  private \DateTime $dateAdded;

  /**
   * @ORM\Column(type="datetime")
   */
  private \DateTime $lastUpdated;

  /**
   * @ORM\Column(type="text", length=65536)
   */
  private string $image;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function setTitle(string $title): void
  {
    $this->title = $title;
  }

  public function getTitle(): string
  {
    return $this->title;
  }

  public function setDescription(string $description): void
  {
    $this->description = $description;
  }

  public function getDescription(): string
  {
    return $this->description;
  }

  public function setDateAdded(\DateTime $dateTime): void
  {
    $this->dateAdded = $dateTime;
  }

  public function getDateAdded(): \DateTime
  {
    return $this->dateAdded;
  }

  public function setLastUpdated(\DateTime $dateTime): void
  {
    $this->lastUpdated = $dateTime;
  }

  public function getLastUpdated(): \DateTime
  {
    return $this->lastUpdated;
  }

  public function setImage(string $image): void
  {
    $this->image = $image;
  }

  public function getImage(): string
  {
    return $this->image;
  }

  public function jsonSerialize()
  {
    return [ 
      'title'       => $this->getTitle(),
      'description' => $this->getDescription(),
      'dateAdded'   => $this->getDateAdded(),
      'lastUpdated' => $this->getLastUpdated(),
      'image'       => $this->getImage(),
    ];
  }
}
