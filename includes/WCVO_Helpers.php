<?php


class WCVO_Helpers {
	/**
	 * print data into log file named by date in plugin directory
	 *
	 * @param string|array|object $log_data
	 */
	public static function log( $log_data ) {
		$log_filename = WCVO_DIR . "log";
		if ( ! file_exists( $log_filename ) ) {
			// create directory/folder uploads.
			mkdir( $log_filename, 0777, true );
		}
		$log_file_data = $log_filename . '/log_' . date( 'd-M-Y' ) . '.log';
		// if you don't add `FILE_APPEND`, the file will be erased each time you add a log
		file_put_contents( $log_file_data, 'Info : ' . date( "d/m/Y h:i:sa" ) . ' ' . print_r( $log_data, true ) . "\n", FILE_APPEND );
	}
}