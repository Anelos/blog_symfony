<?php

namespace AppBundle\Form;

use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('tempTags', TextType::class, array('label' => 'Tags'))
            ->add('content', FroalaEditorType::class, array(
                'toolbarButtons' => array(
                    'fullscreen',
                    'bold',
                    'italic',
                    'underline',
                    'strikeThrough',
                    'subscript',
                    'superscript',
                    '|',
                    'fontFamily',
                    'fontSize',
                    'color',
                    'paragraphStyle',
                    '|',
                    'paragraphFormat',
                    'align',
                    'formatOL',
                    'formatUL',
                    'outdent',
                    'indent',
                    'quote',
                    '-',
                    'insertLink',
                    'insertImage',
                    'insertVideo',
                    'embedly',
                    'insertTable',
                    '|',
                    'emoticons',
                    'specialCharacters',
                    'insertHR',
                    'clearFormatting',
                    '|',
                    'undo',
                    'redo',
                    '|',
                    'html',
                )
            ))
            ->add('published');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Article'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_article';
    }


}
