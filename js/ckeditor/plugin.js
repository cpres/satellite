/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @file Sample plugin for CKEditor.
 */
( function() {
  CKEDITOR.plugins.add( 'satellite',
  {
    init: function( editor )
    {
      var me = this;
      CKEDITOR.dialog.add( 'MyDialog', function( editor ) {
        return {
          title : 'Satellite CKEditor',
          minWidth : 400,
          minHeight : 200,
          contents : [
            {
              id : 'firstTab',
              label : 'My dialog',
              title : 'My dialog',
              elements :
              [
                {
                  id : 'txtField1',
                  type : 'textarea',
                  rows : 9,
                  label : 'Type something here'
                }
              ]
            }
          ],
          onOk : function() {
            var editor = this.getParentEditor();
            var content = this.getValueOf( 'firstTab', 'txtField1' );
            if ( content.length > 0 ) {
              editor.insertHtml( content );
            }
          }
        };
      });

      editor.addCommand( 'MyDialog', new CKEDITOR.dialogCommand( 'MyDialog' ) );

      editor.ui.addButton( 'MyButton',
      {
        label: 'My CKEditor button',
        command: 'MyDialog',
        icon: this.path + '../tinymce/gallery.png'
      } );
    }
  } );
} )();
