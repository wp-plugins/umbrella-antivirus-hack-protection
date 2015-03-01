<?php
namespace Umbrella;
class FileHandler
{
	public static $time_to_cache = 3600;

	public function search($path, $key, $value, $cache = true)
	{
		$array = $this->scan_files($path, $cache);
		$results = array();

		 if (is_array($array)) {

		 	foreach ($array as $subarray)
		 	{

		 		if (isset($subarray[$key]) && $subarray[$key] == $value) {
		            $results[] = $subarray;
		        }
		 	}
	        
	    }

	    return $results;
	}

	public function scan_files($path , $cache = true )
	{

		// Don't accept scans outside WordPress file directory.
		$search = str_replace("/", "\/", ABSPATH);
		if (!preg_match("/{$search}/",$path))
			return die("Don't accept scans outside WordPress file directory.");

		// Delete transient if $cache i set to false.
		if ($cache == false)
			delete_transient("umbrella_file_list-{$path}");

		if ( false === ( $output = get_transient( "umbrella_file_list-{$path}" ) ) )
		{
			// Ignore Files
			$ignore = array('.','..');

			// Return false if $path is empty.
			if (!isset($path))
				return false;

			// Get files and directories list.
			$files = new \RecursiveIteratorIterator( new \RecursiveDirectoryIterator($path) );
			
			// new empty output
			$output = array();

			$i = 0;
			foreach ($files as $file)
			{

				// Convert from object to string.
				$file = (string) $file;

				// Get file name from path string.
				$filename = explode('/', $file);
				$filename = end($filename);

				// Remove files found in $ignore.
				if (!in_array( $filename , $ignore))
				{

					$output[$i] = array(
						'file' => str_replace($path, '', $file),
						'path' => $file,
						'md5' => self::md5($file),
						'chmod' => self::chmod($file),
						'is_writable' => self::is_writable($file),
					);
				}

				$i++;
			}

			set_transient( "umbrella_file_list-{$path}", $output, self::$time_to_cache );
		}

		return $output;

	}

	public static function is_writable( $file )
	{
		if (is_writable($file))
			return '1';
		else
			return '0';
	}

	// Get chmod from file path.
	public static function chmod( $file )
	{
		$length = strlen(decoct(fileperms($file)))-3;
        return substr(decoct(fileperms($file)),$length);
	}

	// Get md5 checksum from file path.
	public static function md5( $file )
	{
		if (!file_exists($file))
			return false;

		$contents = file_get_contents($file);
		return md5( $contents );
	}
}


