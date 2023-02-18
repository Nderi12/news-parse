<?php

declare(strict_types=1);

namespace App;

use Goutte\Client as GoutteClient;
//use Symfony\Component\Panther\Client;
use Symfony\Component\DomCrawler\Crawler;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Contracts\SourceUrlInterface;


class PageScraper
{ 
  public function scrape(SourceUrlInterface $source, PostRepository $postRepo, ValidatorInterface $validator): Collection 
  {
    $collection = [];

    $client = new GoutteClient();
    //$client = Client::createChromeClient();
    $crawler = $client->request('GET', $source->getUrl());

    $crawler->filter($source->getWrapperSelector())->each(function (Crawler $node) use ($validator, $source, $postRepo, &$collection) {
      if (!$node->filter($source->getTitleSelector())->count()) {
        return;
      }

      // Parse out the various parts of the URL  using the Crawler and Parser
      $title    = trim($node->filter($source->getTitleSelector())->text());
      $desc     = trim($node->filter($source->getDescriptionSelector())->text());
      $imageUrl = trim($node->filter($source->getImageSelector())->attr('src'));
      $dateTime = new \DateTime();

      // Check if post already exists...
      $post = $postRepo->findOneByTitle($title);

      if($post) {
        $post->setLastUpdated($dateTime);
        $postRepo->update($post);
      } else {
        // ... otherwise, create a new Post with the parsed website data
        $post = new Post();

        $post->setTitle($title);
        $post->setImage($imageUrl);
        $post->setDescription($desc);
        $post->setDateAdded($dateTime);
        $post->setLastUpdated($dateTime);

        $errors = $validator->validate($post);

        if (count($errors) > 0) {
          throw new \Error($errors);
        }

        $postRepo->add($post, true);
      }

      $postId = $post->getId();
      $collection[$postId] = $post;
    });

    return new ArrayCollection($collection);
  }
}