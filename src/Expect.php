<?php

namespace mlleDesmeules\Expect;

use PHPUnit_Framework_Assert as a;
use \InvalidArgumentException;

/**
 * Class Expect
 *
 * @package mlleDesmeules\Expect
 */
class Expect extends ExpectBase
{
	/**
	 * Reports an error if the target is not of the specified type.
	 * This method can also be used as language chain.
	 *
	 * @param null|string $type  The type to check - set to NULL to use as language chain
	 *
	 * @return ExpectBase
	 */
	public function a ( $type = null )
	{
		return is_null($type) ? $this : $this->expect($this->target, a::isType($type));
	}
	
	/**
	 * @param int|float $expected
	 *
	 * @return ExpectBase
	 */
	public function above ( $expected )
	{
		$target = $this->hasFlag('length') ? $this->getLength($this->target) : $this->target;
		
		return $this->expect($target, a::greaterThan($expected));
	}
	
	/**
	 * @param null|string $type
	 *
	 * @return ExpectBase
	 */
	public function an ( $type = null) { return $this->a($type); }
	
	/**
	 * Chainable getter to improve the assertion readability.
	 *
	 * @return ExpectBase
	 */
	public function at  () { return $this; }
	
	/**
	 * Chainable getter to improve the assertion readability.
	 *
	 * @return ExpectBase
	 */
	public function be  () { return $this; }
	
	/**
	 * Chainable getter to improve the assertion readability.
	 *
	 * @return ExpectBase
	 */
	public function been  () { return $this; }
	
	/**
	 * @param int|float $expected
	 *
	 * @return ExpectBase
	 */
	public function below ( $expected )
	{
		$target = $this->hasFlag('length') ? $this->getLength($this->target) : $this->target;
		
		return $this->expect($target, a::lessThan($expected));
	}
	
	/**
	 * Chainable getter to improve the assertion readability.
	 *
	 * @return ExpectBase
	 */
	public function but  () { return $this; }
	
	/**
	 * @param int|float $expected
	 * @param float     $range
	 *
	 * @return ExpectBase
	 */
	public function closeTo ( $expected, $range )
	{
		return $this->expect($this->target, a::equalTo($expected, $range));
	}
	
	/**
	 * @param null $needle
	 *
	 * @return ExpectBase
	 */
	public function contain ( $needle = null )
	{
		if (is_null($needle)) {
			$this->setFlag("contain");
			return $this;
		}
		
		if ($this->hasFlag("file"))
			return $this->expect(@file_get_contents($this->target), a::stringContains($needle));
		
		if (is_string($this->target))
			return $this->expect($this->target, a::stringContains($needle));
		else
			return $this->expect($this->target, a::contains($needle));
	}
	
	/**
	 * @inheritdoc
	 * @see Expect::contain()
	 */
	public function contains ( $needle = null ) { return $this->contain($needle); }
	
	/**
	 * @param string $needle
	 *
	 * @return ExpectBase
	 */
	public function containOnly ( $needle )
	{
		return $this->expect($this->target, a::containsOnly($needle));
	}
	
	/**
	 * @param string $classname
	 *
	 * @return ExpectBase
	 */
	public function containOnlyInstanceOf ( $classname )
	{
		return $this->expect($this->target, a::containsOnlyInstancesOf($classname));
	}
	
	/**
	 * @return ExpectBase
	 */
	public function directory  () { $this->setFlag("directory"); return $this; }
	
	/**
	 * @return ExpectBase
	 */
	public function does  () { return $this; }
	
	public function endWith ( $needle )
	{
		return $this->expect($this->target, a::stringEndsWith($needle));
	}
	
	public function equal ( $expected )
	{
		if ( $this->hasFlag("file") ) {
			if ( $this->hasFlag("negate") )
				a::assertFileNotEquals($expected, $this->target, $this->message);
			else
				a::assertFileEquals($expected, $this->target, $this->message);
			
			return $this;
		}
		
		$target = $this->hasFlag("length") ? $this->getLength($this->target) : $this->target;
		
		return $this->expect($target, a::equalTo($expected));
	}
	
	public function equals ( $expected ) { return $this->equal($expected); }
	
	public function exists ()
	{
		if ($this->hasFlag("file"))
			$constraint = a::fileExists();
		else
			throw new InvalidArgumentException("This assertion is not a file");
		
		return $this->expect($this->target, $constraint);
	}
	
	public function false ()
	{
		return $this->expect($this->target, a::isFalse());
	}
	
	public function file  () { $this->setFlag("file"); return $this; }
	
	public function has  () { return $this; }
	
	public function have  () { return $this; }
	
	public function identicalTo ( $expected )
	{
		return $this->expect($this->target, a::identicalTo($expected));
	}
	
	public function includes ( $needle = null ) { $this->contain($needle); }
	
	// public function infinite  () { return $this->expect($this->target, ); }
	
	public function is  () { return $this; }
	
	public function isInstanceOf ( $className )
	{
		return $this->expect($this->target, a::isInstanceOf($className));
	}
	
