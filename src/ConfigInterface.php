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
	/**
	 * Returns an array of key/value pairs of PDO attributes to be applied after instantiation.
	 *
	 * @return array
	 */
	public function getAttributes();

	/**
	 * Returns a driver specific Data Source Name.
	 *
	 * @returns string
	 * @throws \Useless\Pdo\Config\Exception\InvalidFilePermissions
	 * @throws \Useless\Pdo\Config\Exception\InvalidHost
	 */
	public function getDsn();

	/**
	 * Returns an array of key/value pairs of driver options to be applied during instantiation
	 *
	 * @return array
	 */
	public function getOptions();

	/**
	 * Returns the set password string for the PDO connection
	 *
	 * @return string
	 */
	public function getPassword();

	/**
	 * Returns the set username for the PDO connection
	 *
	 * @return string
	 */
	public function getUsername();
}

