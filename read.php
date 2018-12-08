<?php
/**
 *
 * @package usb-compacity-test
 * @author Sergei Miami <miami@blackcrystal.net>
 */

$size = $argv[1]; // 1gb
$stream = $argv[2]; //"/dev/sdb";

$string = "Show what you can. Learn what you don't. ";
$length = strlen($string);

$file = fopen ($stream, 'rb');
$position = 0;
do {
  $data = fgets($file, $length+1);

  if ($data !== $string)
    throw new Exception("Wrong string '$data' at $position");

  $position += $length;
  echo " \rReading $position";
} while ($position < $size);
fclose($file);
echo "\rReading complete.        \nFile is correct.";