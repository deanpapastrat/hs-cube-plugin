( function() {
    tinymce.PluginManager.add( 'hscube_button', function( editor, url ) {

        // Add a button that opens a window
        editor.addButton( 'hscube_tinymce_button_key', {
            image: url + '/hscube-logo.jpg',
            onclick: function() {
                // Open window
                editor.windowManager.open( {
                    title: 'High School Cube | Choose Embed Code',
                    body: [{
                        type: 'listbox', 
                        name: 'hscube_type', 
                        label: 'Embed Type', 
                        'values': [
                            {text: 'Video Player', value: 'video'},
                            {text: 'Scoreboard', value: 'scoreboard'},
                        ]
                    },
                    {
                        type: 'textbox',
                        name: 'hscube_url',
                        label: 'URL'
                    },
                    {
                        type: 'textbox',
                        name: 'hscube_width',
                        label: 'Width (add "px" or "%")'
                    },
                    {
                        type: 'textbox',
                        name: 'hscube_height',
                        label: 'Height (add "px" or "%")'
                    }],
                    onsubmit: function( e ) {
                        editor.insertContent( '[hscube-' + e.data.hscube_type + ' url="' + e.data.hscube_url + '" width="' + e.data.hscube_width + '" height="' + e.data.hscube_height + '" ]');
                    }
                });
            }

        } );

    } );

} )();
