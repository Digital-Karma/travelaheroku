<?php

namespace App\Form;

use App\Entity\FocusPays;
use App\Entity\FocusVille;
use App\Form\FocusPaysType;
use App\Form\ApplicationType;
use App\Form\MarkerVilleType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class FocusVilleType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration("Titre", "Titre du Focus Ville"))
            ->add('introduction', TextType::class, $this->getConfiguration("Introduction", "Introduction du Focus Ville"))
            ->add('content', TextareaType::class, $this->getConfiguration("Description détaillée", "Taper une Description du Focus Ville"))
            ->add('slug', TextType::class, $this->getConfiguration("Adresse Web", "Tapez l'adresse web (automatique)", [
                'required' => false
            ]))
            ->add('imageCover', UrlType::class, $this->getConfiguration("Url de l'image principale", "Donnez l'url de l'image pour le focus ville."))
            ->add('focusPays', EntityType::class, [
                'class' => FocusPays::class,
                'choice_label' => 'title',
                'placeholder' => 'Cette ville ce trouve dans quel Pays'
            ])
            ->add('markerVille', MarkerVilleType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FocusVille::class,
        ]);
    }
}
