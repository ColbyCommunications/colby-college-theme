<?php

$config = array(
	'admin'      => array(
		// The default is to use core:AdminPassword, but it can be replaced with
		// any authentication source.
		'core:AdminPassword',
	),

	'default-sp' => array(
		'saml:SP',
		'entityID' => 'https://colby.lndo.site/simplesaml/saml2/idp/metadata.php/default-sp',
		'idp'      => 'http://www.okta.com/exkqw4m28ezS0DYyx357',

	),
);
