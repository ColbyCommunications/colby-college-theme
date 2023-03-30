<?php
/*
 * The configuration of SimpleSAMLphp
 *
 */

$config = array(

	'baseurlpath'                   => 'https://colby.lndo.site/',

	'certdir'                       => 'cert/',
	'loggingdir'                    => 'log/',
	'datadir'                       => 'data/',
	'tempdir'                       => '/tmp/simplesaml',


	'technicalcontact_name'         => 'Webmaster',
	'technicalcontact_email'        => 'webmaster@colby.edu',
	'timezone'                      => null,


	'secretsalt'                    => 'a43x8k07rk8798ejy0ap659dvyvh90mw',

	'auth.adminpassword'            => '1234',

	/*
	 * Set this options to true if you want to require administrator password to access the web interface
	 * or the metadata pages, respectively.
	 */
	'admin.protectindexpage'        => false,
	'admin.protectmetadata'         => false,

	'admin.checkforupdates'         => true,

	/*************************
	 | SESSION CONFIGURATION |
	 *************************/

	/*
	 * This value is the duration of the session in seconds. Make sure that the time duration of
	 * cookies both at the SP and the IdP exceeds this duration.
	 */
	'session.duration'              => 8 * ( 60 * 60 ), // 8 hours.

	/*
	 * Sets the duration, in seconds, data should be stored in the datastore. As the data store is used for
	 * login and logout requests, this option will control the maximum time these operations can take.
	 * The default is 4 hours (4*60*60) seconds, which should be more than enough for these operations.
	 */
	'session.datastore.timeout'     => ( 4 * 60 * 60 ), // 4 hours

	/*
	 * Sets the duration, in seconds, auth state should be stored.
	 */
	'session.state.timeout'         => ( 60 * 60 ), // 1 hour

	/*
	 * Option to override the default settings for the session cookie name
	 */
	'session.cookie.name'           => 'SimpleSAMLSessionID',

	/*
	 * Expiration time for the session cookie, in seconds.
	 *
	 * Defaults to 0, which means that the cookie expires when the browser is closed.
	 *
	 * Example:
	 *  'session.cookie.lifetime' => 30*60,
	 */
	'session.cookie.lifetime'       => 0,

	/*
	 * Limit the path of the cookies.
	 *
	 * Can be used to limit the path of the cookies to a specific subdirectory.
	 *
	 * Example:
	 *  'session.cookie.path' => '/simplesaml/',
	 */
	'session.cookie.path'           => '/',

	/*
	 * Cookie domain.
	 *
	 * Can be used to make the session cookie available to several domains.
	 *
	 * Example:
	 *  'session.cookie.domain' => '.example.org',
	 */
	'session.cookie.domain'         => '.lndo.site',

	/*
	 * Set the secure flag in the cookie.
	 *
	 * Set this to TRUE if the user only accesses your service
	 * through https. If the user can access the service through
	 * both http and https, this must be set to FALSE.
	 */
	'session.cookie.secure'         => false,

	/*
	 * Options to override the default settings for php sessions.
	 */
	'session.phpsession.cookiename' => 'SimpleSAML',
	'session.phpsession.savepath'   => null,
	'session.phpsession.httponly'   => true,

	/*
	 * Option to override the default settings for the auth token cookie
	 */
	'session.authtoken.cookiename'  => 'SimpleSAMLAuthToken',

	/*
	 * Options for remember me feature for IdP sessions. Remember me feature
	 * has to be also implemented in authentication source used.
	 *
	 * Option 'session.cookie.lifetime' should be set to zero (0), i.e. cookie
	 * expires on browser session if remember me is not checked.
	 *
	 * Session duration ('session.duration' option) should be set according to
	 * 'session.rememberme.lifetime' option.
	 *
	 * It's advised to use remember me feature with session checking function
	 * defined with 'session.check_function' option.
	 */
	'session.rememberme.enable'     => false,
	'session.rememberme.checked'    => false,
	'session.rememberme.lifetime'   => ( 14 * 86400 ),

	/*
	 * Custom function for session checking called on session init and loading.
	 * See docs/simplesamlphp-advancedfeatures.txt for function code example.
	 *
	 * Example:
	 *   'session.check_function' => array('sspmod_example_Util', 'checkSession'),
	 */



);
