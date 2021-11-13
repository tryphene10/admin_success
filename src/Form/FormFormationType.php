<?php

namespace App\Form;

use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class FormFormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFile', VichFileType::class, [
                'label'=>'Image (PNG or JPEG)',
                'required'=>false,
                'allow_delete'=>true,
                // 'download_uri'=>false,
                // 'constraints'=> [
                //     new Image(['maxSize'=>'50k'])
                // ]

            ])
            ->add('name')
            ->add('price')
            ->add('temps')
            ->add('status')
            ->add('description')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
