<?php
/**
 * Created by PhpStorm.
 * User: anelos
 * Date: 06/10/17
 * Time: 12:13
 */

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseRegistrationFormType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudonym')
            ->remove("username");
    }

    public function getParent()
    {
        return BaseRegistrationFormType::class;
    }

}