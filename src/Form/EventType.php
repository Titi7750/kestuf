<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Event;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank(
                        ['message' => 'Pense à saisir un nom']
                    ),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Désolé, le nom doit être au moins {{ limit }} caractères',
                        'max' => 50,
                    ])
                ],
                'required' => true,
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'constraints' => [
                    new NotBlank(
                        ['message' => 'Pense à saisir une adresse']
                    ),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Désolé, l\'adresse doit être au moins {{ limit }} caractères',
                        'max' => 50,
                    ])
                ],
            ])
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'constraints' => [
                    new NotBlank(
                        ['message' => 'Pense à saisir un titre']
                    ),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Désolé, le titre doit être au moins {{ limit }} caractères',
                        'max' => 50,
                    ])
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'type',
                'label' => 'Catégorie',
                'placeholder' => 'Choisir une catégorie',
                'constraints' => [
                    new NotBlank(
                        ['message' => 'Pense à choisir une catégorie']
                    ),
                ],
                'required' => true,
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix',
            ])
            ->add('reduction', NumberType::class, [
                'label' => 'Réduction',
            ])
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
