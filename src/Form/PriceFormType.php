<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PriceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('Montant', TextType::class, [
            'label_attr' => ['style'=>'padding:1%'],
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez entrer un montant',
                ]),
                new Regex([
                    'pattern' => '/^\d+$/',
                    'message' => 'Veuillez entrer un montant valide',
                ]),
                new GreaterThan([
                    'value' => 0,
                    'message' => 'Veuillez entrer un montant positif',
                ]),
            ],
        ]);
    }

}