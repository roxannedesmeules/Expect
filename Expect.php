<?php

namespace mlleDesmeules\Expect;

use mlleDesmeules\Expect\src\Expect as e;

/**
 * Trait Expect
 *
 * Trait Expect allow the usage of the expect library as a property of the unit test class that uses it.
 *
 * @package mlleDesmeules\Expect
 */
trait Expect
{
	function expect ( $target, $description = "" )
	{
		return new e( $target, $description );
	}
}