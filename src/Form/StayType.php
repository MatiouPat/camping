<?php

namespace App\Form;

use App\Entity\Entry;
use App\Entity\Stay;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GroupSequence;

class StayType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('arrived_at', DateType::class,[
                'format' => 'dd/MM/yyyy',
                'widget' => 'single_text'
            ])
            ->add('leaved_at',DateType::class,[
                'format' => 'dd/MM/yyyy',
                'widget' => 'single_text'
            ])
            ->add('spot', ChoiceType::class,[
                'empty_data' => null,
                'choices' => $this->getSpots(),
                'required' => false
            ])
            ->add('adult')
            ->add('teenager')
            ->add('child')
            ->add('car')
            ->add('motor_bike')
            ->add('bike')
            ->add('camping_car')
            ->add('caravan')
            ->add('tent')
            ->add('wooden_caravan')
            ->add('registration')
            ->add('electricity')
            ->add('pet')
            ->add('entry', EntityType::class,[
                'class' => Entry::class,
                'choice_label' => 'name',
                'expanded' => true,
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Stay::class,
            'translation_domain' => 'forms'
        ]);
    }

    /**
     * Retourne la liste de tout les emplacements
     * @return array
     */
    private function getSpots() : array
    {
        $choices = Stay::SPOTS;
        $r = [];
        foreach ($choices as $k => $v){
            $r[$v] = $v;
        }
        return $r;
    }
}
