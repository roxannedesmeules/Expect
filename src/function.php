<?php

namespace mlleDesmeules\Expect;

if (!function_exists('expect')) {
	
	/**
	 * Interface into Verify library and main set of assertions
	 *
	 * @param mixed        $target      Value for Test
	 * @param string|mixed $description Optional - Description to display if the assertion fails.
	 *
	 * @throws \BadMethodCallException When called with 0 arguments, or more than two arguments
	 *
	 * @return Expect
	 */
	function expect()
	{
		switch(func_num_args()) {
			case 1:
				return new Expect(func_get_arg(0));
			case 2:
				return new Expect(func_get_arg(0), func_get_arg(1));
			default:
				throw new \BadMethodCallException('expect() must be called with exactly 1 or 2 arguments.');
		}
	}
	
}
