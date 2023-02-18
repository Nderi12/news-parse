<?php 

declare(strict_types=1);

namespace App\MessageHandler;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

use App\PageScraper;
use App\Entity\SourceUrl;
use App\Repository\PostRepository;
use App\Repository\SourceUrlRepository;
use App\Message\SourceUrlMessage;


class SourceUrlMessageHandler implements MessageHandlerInterface
{
  private PageScraper $scraper;

  public function __construct(ValidatorInterface $validator, PostRepository $postRepo, SourceUrlRepository $sourceRepo, PageScraper $scraper)
  {
  	$this->validator = $validator;
  	$this->postRepo = $postRepo;
  	$this->sourceRepo = $sourceRepo;
    $this->scraper = $scraper;
  }

  public function __invoke(SourceUrlMessage $message)
  {
  	$src = json_decode($message->getSource(), true);
  	$sourceRepo = $this->sourceRepo;

  	extract($src);

    $source = $sourceRepo->findOneByUrl($url);

    if(!$source) {
      $source = new SourceUrl();
      $source->setUrl($url);
      $source->setWrapperSelector($wrapperSelector);
      $source->setTitleSelector($titleSelector);
      $source->setDescriptionSelector($descriptionSelector);
      $source->setImageSelector($imageSelector);

      $sourceRepo->add($source, true);
    }

    $sourceId = $source->getId();

    $this->process($source);
  }

  private function process(SourceUrl $source)
  {
    try {
      $posts = $this->scraper->scrape($source, $this->postRepo, $this->validator);

      return $this->json($posts->toArray());
    } catch(\Error $e) {
      //(string) $e;
    }
  }
}
