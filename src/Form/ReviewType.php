<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('star', NULL , array(
                'attr' => array('class' => 'form-control ',
                                'min' => '0',
                                'max' => '5'    )))
            ->add('description', NULL , array(
                'attr' => array('class' => 'form-control ')))
            ->add('user', NULL , array(
                'attr' => array('class' => 'form-control ')))
            ->add('product', NULL , array(
                'attr' => array('class' => 'form-control ')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
