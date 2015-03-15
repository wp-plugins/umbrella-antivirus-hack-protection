<?php
namespace Umbrella;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Scanner
{
    /**
     * Get Whitelist
     * Get whitelist for the current WP version.
     * @return void
    */
    public function whitelist()
    {
        $locale = get_locale();
        $data_file = UMBRELLA__PLUGIN_DIR . "data/wordpress-{$this->wp_version()}.db";

        // Return whitelist as array if found for current WP version.
        if (file_exists($data_file))
            return parse_ini_file($data_file, true);
        else
            return false;
    }   

    public static function google_safe_browsing_code()
    {
        /* Start request */
        $response = wp_remote_get(
            sprintf(
                'https://sb-ssl.google.com/safebrowsing/api/lookup?client=191233873456-jfbgdmrlv1kqlmot3559mnu1rrglggfa.apps.googleusercontent.com&key=%s&appver=1.5.2&pver=3.1&url=%s',
                'AIzaSyCaX3EIz6RzsF1MlDoJ8wJENsFg0v-dcXc',
                urlencode( get_bloginfo('url') )
            ),
            array(
                'sslverify' => false
            )
        );
        return $response['response']['code'];
    }

    /**
     * Vulnerabilities Scanner for Plugins
     * This will scan installed plugins for vulnerabilities.
     * @return array
    */
    public static function vulndb_plugins()
    {
        $all_plugins = get_plugins();

        $plugins = array();

        foreach ($all_plugins as $key => $plugin)
        {
            unset($merge);
            unset($json);

            $slug = explode('/', $key);
            $slug = reset($slug);

            if ($slug == 'hello.php')
                continue;

            if ( false === ( $json = get_transient( "umbrella_vulndb_{$slug}" ) ) ) {
                $json = wp_remote_get( "https://wpvulndb.com/api/v1/plugins/{$slug}" );
                
                if (!\is_wp_error($json))
                    set_transient( "umbrella_vulndb_{$slug}", $json, 300 );
            }

            if (!\is_wp_error($json))
                $merge = array('vulndb' => $json);
            else 
                $merge['vulndb']['error']['code'] = '501';
            
        
            $plugins[] = array_merge($plugin,$merge);
        }

        return $plugins;
    }  

    /**
     * Plugins Errors
     * Get errors from Vulndb for scanned plugins.
     * @return array
    */
    public static function vulndb_plugins_errors() {
        $plugins = Scanner::vulndb_plugins();

        $errors_total = 0;

        foreach ($plugins as $plugin)
        {
            $code = $plugin['vulndb']['response']['code'];

            if ($code == 200)
            {
                $vulndbs = json_decode($plugin['vulndb']['body']);
                if (is_object($vulndbs)) {
                    foreach($vulndbs->plugin->vulnerabilities as $v) {
                        $errors_total++;
                    }
                }
            }
        }

        return array(
            'errors_total' => $errors_total
        );
    }

    /**
     * Vulnerabilities Scanner for Themes
     * This will scan installed themes for vulnerabilities.
     * @return array
    */
    public static function vulndb_themes()
    {
        $all_themes = wp_get_themes();
        $themes = array();

        foreach ($all_themes as $slug => $theme)
        {
            unset($merge);
            unset($json);
            
            if ( false === ( $json = get_transient( "umbrella_vulndb_theme_{$slug}" ) ) ) {
                $json = wp_remote_get( "https://wpvulndb.com/api/v1/themes/{$slug}" );
                
                if (!\is_wp_error($json))
                    set_transient( "umbrella_vulndb_theme_{$slug}", $json, 300 );
            }

            $merge = array(
                'Name' => $theme->get('Name'),
                'Version' => $theme->get('Version'),
                'Author' => $theme->get('Author'),
            );

            if (!\is_wp_error($json))
                $merge['vulndb'] = $json;
            else {
                $merge['vulndb']['error']['code'] = '0';
            }

            $themes[] = $merge;

        }

        return $themes;
    }

    /**
     * Get Whitelist
     * Get whitelist for the current WP version.
     * @return json
    */
    public function api_get_files()
    {
        die( json_encode( $this->list_core_files() ) );
    }  

    /**
     * Reverse Ip
     * Get all domains sharing the same ip
     * @return json
    */
    static public function reverse_ip()
    {
        $ip = gethostbyname($_SERVER['SERVER_NAME']);
        $url = 'http://api.hackertarget.com/reverseiplookup/?q=' . $ip;

        $data = wp_remote_get($url);
        $domains = explode("\n", $data['body']);
        
        return $domains;
    }    

    /**
     * Has Cloudflare
     * Return true if site is protected by Cloud Flare.
     * @return bool
    */
    static public function has_cloudflare()
    {
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"]))
            return true;
        else
            false;
    }

    /**
     * Monitor files list
     * Return lists of files to monitor and not include in file scanner.
     * @return array
    */
    public function monitor_files_list()
    {
        return array(
            'wp-config-sample.php',
            'wp-includes/version.php',
            'wp-content/',
            'wp-config.php',
            'readme.html',
            '.txt',
            '/..',
            '/.',
        );
    }

