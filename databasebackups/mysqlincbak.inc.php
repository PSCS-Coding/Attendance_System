<?php
if (!function_exists("microint")) // was possibly already defined in ads.php....
{
	function microint()
	{	$tmp = microtime();
		$parts = explode(" ",$tmp);
		$floattime = (float)$parts[0] + (float)$parts[1];
		return $floattime;
	}
}

function megabytes($bytes)
{
	$mb = 1024*1024;
	return number_format($bytes/$mb,1);
}

if (!function_exists('write_ini_file'))
{
    function write_ini_file($assocarr, $path, $hassections = false)
    {
        $content = "";
        if ($hassections)
        {
            foreach ($assocarr as $key => $elem)
            {
                $content .= "[" . $key . "]\n";
                foreach ($elem as $key2 => $elem2)
                {
                    if (is_array($elem2))
                    {
                        for ($i = 0; $i < count($elem2); $i++)
                            $content .= $key2 . "[] = \"" . $elem2[$i] . "\"\n";
                    }
                    else if ($elem2 == "")
                        $content .= $key2 . " = \n";
                    else
                        $content .= $key2 . " = \"" . $elem2 . "\"\n";
                }
            }
        }
        else
        {
            foreach ($assocarr as $key => $elem)
            {
                if (is_array($elem))
                {
                    for ($i = 0; $i < count($elem); $i++)
                    {
                        $content .= $key2 . "[] = \"" . $elem[$i] . "\"\n";
                    }
                }
                else if ($elem == "")
                    $content .= $key2 . " = \n";
                else
                    $content .= $key2 . " = \"" . $elem . "\"\n";
            }
        }
        if (!$handle = fopen($path, 'w'))
            return false;
        if (!fwrite($handle, $content))
            return false;
        fclose($handle);
        return true;
    }
}
?>