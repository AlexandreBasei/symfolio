<?php

namespace App\Form;

use App\Entity\AC;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AcType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('competence', ChoiceType::class, [
                'label' => 'Niveau',
                'placeholder' => 'Sélectionnez votre niveau (année)',
                'choices' => [
                    'Comprendre' => 'Comprendre',
                    'Concevoir' => 'Concevoir',
                    'Exprimer' => 'Exprimer',
                    'Développer' => 'Développer',
                    'Entreprendre' => 'Entreprendre'
                ]
            ])
            ->add('niveau', IntegerType::class, ['label' => 'Niveau :  '])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AC::class,
        ]);
    }
}
