<?php
/**
 * @package    Joomla.Cli
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// We are a valid entry point.
const _JEXEC = 1;

// Load system defines
if (file_exists(dirname(dirname(__DIR__)) . '/defines.php'))
{
	require_once dirname(dirname(__DIR__)) . '/defines.php';
}

if (!defined('_JDEFINES'))
{
	define('JPATH_BASE', dirname(dirname(__DIR__)));
	require_once JPATH_BASE . '/includes/defines.php';
}

// Get the framework.
require_once JPATH_LIBRARIES . '/import.legacy.php';

// Bootstrap the CMS libraries.
require_once JPATH_LIBRARIES . '/cms.php';

// Configure error reporting to maximum for CLI output.
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * A command line cron job to attempt to remove files that should have been deleted at update.
 *
 * @package  Joomla.CLI
 * @since    3.0
 */
class BackupSite extends JApplicationCli
{
	/**
	 * Entry point for CLI script
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function doExecute()
	{

	    ini_set("max_execution_time", 300);

		jimport( 'joomla.filesystem.archive' );
		jimport( 'joomla.filesystem.folder' );
		jimport( 'joomla.filesystem.file' );

		$config = JFactory::getConfig();

		$username = $config->get('user');
		$password = $config->get('password');
		$database = $config->get('db');

		echo 'Exporting database...
';
		exec("mysqldump --user={$username} --password={$password} --quick --add-drop-table --add-locks --extended-insert --lock-tables --all {$database} > ".JPATH_SITE."/database-backup.sql");

	    $zipFilesArray = array();
	    $dirs = JFolder::folders(JPATH_SITE, '.', true, true);
	    array_push($dirs, JPATH_SITE);

	    echo 'Collecting files...
';

	    foreach ($dirs as $dir) {
	        $files = JFolder::files($dir, '.', false, true);
	        foreach ($files as $file) {
	            $data = JFile::read($file);
	            $zipFilesArray[] = array('name' => str_replace(JPATH_SITE.'/', '', $file), 'data' => $data);
	        }
	    }
	    
	    $zip = JArchive::getAdapter('zip');

		echo 'Creating zip...
';

	    $archive = JPATH_SITE . '/backups/'.date('Ymd').'-backup.zip';
	    
	    $zip->create($archive, $zipFilesArray);

	    echo 'Backup created '.$archive.'
';

	}
}

// Instantiate the application object, passing the class name to JCli::getInstance
// and use chaining to execute the application.
JApplicationCli::getInstance('BackupSite')->execute();
