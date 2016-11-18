<?php
namespace AppBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use AppBundle\Entity\CorrectUserDatabase;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AddUserFormType extends AbstractType{

	public function buildForm (FormBuilderInterface $builder, array $option){

		$builder->add('username', TextType::class, array(
				'label' => 'Username'
		))
		->add('password', RepeatedType::class, array(
				'type' => PasswordType::class,
				'first_options'  => array('label' => 'Password'),
				'second_options' => array('label' => 'Repeat Password'),
		))
		->add('role', ChoiceType::class, array(
				'choices' => array(
						'user' => 'ROLE_USER',
						'admin' => 'ROLE_ADMIN'
				)
		))
		->add('add', SubmitType::class);
	}

	public function configureOptions (OptionsResolver $resolver){

		$resolver->setDefaults(array(
				'data_class' => 'AppBundle\Entity\CorrectUserDatabase'
		));
	}

	public function getName()
	{
		return 'addUser';
	}
}