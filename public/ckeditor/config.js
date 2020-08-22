/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.width  = 650;
	config.height  = 400;
        config.format_tags = 'pre;address;p;h1;h2;h3;h4;h5;h6;pre;div';             
        config.enterMode=2;   // удаляем тег <p>
        
       
};
