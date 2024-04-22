<?php

namespace App\Form;

use App\Entity\Ambiance;
use App\Entity\Category;
use App\Entity\Event;
use App\Entity\SpecialRegime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
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
                        ['message' => 'Remember to enter a name']
                    ),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Sorry, the name must be at least {{ limit }} characters long',
                        'max' => 50,
                    ])
                ],
                'attr' => [
                    'class' => 'form-control mb-3',
                ],
                'required' => true,
            ])

            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'constraints' => [
                    new NotBlank(
                        ['message' => 'Remember to enter an address']
                    ),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Sorry, the address must be at least {{ limit }} characters long',
                        'max' => 50,
                    ])
                ],
                'attr' => [
                    'class' => 'form-control mb-3',
                ],
            ])

            ->add('picture', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
                'attr' => [
                    'class' => 'form-control mb-3',
                ],
            ])

            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control mb-3',
                ],
            ])

            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'type',
                'label' => 'Catégorie',
                'placeholder' => 'Choisir une catégorie',
                'constraints' => [
                    new NotBlank(
                        ['message' => 'Remember to choose a category']
                    ),
                ],
                'attr' => [
                    'class' => 'form-control mb-3',
                ],
                'required' => true,
            ])

            ->add('ambiance', EntityType::class, [
                'class' => Ambiance::class,
                'choice_label' => 'type',
                'label' => 'Ambiance',
                'placeholder' => 'Choisir une ambiance',
                'mapped' => false,
                'constraints' => [
                    new NotBlank(
                        ['message' => 'Remember to choose an ambiance']
                    ),
                ],
                'attr' => [
                    'class' => 'form-control mb-3',
                ]
            ])

            ->add('specialRegime', EntityType::class, [
                'class' => SpecialRegime::class,
                'choice_label' => 'type',
                'label' => 'Régime spécial',
                'placeholder' => 'Choisir un régime spécial',
                'mapped' => false,
                'constraints' => [
                    new NotBlank(
                        ['message' => 'Remember to choose a special regime']
                    ),
                ],
                'attr' => [
                    'class' => 'form-control mb-3',
                ]
            ])

            ->add('price', ChoiceType::class, [
                'label' => 'Prix',
                'required' => false,
                'placeholder' => 'Choisir un prix',
                'attr' => [
                    'class' => 'form-control mb-3',
                ],
                'choices' => [
                    '€' => '€',
                    '€€' => '€€',
                    '€€€' => '€€€',
                ],
            ])

            ->add('open_hours', TimeType::class, [
                'label' => 'Heure d\'ouverture',
                'widget' => 'single_text',
                'required' => false,
                'attr' => [
                    'class' => 'form-control mb-3',
                ],
            ])

            ->add('close_hours', TimeType::class, [
                'label' => 'Heure de fermeture',
                'widget' => 'single_text',
                'required' => false,
                'attr' => [
                    'class' => 'form-control mb-3',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