	public function isEmpty ()
	{
		if (is_object($this->target) && !($this->target instanceof \Countable)) {
			$constraint = a::countOf(0);
			$target     = get_object_vars($this->target);
			
		} else if (is_string($this->target)) {
			$constraint = a::countOf(0);
			$target     = mb_strlen($this->target);
			
		} else {
			$constraint = a::isEmpty();
			$target     = $this->target;
		}
		
		return $this->expect($target, $constraint);
	}
	
	public function json  () { $this->setFlag("json"); return $this; }
	
	public function least ( $expected )
	{
		$target = $this->hasFlag("length") ? $this->getLength($this->target) : $this->target;
		
		return $this->expect($target, a::greaterThanOrEqual($expected));
	}
	
	public function length ( $expected = null ) { return $this->lengthOf($expected); }
	
	public function lengthOf ( $expected = null )
	{
		if (is_null($expected)) {
			$this->setFlag("length");
			return $this;
		}
		
		if (is_string($expected)) {
			$constraint = a::equalTo($expected);
			$target     = mb_strlen($this->target);
		} else {
			$constraint = a::countOf($expected);
			$target     = $this->target;
		}
		
		return $this->expect($target, $constraint);
	}
	
	public function match( $pattern)
	{
		return $this->expect($this->target, a::matchesRegularExpression($pattern));
	}
	
	public function matchFormat( $format)
	{
		return $this->expect($this->target, a::matches($format));
	}
	
	public function most($value)
	{
		$target = $this->hasFlag('length') ? $this->getLength($this->target) : $this->target;
		return $this->expect($target, a::lessThanOrEqual($value));
	}
	
	
	public function NaN()
	{
		//return $this->expect($this->target, a::isNan());
	}
	
	
	public function not()
	{
		$this->setFlag('negate');
		return $this;
	}
	
	
	public function null()
	{
		return $this->expect($this->target, a::isNull());
	}
	
	
	public function of()
	{
		return $this;
	}
	
	public function oneOf($value)
	{
		return $this->expect($value, a::contains($this->target));
	}
	
	public function ordered () { $this->setFlag('ordered'); return $this; }
	
	public function property ( $name, $value = null )
	{
		$isArray = is_array($this->target) || $this->target instanceof \ArrayAccess;
		
		if (!$isArray && !is_object($this->target)) {
			throw new \BadMethodCallException('The target is not an array nor an object.');
		}
		
		if ($isArray) {
			$hasProperty           = array_key_exists($name, $this->target);
			$hasPropertyConstraint = a::arrayHasKey($name);
			$property              = (isset($this->target[ $name ]) ? $this->target[ $name ] : null);
		} else {
			$hasProperty           = property_exists($this->target, $name);
			$hasPropertyConstraint = a::objectHasAttribute($name);
			$property              = (isset($this->target->$name) ? $this->target->$name : null);
		}
		
		if (!$hasProperty || $value === null) {
			$this->expect($this->target, $hasPropertyConstraint);
		} else {
			a::assertThat($this->target, $hasPropertyConstraint);
			$this->expect($property, a::equalTo($value));
		}
		
		$this->target = $property;
		
		return $this;
	}
	
	public function readable ()
	{
		if (!$this->hasFlag('directory') && !$this->hasFlag('file'))
			throw new \BadMethodCallException('This assertion is not a file or directory one.');
		
		//return $this->expect($this->target, a::isReadable());
	}
	
	public function same () { return $this; }
	
	public function satisfy ($predicate)
	{
		return $this->expect(call_user_func($predicate, $this->target), a::isTrue());
	}
	
	public function startWith ( $value)
	{
		return $this->expect($this->target, a::stringStartsWith($value));
	}
	
	public function that () { return $this; }
	
	public function to () { return $this; }
	
	/*public function throw( $className = '')
	{
		if (!is_callable($this->target)) throw new \BadMethodCallException('The function target is not callable.');
		$exception = null;
		try { call_user_func($this->target); }
		catch (\Throwable $e) { $exception = $e; }
		$constraint = a::logicalNot(a::isNull());
		return $this->expect($exception, mb_strlen($className) ? a::logicalAnd($constraint, a::isInstanceOf($className)) : $constraint);
	}*/
	
	public function true ()
	{
		return $this->expect($this->target, a::isTrue());
	}
	
	public function which () { return $this; }
	
	public function with () { return $this; }
	
	public function within($start, $finish)
	{
		$target = $this->hasFlag('length') ? $this->getLength($this->target) : $this->target;
		
		return $this->expect($target, a::logicalAnd(a::greaterThanOrEqual($start), a::lessThanOrEqual($finish)));
	}
	
	public function writable()
	{
		if (!$this->hasFlag('directory') && !$this->hasFlag('file'))
			throw new \BadMethodCallException('This assertion is not a file or directory one.');
		
		//return $this->expect($this->target, a::isWritable());
	}
	
	public function xml () { $this->setFlag('xml'); return $this; }
}