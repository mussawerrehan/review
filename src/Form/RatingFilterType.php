<?php

namespace App\Form;

use phpDocumentor\Reflection\Types\Integer;
use PhpParser\Node\Scalar\String_;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RatingFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rating', ChoiceType::class , array(
                'choices'  => [
                    '1' => '1 Star',
                    '2' => '2 Star',
                    '3' => '3 Star',
                    '4' => '4 Star',
                    '5' => '5 Star',
                ],
                'attr' => array('class' => 'form-control ')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => String_::KIND_DOUBLE_QUOTED,
        ]);
    }
}
