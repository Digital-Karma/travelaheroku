<?php

namespace App\Form;

use App\Entity\MarkerVille;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MarkerVilleType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration("Titre", "Titre Pour le Marker du Pays"))
            ->add('longitude', TextType::class, $this->getConfiguration("Longitude", "Longitude pour le Marker du Pays"))
            ->add('latitude', TextType::class, $this->getConfiguration("Latitude", "Latitude pour le Marker du Pays"))
            ->add('adresse', TextType::class, $this->getConfiguration("Adresse Complète", "Format : selon le pays"))
            ->add('slug', TextType::class, $this->getConfiguration("Adresse Web", "Tapez l'adresse web (automatique)", [
                'required' => false
            ]))
            // ->add('focusVille')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MarkerVille::class,
        ]);
    }
}
