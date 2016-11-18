<?php
namespace AppBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use AppBundle\Entity\LoginVariables;

class LoginFormType extends AbstractType{
	
	public function buildForm (FormBuilderInterface $builder, array $option){
		
		$builder->add('username', TextType::class, array(
				'label' => 'Username'
		))
		->add('password', PasswordType::class, array(
				'label' => 'Password'
		))
		->add('login', SubmitType::class);
	}
	
	public function configureOptions (OptionsResolver $resolver){
		
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Entity\LoginVariables'	
		));
	}
	
	public function getName()
	{
		return 'login';
	}
}