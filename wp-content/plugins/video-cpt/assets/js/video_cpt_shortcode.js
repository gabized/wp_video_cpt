jQuery( document ).ready(function() {

tinymce.create("tinymce.plugins.video_shortcode_button_plugin", {

        //url is our plg directory
        init : function(ed, url) {

            //create a json file from phpvariable passed
            phpObj = JSON.parse(my_plugin.videos);
            phpObjSize = (phpObj.length) - 1 ;
            videos = [];
            console.log(phpObjSize);
            for(var i in phpObj){
                console.log(i);
                 videos.push({text: phpObj[i].post_title,value: phpObj[i].ID });
            }


            console.log(videos);
            //add new button
            ed.addButton("video_shortcode_button", {
                text : "Add video",
                 cmd : "command_one"
            });



            //button functionality.
            ed.addCommand("command_one", function() {
                    ed.windowManager.open({
                        title: 'Select video and border color and width',
                        body: [
                                {
                                    type: 'listbox',
                                    name : 'selectVideo',
                                    label: 'choose video',
                                    values : videos,
                                },
                                {
                                    type: 'colorpicker',
                                    name: 'bcolor',
                                    label: 'choose color',
                                },
                                {
                                    type: 'textbox',
                                    name: 'bwidth',
                                    label: 'insert width in pixels',
                                }
                        ],

                        onsubmit: function( e ) {
                            ed.insertContent( '[prefix_video id="' + e.data.selectVideo + '" border_color="' + e.data.bcolor + '" border_width="' + e.data.bwidth + '"]');
                        }
                    });

            });

        },

        createControl : function(n, cm) {
            return null;
        },

    });

    tinymce.PluginManager.add("video_shortcode_button_plugin", tinymce.plugins.video_shortcode_button_plugin);

});
