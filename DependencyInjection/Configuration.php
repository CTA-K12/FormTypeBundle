<?php
/**
 * Configuration.php file
 *
 * File that contains the form type configuration interface class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/FormTypeBundle/DependencyInjection/Configuration.php
 * @package    Mesd\FormTypeBundle
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    0.1.0
 */
namespace Mesd\FormTypeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration Class
 *
 * This is the class that validates and merges configuration from your app/config files
 *
 * @package    Mesd\FormTypeBundle\DependencyInjection
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @since      0.1.0
 * @see        http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('mesd_form_type');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        
        /*
        $rootNode
            ->children()
                ->
            ;
        */
       
        /*
        Configuration Options
            toolbar:
                group_name_1: [item_1, item_2, item_3]
                group_name_2: [item_4, item_5, item_6]
            allowed_content:     false (default)
            extra_allowed_content: 'dl dt dd'
            disallowed_content:    'img(border*)'
            styles:
                my_styleset:
                    my_style_1:
                        element: 'h1'
                        styles:
                        attributes:
            file_browser:
                browse_url:       'url_goes_here'
                upload_url:       'url_goes_here'
                image_browse_url: 'url_goes_here'
                image_upload_url: 'url_goes_here'
                window:
                    width:  '640'
                    height: '480'
         */

        return $treeBuilder;
    }
}
