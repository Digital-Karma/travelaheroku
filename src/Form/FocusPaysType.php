<?php

namespace App\Form;

use App\Entity\FocusPays;
use App\Form\MarkerPaysType;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class FocusPaysType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration("Titre", "Titre du Focus Pays"))
            ->add('introduction', TextType::class, $this->getConfiguration("Introduction", "Entrez une introduction pour votre Focus Pays"))
            ->add('slug', TextType::class, $this->getConfiguration("Adresse Web", "Tapez l'adresse web (automatique)", [
                'required' => false
            ]))
            ->add('imageCover', UrlType::class, $this->getConfiguration("Url de l'image principale", "Donnez l'url de l'image Principal du Focus Pays"))
            ->add('markerPays', MarkerPaysType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FocusPays::class,
        ]);
    }
}
