<?php

namespace App\Form;

use App\Entity\Deadgarage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeadgarageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('started_at',DateType::class,[
                'format' => 'dd/MM/yyyy',
                'widget' => 'single_text'
            ])
            ->add('stopped_at',DateType::class,[
                'format' => 'dd/MM/yyyy',
                'widget' => 'single_text'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Deadgarage::class,
            'translation_domain' => 'forms'
        ]);
    }
}
