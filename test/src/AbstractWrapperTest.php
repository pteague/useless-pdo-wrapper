<?php
namespace Useless\Pdo;

use PDO;

abstract class AbstractWrapperTest
	extends \PHPUnit_Framework_TestCase
{
	protected $pdo;

	protected $data = array(
	);

	public function setUp()
	{
		if ( !extension_loaded( 'pdo_sqlite' ) ) {
			$this->markTestSkipped( "Need 'pdo_sqlite' to test in memory." );
		}

		$this->pdo = $this->newPdo();
	}

	abstract protected function newPdo();

}
