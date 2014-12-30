<?php
namespace Useless\Pdo;

use PDO;

class WrapperTest
	extends AbstractWrapperTest
{
	protected function newPdo()
	{
		return new Wrapper( 'sqlite::memory:' );
	}

	public function testGetPdo()
	{
		$lazy_pdo = $this->pdo->getPdo();
		$this->assertInstanceOf( 'PDO', $lazy_pdo );
		$this->assertNotSame( $this->pdo, $lazy_pdo );
	}
}
