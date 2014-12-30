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

use PDOStatement;

/**
 *
 * @package    Useless\Pdo
 */
class Statement
	extends PDOStatement
{
	protected function __construct( $dbh )
	{
		$this->dbh = $dbh;
	}

	public function debugConvertSql( array $params, $questionMarks = false )
	{
		return Wrapper::debugConvertSql(
			$this->queryString
			,$params
			,$questionMarks
		);
	}
}

