<?php

function array_keys_intersect_recursive($array1, $array2)
{
  foreach($array1 as $key => $value)
  {
    if (!isset($array2[$key]))
    {
      unset($array1[$key]);
    }
    else
    {
      if (is_array($array1[$key]))
      {
        $array1[$key] = array_keys_intersect_recursive($array1[$key], $array2[$key]);
      }
      //elseif ($array2[$key] !== $value)
      //{
      //  unset($array1[$key]);
      //}
    }
  }
  return $array1;
}

?>
