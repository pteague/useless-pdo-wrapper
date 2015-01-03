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
class Debug
{
	/**
	 * @var string
	 */
	protected $sql;

	/**
	 * @var array
	 */
	protected $parameters;

	/**
	 * @var bool
	 */
	protected $questionMarks = false;

	/**
	 * @param string $sql optional
	 * @param array $parameters optional
	 * @param bool $questionMarks optional
	 */
	public function __construct( $sql = null, array $parameters = null, $questionMarks = false )
	{
		$this->setSql( $sql );
		$this->setParameters( $parameters );
		$this->setQueryUsesQuestionMarks( $questionMarks );
	}

	/**
	 * Returns a very approximated idea as to what the final prepared statement looks like.
	 * Please note that this is most assuredly *not* what is being run on the server. This
	 * is just to give you an approximation of how it could be converted. The output from
	 * this could be helpful for debugging the data retrieved, but that's about it.
	 *
	 * @return string
	 */
	public function getDebug()
	{
		$sql = $this->getSql();
		$params = $this->getParameters();
		$questionMarks = $this->getQueryUsesQuestionMarks();
		$pattern = array();
		$replace = array();
		if ( $questionMarks ) {
			$limit = 1;
		}
		else {
			$limit = -1;
		}
		foreach ( $params as $key => $value ) {
			if ( $questionMarks ) {
				$pattern[] = '@\?@';
			}
			else {
				$pattern[] = '@:' . preg_quote( $key, '@' ) . '\b@';
			}
			$replace[] = str_replace( '$', '\\$', var_export( $value, true ) );
		}

		if ( $params ) {
			return preg_replace( $pattern, $replace, $sql, $limit );
		}
		else {
			return $sql;
		}
	}

	/**
	 * @return string
	 */
	public function getSql()
	{
		return $this->sql;
	}

	/**
	 * @return array
	 */
	public function getParameters()
	{
		return $this->parameters;
	}

	/**
	 * @return bool
	 */
	public function getQueryUsesQuestionMarks()
	{
		return $this->questionMarks;
	}

	/**
	 * @param string $sql
	 */
	public function setSql( $sql )
	{
		$this->sql = $sql;
	}

	/**
	 * @param array $parameters
	 */
	public function setParameters( array $parameters = null )
	{
		$this->parameters = $parameters;
	}

	/**
	 * @param bool $questionMarks
	 */
	public function setQueryUsesQuestionMarks( $questionMarks = false )
	{
		$this->questionMarks = (bool)$questionMarks;
	}
}

