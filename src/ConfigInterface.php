<?php
/**
 *
 * @copyright  (c) Patrick Teague
 * @link       https://github.com/pteague/useless-pdo-wrapper/
 * @date       2014-11-13
 * @license    For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * @package    useless/pdo-wrapper
 */


/**
 * Part of useless/pdo-wrapper
 *
 * @category useless/pdo-wrapper
 */
namespace Useless\Pdo;

/**
 *
 * @package    Useless\Pdo
 */
interface ConfigInterface
{
	public function __toString();
	
	public function getOptions();
	
	public function getPassword();

	public function getAttributes();
	
	public function getUsername();
}

