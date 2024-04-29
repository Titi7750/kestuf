<?php

namespace App\Form;

use App\Entity\Ambiance;
use App\Entity\Category;
use App\Entity\SpecialRegime;
use App\Model\SearchData;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address', RangeType::class, [
                'label' => 'Adresse',
                'required' => false,
                'mapped' => false,
            ])
            ->add('price', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    '€' => '€',
                    '€€' => '€€',
                    '€€€' => '€€€',
                ],
                'expanded' => true,
                'multiple' => true,
                'mapped' => false,
                'required' => false,
            ])
            ->add('open_hours', TimeType::class, [
                'label' => false,
                'widget' => 'choice',
                'mapped' => false,
                'required' => false,
            ])
            ->add('close_hours', TimeType::class, [
                'label' => false,
                'widget' => 'choice',
                'mapped' => false,
                'required' => false,
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'type',
                'label' => false,
                'expanded' => true,
                'multiple' => true,
                'required' => false,
            ])
            ->add('ambiance_event', EntityType::class, [
                'class' => Ambiance::class,
                'choice_label' => 'type',
                'label' => false,
                'expanded' => true,
                'multiple' => true,
                'required' => false,
            ])
            ->add('specialRegime_event', EntityType::class, [
                'class' => SpecialRegime::class,
                'choice_label' => 'type',
                'label' => false,
                'expanded' => true,
                'multiple' => true,
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Search',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
