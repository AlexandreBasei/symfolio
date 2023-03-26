<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Iut;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use App\Repository\IutRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, ['label' => 'E-mail :  '])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'J\'accepte les conditions d\'utilisation de Symfolio  ',
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label' => 'Mot de passe :  ',
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('iut', EntityType::class, [
                'class' => Iut::class,
                'choice_label' => 'nom',
                'label' => 'IUT :  ',
                'placeholder' => 'Sélectionnez votre IUT',
                'query_builder' => fn (IutRepository $iutRepository) =>
                $iutRepository->allIut()
            ])
            ->add('niveau', ChoiceType::class, [
                'label' => 'Niveau :  ',
                'placeholder' => 'Sélectionnez votre niveau (année)',
                'choices' => [
                    1 => 1,
                    2 => 2,
                    3 => 3
                ]
            ])
            ->add('photo', FileType::class, ['label' => 'Photo de profil :  ', 'required' => false,])
            ->add('description', TextareaType::class, ['label' => 'Décrivez-vous en quelques lignes :  ']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
