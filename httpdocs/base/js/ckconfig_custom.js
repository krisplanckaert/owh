// custom ck editor configuration
// ------------------------------------

CKEDITOR.editorConfig = function( config )
{
   config.language = 'en';
   config.fullPage = false;
   //config.height = 600;
   config.skin = 'v2';
   //config.SkinPath = config.BasePath + 'skins/silver/' ; //default, office2003,silver
   //config.forceEnterMode = true;
  // config.EnterMode          = 'br';
  // config.ShiftEnterMode     = 'p'; 
//   config.toolbar = [
//			{ name: 'basicstyles', items : [ 'Source','-','Bold','Italic','Strike' ] },
//			{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
//			{ name: 'paragraph', items : [ 'NumberedList','BulletedList' ] },
//			{ name: 'tools', items : [ 'About' ] }
//		];
   config.toolbar = 'custom01';
  // config.uiColor = '#AADC6E';
//config.toolbar = 'Basic';
config.EnterMode          = CKEDITOR.ENTER_BR;
config.ShiftEnterMode     = 'p';

config.toolbar_custom01 = [
                               	['Source','-','Bold','Italic','Underline','-','OrderedList','UnorderedList','-','Link','Unlink','Anchor','-','Rule','-','Cut','Copy','Paste','PasteText','PasteWord'],
                               	['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
                               	'/',
                               	['Image','Table','Rule','SpecialChar','PageBreak','-'],
                               	['FontName','FontSize'],
                               	['TextColor','BGColor']
                               	//,['FitWindow','ShowBlocks','-','About']	
                               ] ;
/*
config.ToolbarSets["custom_01"] = [
	['Templates','Preview','NewPage','-','Bold','Italic','Underline','-','OrderedList','UnorderedList','-','Link','Unlink','Anchor','-','Rule','-','Cut','Copy','Paste','PasteText','PasteWord'],
	['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
	'/',
	['Image','Table','Rule','SpecialChar','PageBreak','-'],
	['FontName','FontSize'],
	['TextColor','BGColor']
	//,['FitWindow','ShowBlocks','-','About']	
] ;


config.ToolbarSets["custom_02"] = [
	['Templates','Source','NewPage','Preview','-','Bold','Italic','Underline','-','OrderedList','UnorderedList','-','Link','Unlink','Anchor','-','Rule','-','Cut','Copy','Paste','PasteText','PasteWord'],
	['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
	'/',
	['Image','Table','Rule','SpecialChar','PageBreak','-'],
	['FontName','FontSize'],
	['TextColor','BGColor']
	//,['FitWindow','ShowBlocks','-','About']	
] ;

config.ToolbarSets["custom_03"] = [
	['Templates','Preview','FitWindow','-','Source','DocProps','-','NewPage','ShowBlocks'],
	['Cut','Copy','Paste','PasteText','PasteWord','-','Print','SpellCheck'],
	['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
	'/',
	['Image','Table','Rule','SpecialChar','PageBreak'],
	['Form','Checkbox','Radio','TextField','Textarea','Select','Button','ImageButton','HiddenField'],
	'/',
	['Bold','Italic','Underline','StrikeThrough','-','Subscript','Superscript'],
	['OrderedList','UnorderedList','-','Outdent','Indent','Blockquote','CreateDiv'],
	['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
	['Link','Unlink','Anchor'],	
	'/',
	['Style','FontFormat','FontName','FontSize'],
	['TextColor','BGColor']// No comma for the last row.
] ;
*/
    /*
config.AutoDetectLanguage = false ;
config.Height    		 = "600" ;
config.DefaultLanguage    = "nl" ;
config.EnterMode          = 'br';
config.ShiftEnterMode     = 'p';
config.FullPage	         = false ; //true = edit full html page & keep custom styles, title, html, doctype, etc
config.DocType = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
config.ToolbarSets["custom_01"] = [
	['Templates','Preview','NewPage','-','Bold','Italic','Underline','-','OrderedList','UnorderedList','-','Link','Unlink','Anchor','-','Rule','-','Cut','Copy','Paste','PasteText','PasteWord'],
	['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
	'/',
	['Image','Table','Rule','SpecialChar','PageBreak','-'],
	['FontName','FontSize'],
	['TextColor','BGColor']
	//,['FitWindow','ShowBlocks','-','About']	
] ;


config.ToolbarSets["custom_02"] = [
	['Templates','Source','NewPage','Preview','-','Bold','Italic','Underline','-','OrderedList','UnorderedList','-','Link','Unlink','Anchor','-','Rule','-','Cut','Copy','Paste','PasteText','PasteWord'],
	['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
	'/',
	['Image','Table','Rule','SpecialChar','PageBreak','-'],
	['FontName','FontSize'],
	['TextColor','BGColor']
	//,['FitWindow','ShowBlocks','-','About']	
] ;

config.ToolbarSets["custom_03"] = [
	['Templates','Preview','FitWindow','-','Source','DocProps','-','NewPage','ShowBlocks'],
	['Cut','Copy','Paste','PasteText','PasteWord','-','Print','SpellCheck'],
	['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
	'/',
	['Image','Table','Rule','SpecialChar','PageBreak'],
	['Form','Checkbox','Radio','TextField','Textarea','Select','Button','ImageButton','HiddenField'],
	'/',
	['Bold','Italic','Underline','StrikeThrough','-','Subscript','Superscript'],
	['OrderedList','UnorderedList','-','Outdent','Indent','Blockquote','CreateDiv'],
	['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
	['Link','Unlink','Anchor'],	
	'/',
	['Style','FontFormat','FontName','FontSize'],
	['TextColor','BGColor']// No comma for the last row.
] ;

config.SkinPath = config.BasePath + 'skins/silver/' ; //default, office2003,silver

//config.ToolbarLocation = 'None'; //disable toolbar
*/


};