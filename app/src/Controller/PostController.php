<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\Post;
use App\Repository\PostRepository;


class PostController extends AbstractController
{
  /**
   * @Route("/posts", name="posts_list")
   */
  public function getPostsByPage(Request $request, PostRepository $repo): Response
  {
    $this->denyAccessUnlessGranted(
      'ROLE_MODERATOR', 
      null, 
      'You need to be logged in with MODERATOR access to view this resource'
    );

    $page       = $request->query->getInt('page', 1);
    $limit      = (int) $_SERVER['POSTS_PER_PAGE'] ?? 10;

    $paginator  = $repo->getPagedPosts($page, $limit);

    $iterator   = $paginator->getIterator(); # ArrayIterator
    $totalCount = count($paginator); # Total number of posts available
    $numPages   = (int) ceil($totalCount / $limit);
    $startNum   = $limit * ($page - 1) + 1; # Start number of currently displayed posts
    $endNum     = $page === $numPages ? $totalCount : $limit * $page; # End number currently displayed posts

    return $this->render('posts/list.html.twig', [ 
      'posts'      => $iterator,
      'currPage'   => $page, 
      'numPages'   => $numPages, 
      'startNum'   => $startNum, 
      'endNum'     => $endNum,
      'totalCount' => $totalCount,
      'routeName'  => 'posts_list',
    ]);
  }

  /**
   * @Route("/posts/delete/{id}", name="delete_post")
   */
  public function deletePost(Request $request, PostRepository $repo, Post $post): Response 
  {
    $this->denyAccessUnlessGranted(
      'ROLE_ADMIN', 
      null, 
      'You need to be logged in with ADMIN access to perform this operation'
    );

    $repo->remove($post, true);

    return new RedirectResponse($request->headers->get('referer'));
  }
}