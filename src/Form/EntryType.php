<?php

namespace App\Form;

use App\Entity\Entry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('surname')
            ->add('gender', ChoiceType::class,[
                'choices' => $this->getGender()
            ])
            ->add('address')
            ->add('nationality', ChoiceType::class,[
                'choices' => $this->getNationality()
            ])
            ->add('postal_code')
            ->add('city')
            ->add('home_phone')
            ->add('phone')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Entry::class,
            'translation_domain' => 'forms'
        ]);
    }

    public function getGender() : array
    {
        $choices = Entry::GENDERS;
        $r = [];
        foreach ($choices as $k => $v){
            $r[$v] = $k;
        }
        return $r;
    }

    public function getNationality() : array
    {
        $choices = Entry::NATIONALITY;
        $r = [];
        foreach ($choices as $k => $v){
            $r[$v] = $k;
        }
        return $r;
    }
}
