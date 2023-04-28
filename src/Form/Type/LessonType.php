<?php

namespace App\Form\Type;

use App\DTO\LessonDTO;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LessonType extends BaseTaskType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder->add(
            'tasks', CollectionType::class, [
                'entry_type' => BaseTaskType::class,
                'entry_options' => ['label' => false],
            ]
        );
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults([
            'data_class' => LessonDTO::class,
            'empty_data' => new LessonDTO(),
        ]);
    }
}
