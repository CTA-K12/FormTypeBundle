<?php
namespace Mesd\FormTypeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

use Mesd\FormTypeBundle\Form\DataTransformer\DateStringToDateStringTransformer as DateTransformer;
use Mesd\FormTypeBundle\Form\DataTransformer\TimeStringToTimeStringTransformer as TimeTransformer;
use Mesd\FormTypeBundle\Form\DataTransformer\DateTimeStringToDateTimeStringTransformer as DateTimeTransformer;

class DateTimePickerType extends AbstractType
{
    protected $options;

    public function __construct(array $options = array())
    {
        $this->options = $options;
    }

    /**
     * Set default options
     *
     * Set default options for form type extension
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'format' => 'MM/DD/YYYY hh:mm A',
                'icons'  => array(
                    'time'     => 'fa fa-clock-o',
                    'date'     => 'fa fa-calendar-o',
                    'up'       => 'fa fa-chevron-up',
                    'down'     => 'fa fa-chevron-down',
                    'previous' => 'fa fa-chevron-left',
                    'next'     => 'fa fa-chevron-right',
                    'today'    => 'fa fa-crosshairs',
                    'clear'    => 'fa fa-ban',
                    'close'    => 'fa fa-times',
                ),
            ))
            ->setOptional(array(
                'linked_with',
                'on_change',
            ))
        ;
    }

    /**
     * Build view
     *
     * Build form view object
     *
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $opts = array_merge($this->options, $options);

        $view->vars = array_replace($view->vars, $opts);

        //$b = $form->getBuilder();
        //echo '<pre>';var_dump(get_class_methods($form));exit;
        //if (array_key_exists('js_picker_enabled', $options))
        //{
        //    $view->vars['js_picker_enabled'] = $options['js_picker_enabled'];
        //}
        //else
        //{
        //    $view->vars['js_picker_enabled'] = false;
        //}
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ('MM/DD/YYYY hh:mm A' == $options['format'])
        {
            $builder->addModelTransformer(new DateTimeTransformer());
        }
        elseif ('MM/DD/YYYY' == $options['format'])
        {
            $builder->addModelTransformer(new DateTransformer());
        }
        elseif ('hh:mm A' == $options['format'])
        {
            $builder->addModelTransformer(new TimeTransformer());
        }
    }

    /**
     * Get parent type
     *
     * Returns the name of the parent type
     *
     * @return string The name of the parent type
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * Get name
     *
     * Returns the name of the type
     *
     * @return string The name of the type
     */
    public function getName()
    {
        return 'mesd_form_type_datetime_picker';
    }
}
