<?php

namespace App\Form;

use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('visitDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Jour de visite'
            ])
            ->add('durationType', ChoiceType::class, [
                'choices' => ['Journée' => Booking::TYPE_DAY, 'Demi-journée' => Booking::TYPE_HALF_DAY],
                'label' => 'Durée de la visite'
            ])
            ->add('quantity', IntegerType::class, [
                'label' => 'Quantité des billets'])
            ->add('email', EmailType::class, [
                'label' => 'Adresse email'])
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
