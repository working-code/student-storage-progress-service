<?php

namespace App\Form\Type;

use App\DTO\TaskDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseTaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('title', TextType::class, [
            'label' => 'Тема',
            'attr' => ['placeholder' => 'Тема']
        ])
            ->add('content', TextType::class, [
                'label' => 'Описание'
            ]);

    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults([
            'data_class' => TaskDTO::class,
            'empty_data' => new TaskDTO(),
        ]);
    }
}
