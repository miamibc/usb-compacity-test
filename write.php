<?php
/**
 *
 * @package usb-compacity-test
 * @author Sergei Miami <miami@blackcrystal.net>
 */

$size = $argv[1]; // 1gb
$stream = $argv[2]; //"/dev/sdb";

echo "Writing $size bytes to $stream ...\n";

$string = "Show what you can. Learn what you don't. ";
$length = strlen($string);

$file = fopen($stream, 'wb');
$position = 0;
do {
  $result = fwrite($file, $string, $length);
  if ($result === false)
    throw new Exception("Error writing to file @ $position");
  $position += $result;
  echo "\rWriting $position";
} while ($position < $size);
fclose($file);
echo "\rWriting complete.        \nNow run read.php with same parameters.";