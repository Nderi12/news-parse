<?php 

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use App\Contracts\SourceUrlInterface;
use App\Repository\SourceUrlRepository;


/**
 * @ORM\Entity(repositoryClass=SourceUrlRepository::class)
 */
class SourceUrl implements SourceUrlInterface
{
  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=255)
   * @Assert\NotBlank
   */
  private string $url;

  /**
   * @ORM\Column(type="string", length=255)
   * @Assert\NotBlank
   */
  private string $wrapperSelector;

  /**
   * @ORM\Column(type="string", length=255)
   * @Assert\NotBlank
   */
  private string $titleSelector;

  /**
   * @ORM\Column(type="string", length=255)
   * @Assert\NotBlank
   */
  private string $descriptionSelector;

  /**
   * @ORM\Column(type="string", length=255)
   * @Assert\NotBlank
   */
  private string $imageSelector;

  /**
   * @param mixed $id
   */
  public function setId($id): void
  {
    $this->id = $id;
  }

  /**
   * @return mixed
   */
  public function getId()
  {
    return $this->id;
  }

  public function setUrl(string $url): void
  {
    $this->url = $url;
  }

  public function getUrl(): string
  {
    return $this->url;
  }

  public function setWrapperSelector(string $wrapperSelector): void
  {
    $this->wrapperSelector = $wrapperSelector;
  }

  public function getWrapperSelector(): string
  {
    return $this->wrapperSelector;
  }

  public function setTitleSelector(string $titleSelector): void
  {
    $this->titleSelector = $titleSelector;
  }

  public function getTitleSelector(): string
  {
    return $this->titleSelector;
  }

  public function setDescriptionSelector(string $descSelector): void
  {
    $this->descriptionSelector = $descSelector;
  }

  public function getDescriptionSelector(): string
  {
    return $this->descriptionSelector;
  }

  public function setImageSelector(string $imageSelector): void
  {
    $this->imageSelector = $imageSelector;
  }

  public function getImageSelector(): string
  {
    return $this->imageSelector;
  }
}
