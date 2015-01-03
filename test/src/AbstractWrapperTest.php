<?php
namespace Useless\Pdo;

use PDO;

abstract class AbstractWrapperTest
	extends \PHPUnit_Framework_TestCase
{
	protected $pdo;

	protected $data = array(
		1 => 'Billy Joe',
		2 => 'Jim Bob',
		3 => 'Foo Bar',
	);

	public function setUp()
	{
		if ( !extension_loaded( 'pdo_sqlite' ) ) {
			$this->markTestSkipped( "Need 'pdo_sqlite' to test in memory." );
		}

		$this->pdo = $this->newPdo();
		$this->createTable();
		$this->fillTableData();
	}

	abstract protected function newPdo();

	protected function createTable()
	{
		$sql = "CREATE TABLE pdotest (
			id   INTEGER PRIMARY KEY AUTOINCREMENT,
			name VARCHAR(10) NOT NULL
		)";

		$this->pdo->exec( $sql );
	}

	protected function fillTableData()
	{
		foreach ( $this->data as $id => $name ) {
			$this->insertData( array( 'name' => $name ) );
		}
	}

	protected function insertData( array $params )
	{
		$fields = array_keys( $params );
		$sql = sprintf(
			'INSERT INTO pdotest ( `%s` ) VALUES ( :%s )'
			,implode( '`, `', $fields )
			,implode( ', :', $fields )
		);
		$stmt = $this->pdo->prepare( $sql );
		$stmt->execute( $params );
	}

	abstract public function testGetPdo();

	public function testAttributeGetterAndSetter()
	{
		$pdo = $this->newPdo();
		$this->assertFalse( $pdo->isConnected() );

		# not connected yet
		$pdo->setAttribute( PDO::ATTR_CASE, PDO::CASE_LOWER );
		$this->assertFalse( $pdo->isConnected() );

		$actual = $pdo->getAttribute( PDO::ATTR_CASE );
		$this->assertSame( PDO::CASE_LOWER, $actual );
		$this->assertTrue( $pdo->isConnected() );

		# now connected
		$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		$actual = $pdo->getAttribute( PDO::ATTR_ERRMODE );
		$this->assertSame( PDO::ERRMODE_EXCEPTION, $actual );
	}

	public function testErrorCode()
	{
		$actual = $this->pdo->errorCode();
		$expect = '00000';
		$this->assertEquals( $expect, $actual );
	}

	public function testErrorInfo()
	{
		$actual = $this->pdo->errorInfo();
		$expect = array(
			'00000',
			null,
			null,
		);
		$this->assertEquals( $expect, $actual );
	}

	public function testQuery()
	{
		$sql = 'SELECT * FROM pdotest';

		$stmt = $this->pdo->query( $sql );
		$this->assertInstanceOf( 'PDOStatement', $stmt );
		$result = $stmt->fetchAll( Wrapper::FETCH_ASSOC );
		$expect = count( $this->data );
		$actual = count( $result );
		$this->assertEquals( $expect, $actual );

		$stmt = $this->pdo->query( $sql, Wrapper::FETCH_COLUMN, 2 );
		$this->assertInstanceOf( 'PDOStatement', $stmt );
		$result = $stmt->fetchAll( Wrapper::FETCH_ASSOC );
		$expect = count( $this->data );
		$actual = count( $result );
		$this->assertEquals( $expect, $actual );

		$stmt = $this->pdo->query( $sql, Wrapper::FETCH_CLASS, 'ArrayObject', array( 'foo' => 'bar' ) );
		$this->assertInstanceOf( 'PDOStatement', $stmt );
		$result = $stmt->fetchAll( Wrapper::FETCH_ASSOC );
		$expect = count( $this->data );
		$actual = count( $result );
		$this->assertEquals( $expect, $actual );
	}

	public function testQueryException()
	{
		$sql = 'SELECT * FROM pdotest';
		$this->setExpectedException(
			'PDOException'
			,'SQLSTATE[22003]: Numeric value out of range: Invalid fetch mode specified'
			,22003
		);
		$stmt = $this->pdo->query( $sql, -1 );
	}

	public function testQuote()
	{
		# quoting a string
		$actual = $this->pdo->quote( '"foo" bar \'baz\'' );
		$this->assertEquals( '\'"foo" bar \'\'baz\'\'\'', $actual );

		# quoting an integer
		$actual = $this->pdo->quote( 123 );
		$this->assertEquals( '\'123\'', $actual );

		# quoting a float
		$actual = $this->pdo->quote( 123.456 );
		$this->assertEquals( '\'123.456\'', $actual );

		# quoting a null
		$actual = $this->pdo->quote( null );
		$this->assertSame( '\'\'', $actual );
	}

	public function testLastInsertId()
	{
		$params = array(
			'name' => 'Little Miss Muffet',
		);
		$this->insertData( $params );
		$expect = count( $this->data ) + 1;
		$actual = $this->pdo->lastInsertId();
		$this->assertEquals( $expect, $actual );
	}

	public function testTransactions()
	{
		# data
		$params =
		$cols = array(
			'name' => 'Laura',
		);

		# make sure we're not starting in a trasnaction
		$this->assertFalse( $this->pdo->inTransaction() );

		// begin and rollback
		$this->pdo->beginTransaction();
		$this->assertTrue( $this->pdo->inTransaction() );
		$this->insertData( $params );
		$stmt = $this->pdo->query( 'SELECT * FROM pdotest' );
		$actual = $stmt->fetchAll( PDO::FETCH_ASSOC );
		$this->assertSame( count( $this->data ) + 1, count( $actual ) );
		$this->assertTrue( $this->pdo->rollback() );
		$this->assertFalse( $this->pdo->inTransaction() );

		$stmt = $this->pdo->query( 'SELECT * FROM pdotest' );
		$actual = $stmt->fetchAll( PDO::FETCH_ASSOC );
		$this->assertSame( count( $this->data ), count( $actual ) );

		// begin and commit
		$this->assertFalse( $this->pdo->inTransaction() );
		$this->pdo->beginTransaction();
		$this->assertTrue( $this->pdo->inTransaction() );
		$this->insertData( $params );
		$this->pdo->commit();
		$this->assertFalse( $this->pdo->inTransaction() );

		$stmt = $this->pdo->query( 'SELECT * FROM pdotest' );
		$actual = $stmt->fetchAll( PDO::FETCH_ASSOC );
		$this->assertSame( count( $this->data ) + 1, count( $actual ) );
	}

}
