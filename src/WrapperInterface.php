<?php
/**
 *
 * @copyright  (c) Patrick Teague
 * @link       https://github.com/pteague/useless-pdo-wrapper/
 * @date       2014-11-17
 * @license    For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * @package    useless/pdo-wrapper
 */


/**
 * Part of useless/pdo-wrapper
 *
 * @category useless/pdo-wrapper
 */
namespace Useless\Pdo;

use PDO;
use Useless\Pdo\ConfigInterface;

/**
 *
 * @package    Useless\Pdo
 */
interface WrapperInterface
{
	public function getAttributes();

	public function getDsn();

	public function getOptions();

	public function getPassword();

	public function getUsername();

	public function setAttribute( $attribute, $value );

	public function setConfig( ConfigInterface $config );

	public function setDsn( $dsn );

	public function setOption( $attribute, $value );

	public function setPassword( $password );

	public function setUsername( $username );
}




