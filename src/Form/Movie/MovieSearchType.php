<?php

namespace App\Form\Movie;

use App\Model\Movie\MovieSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class MovieSearchType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'constraints' => [new NotBlank()],
            ])
            ->add('year', ChoiceType::class, [
                'choices' => $this->getYears(1900),
                'required' => false,
                'choice_translation_domain' => false,
            ])
            ->add('type', ChoiceType::class, [
                'choices' => MovieSearch::getTypes(),
                'required' => false,
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'movie',
            'data_class' => MovieSearch::class,
        ]);
    }

    private function getYears(int $min, string $max='current')
    {
        $years = range($min, ($max === 'current' ? date('Y') : $max));

        return array_reverse(array_combine($years, $years), true);
    }
}