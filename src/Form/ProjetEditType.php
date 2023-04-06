<?php

namespace App\Form;

use App\Entity\AC;
use App\Entity\Projets;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\File\File;
use App\Repository\ACRepository;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Transformer\FileToUploadedFileTransformer;

class ProjetEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, ['label' => false])
            
            ->add('description', TextareaType::class, ['label' => false])

            ->add('image', FileType::class, [
                'label' => 'Image (PNG ou JPEG)',
                'required' => false,
                'mapped' => false,
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
            ]);

            if ($options['compound']) {
                $builder->get('image')->addViewTransformer(new FileToUploadedFileTransformer());
            }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projets::class,
        ]);
    }
}
