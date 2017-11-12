<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AdminBundle\Entity\CustomImage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ImageController extends Controller
{

  public function createAction(Request $request) {
    $em = $this->getDoctrine()->getEntityManager();
    $image = new CustomImage();

    $imageName = $request->request->get('imageName');
    $imageFile = $request->files->get('imageFile');

    $image->setImageName($imageName);
    $image->setImageFile($imageFile);

    $em->persist($image);
    $em->flush();

    // // Store the image.
    // try {
    //   $response = FroalaEditor_Image::upload('/web/images/');
    //   echo stripslashes(json_encode($response));
    // }
    // catch (Exception $e) {
    //   http_response_code(404);
    // }

    return new JsonResponse(array('link' => stripcslashes('/web/images/') . $imageName));
  }
}
