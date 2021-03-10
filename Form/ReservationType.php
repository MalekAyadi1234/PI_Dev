<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Entity\Vol;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idR')
            ->add('cin')
            ->add('email')
            ->add('prix')
            ->add('dateV',DateType::class,[
                'data' => new \DateTime(),
            ])
            ->add('numP')
            ->add('vol',EntityType::class,[
                'class'=>Vol::class
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
