<?php 

declare(strict_types=1);

namespace App\Controller;


use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\PageScraper;
use App\Entity\SourceUrl;
use App\Form\Type\SourceUrlType;
use App\Repository\PostRepository;
use App\Repository\SourceUrlRepository;

class IndexController extends AbstractController
{
  /** 
   * @Route("/", name="homepage", methods={"GET"})
   */
  public function index(): Response 
  {
    return $this->render('home.html.twig');
  }

  /** 
   * @Route("/server-info", name="server_info_page", methods={"GET"})
   */
  public function serverInfo() 
  {
    phpinfo();
  }
}
