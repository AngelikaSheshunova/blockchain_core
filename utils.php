<?php

function removeMatches(&$in1, $in2)
{
	$output = array();

	foreach ($in1 as $key => $in1_value)
	{
		if (isset($in2[$key]))
		{
			if (is_array($in1_value))
			{
				$k = removeMatches($in1_value, $in2[$key]);
				if (is_array($k))
				{
					$output[$key] = $k;

				} else {
					$output[$key] = 1;
				}

			} else {
				if ($in1_value == $in2[$key])
				{
					unset($in1[$key]);
					$output[$key] = 1;
				}
			}
		}
	}

	return $output;
}

?>
