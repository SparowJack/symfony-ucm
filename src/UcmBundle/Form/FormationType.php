<?php

namespace UcmBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class FormationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('titre')
                ->add('metier',TextareaType::class,array('required' => false,
                                                        'attr' => array('class' => 'tinymce','data-theme'=>'bbcode')
                                                      ))
                ->add('poursuite',TextareaType::class,array('required' => false,
                                                        'attr' => array('class' => 'tinymce','data-theme'=>'bbcode')
                                                      ))
                ->add('contact',TextareaType::class,array('required' => false,
                                                        'attr' => array('class' => 'tinymce','data-theme'=>'bbcode')
                                                      ))
                ->add('objectif',TextareaType::class,array('required' => false,
                                                        'attr' => array('class' => 'tinymce','data-theme'=>'bbcode')
                                                      ))
                ->add('admission',TextareaType::class,array('required' => false,
                                                        'attr' => array('class' => 'tinymce','data-theme'=>'bbcode')
                                                      ))
                ->add('departement')
                ->add('categformation')
                ->add('categorie');
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UcmBundle\Entity\Formation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ucmbundle_formation';
    }


}
