<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AdminBundle\Entity\Comment;
use Symfony\Component\HttpFoundation\Request;
use AdminBundle\Repository\CommentRepository;

use Symfony\Component\Debug\Debug;


class CommentController extends Controller
{
  public function indexAction(Request $request)
  {
    // On recupere la page courante
    $page = $request->query->get('page');

    // Si celle-ci n'est pas precisée (par defaut), on la met a 1
    if ($page == NULL) {
      $page = 1;
    }

    $em = $this->getDoctrine()->getManager();

    $comments = $em->getRepository('AdminBundle:Comment')
        ->findAllPagineEtTrie($page, 10);

    $pagination = array(
        'page' => $page,
        'nbPages' => ceil(count($comments) / 10),
        'nomRoute' => 'admin_index_comment',
        'paramsRoute' => array()
    );

    return $this->render('AdminBundle:Comment:index.html.twig', array(
        'listComments' => $comments,
        'pagination' => $pagination
    ));
  }
}
