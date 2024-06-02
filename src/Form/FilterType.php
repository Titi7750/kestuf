<?php

namespace App\Form;

use App\Entity\Ambiance;
use App\Entity\Category;
use App\Entity\SpecialRegime;
use App\Model\SearchData;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('latitude', NumberType::class, [
            //     'required' => false,
            // ])
            // ->add('longitude', NumberType::class, [
            //     'required' => false,
            // ])
            // ->add('address', RangeType::class, [
            //     'label' => 'Adresse',
            //     'required' => false,
            //     'mapped' => false,
            // ])
            ->add('price', ChoiceType::class, [
                'label' => 'Prix',
                'choices' => [
                    '€' => '€',
                    '€€' => '€€',
                    '€€€' => '€€€',
                ],
                'expanded' => true,
                'multiple' => true,
                'required' => false,
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'type',
                'label' => 'Type de lieu',
                'expanded' => true,
                'multiple' => true,
                'required' => false,
            ])
            ->add('ambiance_event', EntityType::class, [
                'class' => Ambiance::class,
                'choice_label' => 'type',
                'label' => 'Ambiance',
                'expanded' => true,
                'multiple' => true,
                'required' => false,
            ])
            ->add('specialRegime_event', EntityType::class, [
                'class' => SpecialRegime::class,
                'choice_label' => 'type',
                'label' => 'Régime spécial',
                'expanded' => true,
                'multiple' => true,
                'required' => false,
            ]);
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
