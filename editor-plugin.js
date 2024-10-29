(function() {
function removequotes(str){return (str=str.replace(/["']{1}/gi,""));} 
    tinymce.create('tinymce.plugins.BrettsYouTube', {
        init : function(ed, url) {
            ed.addButton('brettsyoutube', {
                title : 'AudioTube',
                image : url+'/button.jpg',
                onclick : function() {
                    idPattern = /(?:(?:[^v]+)+v.)?([^&=]{11})(?=&|$)/;
                    var vidId = prompt("AudioTube", "YOUTUBE_URL");
                    var caption = prompt("AudioTube Caption (Optional)", "CAPTION");
                    var m = idPattern.exec(vidId);
                    if (m != null && m != 'undefined') {
                        ed.execCommand('mceInsertContent', false, '[audiotube url="http://www.youtube.com/watch?v='+m[1]+'" ');
                        if (caption != null && caption != 'undefined' && caption != 'CAPTION')
                        	caption = removequotes(caption);
	                        ed.execCommand('mceInsertContent', false, 'caption="'+caption+'"');
	                	}
	                	ed.execCommand('mceInsertContent', false, ']');
                	}
            });
        },
        createControl : function(n, cm) {
            return null;
        },
        getInfo : function() {
            return {
                longname : "AudioTube Shortcode",
                author : 'David Angel',
                authorurl : 'http://davidangel.net/',
                infourl : 'http://davidangel.net/',
                version : "0.1"
            };
        }
    });
    tinymce.PluginManager.add('brettsyoutube', tinymce.plugins.BrettsYouTube);
})();