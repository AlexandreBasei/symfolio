<?php

namespace App\Form;

use App\Entity\AC;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AcType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('competence', ChoiceType::class, [
                'label' => 'Niveau',
                'placeholder' => 'Compétence concernée par l\'AC',
                'choices' => [
                    'Comprendre' => 'Comprendre',
                    'Concevoir' => 'Concevoir',
                    'Exprimer' => 'Exprimer',
                    'Développer' => 'Développer',
                    'Entreprendre' => 'Entreprendre'
                ]
            ])
            ->add('niveau', ChoiceType::class, [
                'label' => 'Niveau :  ',
                'placeholder' => 'Niveau de l\'AC',
                'choices' => [
                    1 => 1,
                    2 => 2,
                    3 => 3
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AC::class,
        ]);
    }
}
