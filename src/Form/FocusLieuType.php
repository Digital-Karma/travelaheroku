<?php

namespace App\Form;

use App\Entity\FocusLieu;
use App\Entity\FocusVille;
use App\Form\MarkerLieuType;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class FocusLieuType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration("Titre", "Titre du Focus Lieu"))
            ->add('content', TextareaType::class, $this->getConfiguration("Description détaillée", "Taper une Description du Focus Lieu"))
            ->add('slug', TextType::class, $this->getConfiguration("Adresse Web", "Tapez l'adresse web (automatique)", [
                'required' => false
            ]))
            ->add('focusVille', EntityType::class, [
                'class' => FocusVille::class,
                'choice_label' => 'title',
                'placeholder' => 'Ce lieu ce trouve dans quel Ville'
            ])
            ->add('markerLieu', MarkerLieuType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FocusLieu::class,
        ]);
    }
}
