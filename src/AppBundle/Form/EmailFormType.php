<?php
namespace AppBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class EmailFormType extends AbstractType{

	public function buildForm (FormBuilderInterface $builder, array $option){

		$builder->add('email', EmailType::class, array(
				'label' => 'Recipient\'s e-mail'
		))
		->add('message', TextareaType::class, array(
				'label' => 'Message',
		))
		->add('send', SubmitType::class);
	}

	public function configureOptions (OptionsResolver $resolver){

		$resolver->setDefaults(array(
				'data_class' => 'AppBundle\Entity\Email'
		));
	}

	public function getName()
	{
		return 'send_email';
	}
}