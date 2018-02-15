<?php

namespace AdminBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\AbstractType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AdminBundle\Entity\User;


class MessageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      //Création du formulaire pour se connecter à l'application
      $builder->add('recipient', 'FOS\UserBundle\Form\Type\UsernameFormType');

    }
}
