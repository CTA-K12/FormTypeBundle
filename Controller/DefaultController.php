<?php
/**
 * DefaultController.php file
 *
 * File that contains the form type default controller class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/FormTypeBundle/Controller/DefaultController.php
 * @package    Mesd\FormTypeBundle
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    0.1.0
 * @deprecated This file is no longer used and will be removed in subsequent versions
 */
namespace Mesd\FormTypeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Default Controller
 *
 * Default controller, this controller does nothing
 *
 * @package    Mesd\FormTypeBundle
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @since      0.1.0
 * @deprecated This class is no longer used and will be removed in subsequent versions
 */
class DefaultController extends Controller
{
	/**
     * Index Action
     *
     * The default action
     *
     * @since  0.1.0
     * @param  string     $name Any string argument
     * @return Controller $this A twig output
     */
    public function indexAction($name)
    {
        return $this->render('MesdFormTypeBundle:Default:index.html.twig', array('name' => $name));
    }
}
