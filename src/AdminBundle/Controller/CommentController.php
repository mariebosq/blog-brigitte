<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AdminBundle\Entity\Comment;
use Symfony\Component\HttpFoundation\Request;
use AdminBundle\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

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

  public function showAction($id)
  {
      $repository = $this->getDoctrine()->getRepository('AdminBundle:Comment');

      $comment = $repository->find($id);

      return $this->render('AdminBundle:Comment:comment.html.twig', array('comment' => $comment));
  }

  public function deleteAction($id)
  {
      $em = $this->getDoctrine()->getEntityManager();
      $comment = $em->getRepository('AdminBundle:Comment')->find($id);

      $em->remove($comment);
      $em->flush();

      return $this->redirectToRoute('admin_index_comment');
  }

  public function publishAction($id, Request $request)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $comment = $em->getRepository('AdminBundle:Comment')->find($id);

    $now = new \DateTime();
    $comment->setPublishedAt($now);

    $em->flush();

    return $this->redirectToRoute('admin_index_comment');
  }

  public function depublishAction($id, Request $request)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $comment= $em->getRepository('AdminBundle:Comment')->find($id);

    $comment->setPublishedAt(NULL);

    $em->flush();

    return $this->redirectToRoute('admin_index_comment');
  }

  public function countAction()
  {
    $repository = $this
      ->getDoctrine()
      ->getManager();

    $query = "
      SELECT *
      FROM comments
      WHERE published_at IS NULL
    ";

    $stmt = $repository->getConnection()->prepare($query);
    $stmt->execute();
    $comments = $stmt->fetchAll();

    return new JsonResponse(count($comments));
  }
}
