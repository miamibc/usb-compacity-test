<?php
/**
 *
 * @package usb-compacity-test
 * @author Sergei Miami <miami@blackcrystal.net>
 */

$size  = isset($argv[1]) ? $argv[1] : 10*1024*1024; // 10gb
$file  = isset($argv[2]) ? $argv[2] : '/dev/sdb'; //"/dev/sdb";
$steps = isset($argv[3]) ? $argv[3] : 1024;

$string = "Show what you can. Learn what you don't. ";
$length = strlen($string);

$file = fopen($stream, 'wb');
$position = 0;
while ($position < $size)
{
  $result = fwrite($file, $string, $length);
  if ($result === false)
    throw new Exception("Error writing to file @ $position");
  $position += $result;
  echo "\rWriting $position";
}
echo "\rWriting complete.        \n";
fclose($file);
$file = fopen ($stream, 'rb');

while ($position < $size)
{
  $data = fgets($file, $length+1);
  if ($data !== $string)
    throw new Exception("Wrong string '$data' at $position");
  $position += $length;
  echo " \rReading $position";
}
fclose($file);

echo "\nTest complete.";