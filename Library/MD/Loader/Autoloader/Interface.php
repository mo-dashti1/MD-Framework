<?php
/**
 * MD Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.MD.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@MD.com so we can send you a copy immediately.
 *
 * @category   MD
 * @package    MD_Loader
 * @subpackage Autoloader
 * @copyright  Copyright (c) 2005-2010 MD Technologies USA Inc. (http://www.MD.com)
 * @version    $Id: Interface.php 22913 2010-08-29 00:28:02Z ramon $
 * @license    http://framework.MD.com/license/new-bsd     New BSD License
 */

/**
 * Autoloader interface
 *
 * @package    MD_Loader
 * @subpackage Autoloader
 * @copyright  Copyright (c) 2005-2010 MD Technologies USA Inc. (http://www.MD.com)
 * @license    http://framework.MD.com/license/new-bsd     New BSD License
 */
interface MD_Loader_Autoloader_Interface
{
    /**
     * Autoload a class
     *
     * @abstract
     * @param   string $class
     * @return  mixed
     *          False [if unable to load $class]
     *          get_class($class) [if $class is successfully loaded]
     */
    public function autoload($class);
}
