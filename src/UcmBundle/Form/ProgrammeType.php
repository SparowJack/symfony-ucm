<?php

namespace UcmBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProgrammeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('libelle')
                ->add('class')
                ->add('class', ChoiceType::class, array(
                                                        'choices'  => array(
                                                            'grand_titre' => 'grand_titre',
                                                            'semestre' => 'semestre',
                                                            'titre_volume' => 'titre_volume',
                                                            'interne_titre_parent ' => 'interne_titre_parent ',
                                                            'label' => 'label'                                                          
                                                        ),))
                ->add('volume')
                ->add('credit')
                ->add('description',TextareaType::class)
                ->add('semestre')
                ->add('sequence')
                ->add('formation');
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UcmBundle\Entity\Programme'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ucmbundle_programme';
    }


}
