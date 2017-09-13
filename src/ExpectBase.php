<?php
namespace mlleDesmeules\Expect\src;

use \InvalidArgumentException;
use PHPUnit_Framework_Assert as a;
use PHPUnit_Framework_Constraint as c;

/**
 * Class ExpectBase
 *
 * Base class to use for other expect classes to inherit features from.
 * Currently, just the constructor and some basic properties that are common to all classes.
 *
 * @package mlleDesmeules\Expect
 * @abstract
 */
abstract class ExpectBase
{
	/** @var array      The assertion flags */
	protected $flags = [];
	
	/** @var  mixed     Actual value that will be the Test Subject */
	protected $target;
	
	/** @var  string    Description to be displayed if assertion fails */
	protected $message;
	
	/**
	 * ExpectBase constructor.
	 *
	 * @param mixed  $target    Actual value for test
	 * @param string $message   optional - message if the assertion fails
	 */
	public function __construct ( $target, $message = '' )
	{
		$this->target      = $target;
		$this->message = $message;
	}
	
	/**
	 * @param string $name
	 */
	public function __get ( $name )
	{
		static $reflection;
		
		if (!$reflection)
			$reflection = new \ReflectionClass(static::class);
		
		if ($reflection->hasMethod($name)) {
			$method = $reflection->getMethod($name);
			
			if ($method->isPublic() && !$method->getNumberOfRequiredParameters())
				return $this->$name();
		}
		
		throw new InvalidArgumentException("The specified method is not found : $name");
	}
	
	/**
	 * Asserts that the specified target matches the specified target
	 *
	 * @param mixed $target
	 * @param c     $constraint
	 *
	 * @return $this
	 */
	protected function expect ( $target, $constraint )
	{
		$constraint = ($this->hasFlag('negate')) ? a::logicalNot($constraint) : $constraint;
		
		a::assertThat($target, $constraint, $this->message);
		return $this;
	}
	
	/**
	 * Return the length of the passed value
	 *
	 * @param mixed $value  an iterable value like an array or a string
	 *
	 * @return int
	 * @throws InvalidArgumentException
	 */
	protected function getLength ( $value )
	{
		if (is_array($value) || $value instanceof \Countable)
			return count($value);
		
		if ($value instanceof \Traversable)
			return iterator_count($value);
		
		if (is_string($value))
			return mb_strlen($value);
		
		throw new InvalidArgumentException("The specified value is not iterable : $value");
	}
	
	/**
	 * Gets the value that indicates if this assertion has a specified flag
	 *
	 * @param string $name  The flag name
	 *
	 * @return bool
	 */
	protected function hasFlag ( $name ) { return $this->flags[ $name ]; }
	
	/**
	 * Sets a value that indicates if this assertions has the specified flag
	 *
	 * @param string $name  The flag name
	 * @param bool   $value If the assertions has the specified flags
	 */
	protected function setFlag ( $name, $value = true ) { $this->flags[ $name ] = $value; }
}
