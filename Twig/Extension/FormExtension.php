<?php
namespace Mesd\FormTypeBundle\Twig\Extension;

use Twig_Extension;
//use Symfony\Bridge\Twig\Form\TwigRendererInterface;
use Symfony\Component\Form\FormView;

class FormExtension extends \Twig_Extension
{
    private $template;

    public function __construct()
    {
        $this->template = 'MesdFormTypeBundle:Twig:assets.html.twig';
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction(
                'mesd_form_type_page_js',
                array($this, 'renderPageJs'),
                array(
                    'is_safe'           => array('html'),
                    'needs_environment' => true,
                )
            ),
            new \Twig_SimpleFunction(
                'mesd_form_type_js_assets',
                array($this, 'renderJsAssets'),
                array(
                    'is_safe'           => array('html'),
                    'needs_environment' => true,
                )
            ),
            new \Twig_SimpleFunction(
                'mesd_form_type_css_assets',
                array($this, 'renderCssAssets'),
                array(
                    'is_safe'           => array('html'),
                    'needs_environment' => true,
                )
            ),
        );
    }

    public function renderPageJs(\Twig_Environment $twig, array $options = array())
    {
        $block = 'mesd_form_type_page_js';

        // I can render the template block
        $template = $twig->loadTemplate($this->template);

        return $template->renderBlock($block, array());
    }

    public function renderJsAssets(\Twig_Environment $twig, array $options = array())
    {
        $block = 'mesd_form_type_js';

        // I can render the template block
        $template = $twig->loadTemplate($this->template);

        return $template->renderBlock($block, array());
    }

    public function renderCssAssets(\Twig_Environment $twig, array $options = array())
    {
        $block = 'mesd_form_type_css';

        // I can render the template block
        $template = $twig->loadTemplate($this->template);

        return $template->renderBlock($block, array());
    }

    public function getName()
    {
        return 'mesd_form_type_form_extension_twig_extension';
    }
}
