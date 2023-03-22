<?php

namespace App\Form;

use App\Entity\Noter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class NoterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('commentaire', TextareaType::class, ['label' => 'Commentaire :  '])
            ->add('note', IntegerType::class, ['label' => 'Note (/20) :  '])
            ->add('idUser')
            ->add('idProjet')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Noter::class,
        ]);
    }
}
