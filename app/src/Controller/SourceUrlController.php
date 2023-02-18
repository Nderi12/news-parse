<?php 

declare(strict_types=1);

namespace App\Controller;


use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\PageScraper;
use App\Entity\SourceUrl;
use App\Form\Type\SourceUrlType;
use App\Repository\PostRepository;
use App\Repository\SourceUrlRepository;

class SourceUrlController extends AbstractController
{
  private PageScraper $scraper;

  public function __construct(PageScraper $scraper)
  {
    $this->scraper = $scraper;
  }

  /**
   * Add a new news source to the database.
   * Used via a web interface to display a form 
   * for adding a new news source to the database.
   * We can then crawl and parse each added news source 
   * by triggering an event either from the command line, a web UI or any other means.
   * 
   * @Route("/news-source/new", name="new_news_form", methods={"GET", "POST"})
   * 
   */
  public function new(Request $request, SourceUrlRepository $sourceRepo): Response 
  {
    $this->denyAccessUnlessGranted(
      'ROLE_MODERATOR', 
      null, 
      'You need to be logged in with MODERATOR access to perform this operation'
    );

    $source = new SourceUrl();
    $form = $this->createForm(SourceUrlType::class, $source);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) { 
      $source = $form->getData();
      $sourceRepo->add($source, true);

      $sourceId = $source->getId();

      return $this->redirectToRoute('all_sources');
    }

    return $this->renderForm('sources/new.html.twig', [
      'form' => $form,
    ]);
  }

  /**
   * @Route("/news-source/detail/{id}", name="source_detail")
   */
  public function source_detail(SourceUrl $source)
  {
    return $this->render('sources/detail.html.twig', ['source' => $source]);
  }

  /**
   * @Route("/news-source/all", name="all_sources")
   */
  public function sources(SourceUrlRepository $sourceRepo) 
  {
    $sources = $sourceRepo->findAll();

    return $this->render('sources/list.html.twig', ['sources' => $sources]);
  }


  /**
   * @Route("/news-source/process/{id}", name="process_news_source")
   * 
   * Synchronously fetch and parse the news source (identified by {id})
   */
  public function process(SourceUrl $source, PostRepository $postRepo, ValidatorInterface $validator): Response
  {
    $this->denyAccessUnlessGranted(
      'ROLE_ADMIN', 
      null, 
      'You need to be logged in with ADMIN access to perform this operation'
    );

    try {
      $posts = $this->scraper->scrape($source, $postRepo, $validator);

      return $this->redirectToRoute('posts_list');
      //return $this->json($posts->toArray());
    } catch(\Error $e) {
      return new Response((string) $e, 400);
    }
  }
}
