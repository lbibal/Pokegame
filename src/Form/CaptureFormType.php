<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CaptureFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lieu', ChoiceType::class, [
                'label' => 'Lieu',
                'choices' => [
                    'Montagne' => 'montagne',
                    'Prairie' => 'prairie',
                    'Ville' => 'ville',
                    'Forêt' => 'foret',
                    'Plage' => 'plage',
                ],
                'placeholder' => 'Choisissez un lieu',
                'required' => true,
            ]);
    }
}