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
 * Part of useless-pdo-wrapper
 *
 * @category useless-pdo-wrapper
 */
namespace Useless\Pdo;

/**
 *
 * @package    Useless\Pdo
 */
class DebugTest
	extends \PHPUnit_Framework_TestCase
{
	public function testGettersAndSetters()
	{
		$sql = 'SELECT * FROM test';
		$params = array(
			'foo' => 'bar',
		);
		$questionMarks = true;
		$debug = new Debug( $sql, $params, $questionMarks );
		$this->assertEquals( $sql, $debug->getSql() );
		$this->assertEquals( $params, $debug->getParameters() );
		$this->assertEquals( $questionMarks, $debug->getQueryUsesQuestionMarks() );
	}

	public function testDebugConvertSql()
	{
		$sql = 'SELECT * FROM table WHERE field0 = :shortAndLong AND field1 = :short OR field2 = :short';
		$params = array(
			'short' => 'foo',
			'shortAndLong' => 'bar',
		);
		$debug = new Debug( $sql, $params, false );
		$actual = $debug->getDebug();
		$expect = 'SELECT * FROM table WHERE field0 = \'bar\' AND field1 = \'foo\' OR field2 = \'foo\'';
		$this->assertEquals( $expect, $actual );
	}

	public function testDebugConvertSqlQuestionMarks()
	{
		$sql = 'SELECT * FROM table WHERE field0 = ? AND field1 = ? OR field2 = ?';
		$params = array(
			'short' => 'foo',
			'shortAndLong' => 'bar',
			'baz' => 'baz',
		);
		$debug = new Debug( $sql, $params, true );
		$actual = $debug->getDebug();
		$expect = 'SELECT * FROM table WHERE field0 = \'foo\' AND field1 = \'bar\' OR field2 = \'baz\'';
		$this->assertEquals( $expect, $actual );
	}

	public function testDebugConvertSqlNoParams()
	{
		$sql = 'SELECT * FROM table';
		$params = array();
		$debug = new Debug( $sql, $params, false );
		$actual = $debug->getDebug();
		$expect = 'SELECT * FROM table';
		$this->assertEquals( $expect, $actual );
	}

}

