<?php

namespace App\Form;

use App\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, ['label' => 'Prénom'])
            ->add('lastName', TextType::class, ['label' => 'Nom'])
            ->add('birthDate', BirthdayType::class, ['label' => 'Né'])
            ->add('country', CountryType::class, ['label' => 'Pays', 'preferred_choices' => ['FR', 'GB']])
            ->add('specialOffer', CheckboxType::class,[
                'label' => "Prix réduit",
                'required' => false,
                'help' => "Il sera nécessaire de présenter la carte d’étudiant, militaire ou équivalent lors de l’entrée pour prouver le tarif réduit"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }

    private function addFlash(string $string, string $string1)
    {
    }
}
