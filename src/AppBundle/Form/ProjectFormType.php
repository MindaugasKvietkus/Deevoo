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

class ProjectFormType extends AbstractType{

	public function buildForm (FormBuilderInterface $builder, array $option){

		$builder->add('projectname', TextType::class, array(
				'label' => 'Project name'
		))
		->add('file', FileType::class, array(
				'label' => 'File',
				'multiple' => TRUE
		))
        ->add('add', SubmitType::class);
	}

	public function configureOptions (OptionsResolver $resolver){

		$resolver->setDefaults(array(
				'data_class' => 'AppBundle\Entity\ProjectFromVariables'
		));
	}

	public function getName()
	{
		return 'addproject';
	}
}