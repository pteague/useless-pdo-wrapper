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
namespace Useless\Pdo\Config;

use Useless\Pdo\Config\Exception\UnknownDriver;

/**
 *
 * @package    Useless\Pdo\Config
 */
class Factory
{
	const CONFIG_MYSQL = 'mysql';
	const CONFIG_SQLITE = 'sqlite';
	const CONFIG_SQLITE_2 = 'sqlite2';
	const CONFIG_SQLITE_3 = self::CONFIG_SQLITE;
	
	const DRIVER = 'driver';
	
	protected $classMap = array(
		self::CONFIG_MYSQL => 'Useless\\Pdo\\Config\\Mysql',
		self::CONFIG_SQLITE => 'Useless\\Pdo\\Config\\Sqlite',
		self::CONFIG_SQLITE_2 => 'Useless\\Pdo\\Config\\Sqlite2',
	);

	/**
	 * @param array $config
	 * @return \Useless\Pdo\ConfigInterface
	 * @throws \Useless\Pdo\Config\Exception\UnknownDriver
	 */
	public function getConfig( array $config )
	{
		$rv = null;
		if ( isset( $config[ static::DRIVER ] ) ) {
			switch ( $config[ static::DRIVER ] ) {
				case static::CONFIG_MYSQL:
					$class = $this->classMap[ static::CONFIG_MYSQL ];
					$rv = new $class( $config );
					break;
				case static::CONFIG_SQLITE_2:
					$class = $this->classMap[ static::CONFIG_SQLITE_2 ];
					$rv = new $class( $config );
					break;
				case static::CONFIG_SQLITE:
				case static::CONFIG_SQLITE_3:
					$class = $this->classMap[ static::CONFIG_SQLITE ];
					$rv = new $class( $config );
					break;
				default:
					throw new UnknownDriver();
			}
		}
		return $rv;
	}
}

