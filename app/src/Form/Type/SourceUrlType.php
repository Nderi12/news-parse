<?php

declare(strict_types=1);

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\FormBuilderInterface;

use App\Entity\SourceUrl;

class SourceUrlType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('url', TextType::class)
      ->add('wrapperSelector', TextType::class)
      ->add('titleSelector', TextType::class)
      ->add('descriptionSelector', TextType::class)
      ->add('imageSelector', TextType::class)
      ->add('save', SubmitType::class, ['label' => 'Save'])
    ;
  }

  /**
   * Every form needs to know the name of the class that holds the underlying data
   * So, it's generally a good idea to explicitly specify the data_class option 
   * by adding the following to your form type class.
   * 
   * Cf. https://symfony.com/doc/5.4/forms.html#creating-form-classes
   */
  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => SourceUrl::class,
    ]);
  }
}
