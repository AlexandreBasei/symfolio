<?php

namespace App\Form;

use App\Entity\Noter;
use App\Entity\Projets;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\ProjetsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class NoterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $id = $options['data']['id'];
        $builder
            ->add('commentaire', TextareaType::class, ['label' => 'Commentaire :  '])
            ->add('note', IntegerType::class, ['label' => 'Note (/20) :  '])
            ->add('idProjet', EntityType::class, [
                    'label' => 'Projet à noter :  ',
                    'class' => Projets::class,
                    'choice_label' => 'nom',
                    'placeholder' => 'Sélectionnez le projet',
                    'query_builder' => function (ProjetsRepository $repo) use ($id) {
                        return $repo->createQueryBuilder('p')
                            ->andWhere('p.idUser = :id')
                            ->setParameter('id', $id)
                            ->orderBy('p.nom', 'ASC');
                    },
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
