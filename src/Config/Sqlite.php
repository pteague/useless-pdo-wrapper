<?php
/**
 * Short description for file
 *
 * Long description for file (if any)...
 *
 * @copyright  (c) Patrick Teague <plteague@gmail.com>
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

use PDO;
use Useless\Pdo\Config\Exception\InvalidHost;
use Useless\Pdo\Config\Exception\InvalidFilePermissions;

/**
 * Short description for class
 *
 * Long description for class (if any)...
 *
 * @package    Useless\Pdo\Config
 */
class Sqlite
	extends AbstractConfig
{
	protected $defaultDriver = 'sqlite';
	
	public function __construct( array $config = array(), array $map = array() )
	{
		parent::__construct( $config, $map );
		$this->defaultDriver = $this->getDriver();
	}

	/**
	 * @return string
	 * @throws InvalidFilePermissions
	 * @throws InvalidHost
	 */
	public function getDsn()
	{
		$dsn = $this->getDriver();
		$host = null;
		if ( ( $socket = $this->getUnixSocket() ) ) {
			if ( static::SQLITE_MEMORY_TABLE == $socket ) {
				$host = $socket;
			}
			elseif ( is_dir( $socket ) ) {
				if ( is_writable( $socket ) ) {
					$dbname = $this->getDatabaseName();
					$host = $socket . DIRECTORY_SEPARATOR . $dbname;
				}
				else {
					throw new InvalidFilePermissions();
				}
			}
			else {
				$dir = dirname( $socket );
				if ( is_dir( $dir ) && is_writable( $dir ) ) {
					$host = $socket;
				}
				else {
					throw new InvalidFilePermissions();
				}
			}
		}
		if ( $host ) {
			$dsn .= ':' . $host;
		}
		else {
			throw new InvalidHost();
		}
		if ( ( $charset = $this->getCharset() ) ) {
			$dsn .= ';charset=' . $charset;
		}

		return $dsn;
	}

	public function init()
	{
		$this->options = array(
		);
		$this->config = array(
			'driver' => $this->defaultDriver,
		);
		$this->attributes = array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		);
	}

}

