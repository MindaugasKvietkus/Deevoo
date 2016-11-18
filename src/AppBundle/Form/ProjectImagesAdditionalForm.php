<?php
namespace AppBundle\Form;

use Symfony\Component\Form\Button;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ProjectImagesAdditionalForm extends AbstractType{

    public function buildForm (FormBuilderInterface $builder, array $option)
    {

        $builder->add('project_name', TextType::class)
            ->add('images_name', CollectionType::class, array(
                    'entry_type' => TextType::class,
                    'allow_add' => TRUE,
                    'allow_delete' => TRUE,
                    'entry_options' => array(
                        'label' => FALSE
                    )
                ))
            ->add('images_path', CollectionType::class, array(
                'entry_type' => TextType::class,
                'allow_add' => TRUE,
                'allow_delete' => TRUE,
                'entry_options' => array(
                    'label' => FALSE
                )
            ))
            ->add('images_id', CollectionType::class, array(
                'entry_type' => TextType::class,
                'allow_add' => TRUE,
                'allow_delete' => TRUE,
                'entry_options' => array(
                    'label' => FALSE
                )
            ))
            ->add('delete', CollectionType::class, array(
                'entry_type' => SubmitType::class,
                'entry_options' => array(
                    'label' => FALSE
                )
            ))
            ->add('update', ButtonType::class)
            ->add('add_file', FileType::class, array(
                'label' => 'File',
                'multiple' => TRUE
            ))
            ->add('add', ButtonType::class);
    }

    public function configureOptions (OptionsResolver $resolver){

        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ProjectImagesAdditionalVariables'
        ));
    }

    public function getName()
    {
        return 'add_image';
    }
}

/**
 * Created by PhpStorm.
 * User: Mariukas
 * Date: 2016.11.10
 * Time: 13:17
 */