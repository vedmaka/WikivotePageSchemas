<?php
/**
* Initialization file for the WikivotePageSchemas extension.
*
* @file WikivotePageSchemas.php
* @ingroup WikivotePageSchemas
*
* @licence GNU GPL v3
* @author Wikivote! ltd < http://wikivote.ru >
*/

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

if ( version_compare( $wgVersion, '1.17', '<' ) ) {
	die( '<b>Error:</b> This version of WikivotePageSchemas requires MediaWiki 1.17 or above.' );
}

/* Credits page */
$wgExtensionCredits['specialpage'][] = array(
	'path' => __FILE__,
	'name' => 'WikivotePageSchemas',
	'version' => '0.1',
	'author' => 'Wikivote! ltd.',
	'url' => '',
	'descriptionmsg' => 'wikivotepageschemas-desc',
);

/* Resource modules */
$wgResourceModules['ext.WikivotePageSchemas.main'] = array(
	'localBasePath' => dirname( __FILE__ ) . '/',
	'remoteExtPath' => 'WikivotePageSchemas/',
	'group' => 'ext.WikivotePageSchemas',
	'scripts' => '',
	'styles' => ''
);

/* Message Files */
$wgExtensionMessagesFiles['WikivotePageSchemas'] = dirname( __FILE__ ) . '/WikivotePageSchemas.i18n.php';

/* Autoload classes */
$wgAutoloadClasses['WikivotePageSchemas'] = dirname( __FILE__ ) . '/WikivotePageSchemas.class.php';
#$wgAutoloadClasses['WikivotePageSchemasHooks'] = dirname( __FILE__ ) . '/WikivotePageSchemas.hooks.php';

/* Rights */
#$wgAvailableRights['example_rights'] = '';

/* Permissions */
#$wgGroupPermissions['sysop']['example_rights'] = true;

/* Special Pages */
#$wgSpecialPages['WikivotePageSchemas'] = 'WikivotePageSchemasSpecial';

/* Hooks */
#$wgHooks['example_hook'][] = 'WikivotePageSchemasHooks::onExampleHook';
$wgHooks['PageSchemasRegisterHandlers'][] = 'WikivotePageSchemas::registerClass';