<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\SpecialRegime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpecialRegimeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', TextType::class, [
                'label' => 'Type de régime spécial',
                'required' => true,
                'attr' => [
                    'class' => 'form-control mb-3',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SpecialRegime::class,
        ]);
    }
}