    /**
     * List core files
     * List core files of the current install.
     * @return void
    */
    public function list_core_files()
    {

        $exclude = $this->monitor_files_list();

        // Get files and directories list.
        $files = new \RecursiveIteratorIterator( new \RecursiveDirectoryIterator( ABSPATH ) );
        
        // new empty output
        $output = array();

        foreach ($files as $file)
        {

            $file = (string) $file;

            $continue = 0;
            foreach ($exclude as $e)
            {
                if ( strpos($file, $e) !== false ) 
                    $continue = 1;
            }

            if ($continue == 0) 
                $output[] = str_replace(ABSPATH, '', $file);
            else 
                $continue = 1;
        
        }

        usort($output, array(&$this, 'sort'));

        return $output;

    }

    public function sort($a,$b){
        return strlen($a)-strlen($b);
    }


    /**
     * Check File
     * Get whitelist for the current WP version.
     * @return void
    */
    public function check_file( $file = '' )
    {
        $whitelist = $this->whitelist();

        // File is unknown (not included in core)
        if (!isset($whitelist[$file])) {

            $url = "admin.php?page=umbrella-scanner&action=remove&file={$file}";
            $nonce_url =  wp_nonce_url( $url, "remove_{$file}" );

            Log::write(__('File Scanner', UMBRELLA__TEXTDOMAIN), __("Unexpected file:", UMBRELLA__TEXTDOMAIN) . ' ' . $file );


            return array(
                'error' => array('code' => '0010', 'msg' => 'Unexpected file'),
                'file' => ABSPATH . $file,
                'buttons' => array(
                    array(
                        'label' => __('Actions not available in the BETA version', UMBRELLA__TEXTDOMAIN),
                        'href' => '#'
                    ),
                )
            );

        }

        $original_md5 = $whitelist[$file];
       
        $file_data = file_get_contents( ABSPATH . $file );

        if (md5($file_data) != $original_md5)
        {
            Log::write(__('File Scanner', UMBRELLA__TEXTDOMAIN), __("Modified file:", UMBRELLA__TEXTDOMAIN) . ' ' . $file );
            
            return array(
                'error' => array('code' => '0020', 'msg' => 'Modified file'),
                'file' => $file,
                'buttons' => array(
                    array(
                        'label' => __('Actions not available in the BETA version', UMBRELLA__TEXTDOMAIN),
                        'href' => '#'
                    ),
                )
            );
        }
           
    }

    /**
     * Remove File
     * Completely remove a file from the system.
     * @return void
    */
    public function remove_file($file)
    {
        if (!wp_verify_nonce( $_GET['_wpnonce'], "remove_{$file}" )) die();

        global $wp_filesystem;

        $form_url = "admin.php?page=umbrella-scanner&action=remove&file=" . $file."&_wpnonce=".$_GET['_wpnonce'];

        if (false === ($creds = request_filesystem_credentials($form_url, 'ftp', false, ABSPATH))) {
            /**
             * if we comes here - we don't have credentials
             * so the request for them is displaying
             * no need for further processing
             **/
            return false;
        }

        $file = esc_attr($file);
        echo 'Remove file: ' . $file;
    }

    /**
     * Ignore File
     * Temporary ignore file modifications on file.
     * @return void
    */
    public function ignore_file($file)
    {
        $file = esc_attr($file);
        echo 'Ignore file: ' . $file;
    }    

    /**
     * Update Core whitelist
     * Update Core whitelist with the latest core download.
     * @return void
    */
    static public function update_core_whitelist()
    {
        die("Time to update!");
    }    

    /**
     * Reinstall file
     * Download the right version of the file from 
     * Github and replace the modified one.
     * @return void
    */
    public function reinstall_file($file)
    {
        $file = esc_attr($file);
        echo 'Re-install file: ' . $file;
    }    

    /**
     * Compare file
     * Download the right version of the file from 
     * Github and compare it with the modified
     * @return void
    */
    public function compare_file($file)
    {
        $file = esc_attr($file);
        echo 'Compare file: ' . $file;
    }


    /**
     * WP Version
     * Get current WP version
     * @return void
    */
    public function wp_version()
    {
        global $wp_version;
        return str_replace('.','', $wp_version);
    }

    public static function progressbar()
    {
        for ($i=1; $i <= 100; $i++) { 
            echo "<div id='progress{$i}' class='progress'></div>";
        }
        echo "<div style='clear:both;'></div>";
    }

} 

add_action('admin_init', function() {

    if (current_user_can('administrator')
        AND isset($_GET['page']) 
        AND $_GET['page'] == 'umbrella-scanner' 
    ){
        if (isset($_GET['action'])) 
        {
            $scanner = new Scanner;

            switch($_GET['action'])
            {
                case 'remove'; $scanner->remove_file($_GET['file']); break;
                case 'ignore'; $scanner->ignore_file($_GET['file']); break;
                case 'reinstall'; $scanner->reinstall_file($_GET['file']); break;
                case 'compare'; $scanner->compare_file($_GET['file']); break;
                case 'get_files'; $scanner->api_get_files(); break;
                case 'check_file'; 
                    echo json_encode($scanner->check_file($_GET['file'])); 
                    die();
                break;
            }
        }
    }

});

function arr2ini(array $a, array $parent = array())
{
    $out = '';
    foreach ($a as $k => $v)
    {
        if (is_array($v))
        {
            //subsection case
            //merge all the sections into one array...
            $sec = array_merge((array) $parent, (array) $k);
            //add section information to the output
            $out .= '[' . join('.', $sec) . ']' . PHP_EOL;
            //recursively traverse deeper
            $out .= arr2ini($v, $sec);
        }
        else
        {
            //plain key->value case
            $out .= "$k=$v" . PHP_EOL;
        }
    }
    return $out;
}