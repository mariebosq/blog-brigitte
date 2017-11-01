<?php

namespace AdminBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\AbstractType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AdminBundle\Entity\User;


class MessageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('recipient', 'FOS\UserBundle\Form\Type\UsernameFormType');

    }
}
