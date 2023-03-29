<?php

namespace App\Form;

use App\Entity\AC;
use App\Entity\Projets;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\ACRepository;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProjetAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, ['label' => false])
            ->add('description', TextareaType::class, ['label' => false])
            ->add('image', FileType::class, [
                'label' => false, 
                'attr' => [
                    'id' => 'name-field',
                ],
            ])
            ->add('tag', TextType::class, [
                'label' => false,
                // 'attr' => [
                //     'class' => 'custom-file-button',
                // ],
            ])
            ->add('date_publi', DateType::class, ['years' => range(2000, date("Y")), 'label' => false])
            ->add('idAc', EntityType::class, [
                'class' => AC::class,
                'label' => false,
                'multiple' => true,
                'expanded' => true,
                'query_builder' => fn (ACRepository $acRepository) =>
                $acRepository->allAc()
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projets::class,
        ]);
    }
}
