<?php
namespace Useless\Pdo;

use PDO;
use Useless\Pdo\Config\Loader as ConfigLoader;
use Useless\Pdo\Config\AbstractConfig;

class WrapperConfigSqliteTest
	extends AbstractWrapperTest
{
	protected function newPdo()
	{
		$configLoader = new ConfigLoader();
		$config = $configLoader->getConfig( array(
			AbstractConfig::DRIVER => ConfigLoader::CONFIG_SQLITE,
			AbstractConfig::UNIX_SOCKET => AbstractConfig::SQLITE_MEMORY_TABLE,
		) );
		$config->addOption( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		$config->addAttribute( PDO::ATTR_CASE, PDO::CASE_LOWER );
		return new Wrapper( $config );
	}

	public function testGetPdo()
	{
		$lazy_pdo = $this->pdo->getPdo();
		$this->assertInstanceOf( 'PDO', $lazy_pdo );
		$this->assertNotSame( $this->pdo, $lazy_pdo );
	}
}
