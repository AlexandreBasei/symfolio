<?php

namespace App\Form;

use App\Entity\AC;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\ACRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class DeleteAcType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('id', EntityType::class, [
            'class' => AC::class,
            'label' => false,
            'multiple' => true,
            'placeholder' => "Nom de l'AC", 
            'attr' => [
                'ng-model' => 'id',
            ],
            // 'expanded' => true,
            'query_builder' => fn (ACRepository $acRepository) => $acRepository->allAc()
        ])
        ->setAttributes([
            'ng-submit' => 'supprAC()',
            'class' => 'test',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AC::class,
        ]);
    }
}
