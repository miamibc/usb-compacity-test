<?php
/**
 *
 * @package usb-compacity-test
 * @author Sergei Miami <miami@blackcrystal.net>
 */

$size  = round((isset($argv[1]) ? $argv[1] : 16)*1024*1024*1024); // 16gb
$file  = isset($argv[2]) ? $argv[2] : '/dev/sdb'; //"/dev/sdb";
$steps = isset($argv[3]) ? $argv[3] : 1024;

$string = str_repeat("Show what you can. Learn what you don't. ",1);
$length = strlen($string);

echo "Starting $size bytes test of $file in $steps steps\n";
$time= time();

$positions = [];
for($i = 1; $i<=$steps; $i++)
{
  $positions[] = $last = rand( $i*$size/$steps - $size/$steps/3, $i*$size/$steps + $size/$steps/3 );
  echo "Generating position $i $last \r";
}

sleep(1);
echo "\rGenerating position complete      \n";

$errors = [];

echo "Shuffling...\n";
shuffle($positions);
$stream = fopen($file, 'wb');
echo "Writing...\n";
foreach ($positions as $i=>$position)
{
  $percent = round(($i+1)*100/count($positions),1);
  echo "\r$percent% writing @ $position       ";
  fseek($stream,$position,SEEK_SET);
  $result = fwrite($stream,$string,$length);
  if ($result === false)
  {
    echo "$percent% writing @ $position error '$result'\n";
    $errors[] = $position;
  }
}
fclose($stream);
echo "\r$percent% writing complete       \n";

echo "Shuffling...\n";
shuffle($positions);
$stream = fopen($file, 'rb');
echo "Reading...\n";

foreach ($positions as $i=>$position)
{
  $percent = round(($i+1)*100/count($positions),1);
  echo "\r$percent% reading @ $position       ";
  fseek($stream,$position,SEEK_SET);
  $result = fread( $stream, $length);
  if ( $result !== $string)
  {
    echo "$percent% reading @ $position error '$result'\n";
    $errors[] = $position;
  }
}
fclose($stream);
echo "\r$percent% reading complete       \n";

echo "Test complete in ". (time()-$time) . " seconds\n";
echo "Positions tested ". count($positions) ." range " . min($positions) . " - " . max($positions) ."\n";

if (!count($errors))
{
  echo "No errors found\n";
}
else
{
  echo count($errors) . " errors found\n";
  echo "Starts at position ". min($errors) ."\n";
  echo "Ends at position " .  max($errors) . "\n";
}
