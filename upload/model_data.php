<?php header('Access-Control-Allow-Origin: *'); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Upload video wizard</title>
    <link href='/css/bootstrap.min.css' rel='stylesheet'>
    <link rel="icon" type="image/png" href="http://www.linkbazaar.com/images/logo.png" />
    <meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta http-equiv='content-type' content='text/html; charset=UTF-8'>
    <link rel="stylesheet" href="/upload/css/style.css">
    <!-- blueimp Gallery styles -->
    <link rel="stylesheet" href="http://blueimp.github.io/Gallery/css/blueimp-gallery.min.css">
    <!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
    <link rel="stylesheet" href="/upload/css/jquery.fileupload.css">
    <link rel="stylesheet" href="/upload/css/jquery.fileupload-ui.css">
    <!-- CSS adjustments for browsers with JavaScript disabled -->
    <noscript><link rel="stylesheet" href="/upload/css/jquery.fileupload-noscript.css"></noscript>
    <noscript><link rel="stylesheet" href="/upload/css/jquery.fileupload-ui-noscript.css"></noscript>
    <style type="text/css">
    .form-control{
        height: 45px;
    }
    </style>
    
</head>
<body>
    <div>
    <h1 style="margin-left:50px;">Hello,<?php echo $_SESSION['Username'];?></h1>
    <a href="/login/logout.php" style="margin-left:50px;float:left;">Logout</a><br/>
    </div>
    <a href="#" style="margin-left:100px;clear:both;text-align:center" data-toggle="modal" data-target="#myModal">Add Videos</a>
</body>
</html>


<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="/upload/js/vendor/jquery.ui.widget.js"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="//blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
<script type="text/javascript" src="/js/jquery.cookie.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="//blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="//blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<!-- blueimp Gallery script -->
<script src="//blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="/upload/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="/upload/js/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="/upload/js/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="/upload/js/jquery.fileupload-image.js"></script>
<!-- The File Upload audio preview plugin -->
<script src="/upload/js/jquery.fileupload-audio.js"></script>
<!-- The File Upload video preview plugin -->
<script src="/upload/js/jquery.fileupload-video.js"></script>
<!-- The File Upload validation plugin -->
<script src="/upload/js/jquery.fileupload-validate.js"></script>
<!-- The File Upload user interface plugin -->
<script src="/upload/js/jquery.fileupload-ui.js"></script>
<!-- The main application script -->
<script src="/upload/js/main.js"></script>
<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
<!--[if (gte IE 8)&(lt IE 10)]>
<script src="/upload/js/cors/jquery.xdr-transport.js"></script>
<![endif]-->

    <!-- Modal -->
    <div class='modal fade' id='myModal' role='dialog'>
        <div class='modal-dialog modal-lg'>
            <!-- Modal content-->
            <div class='modal-content'>
                <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal'>&times;</button>
                    <h4 class='modal-title'>Modal Header</h4>
                </div>
                <div class='modal-body'>
                    <form id="fileupload" action="" method="POST" enctype="multipart/form-data">
                        <!-- Redirect browsers with JavaScript disabled to the origin page -->
                        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                        <div class="row fileupload-buttonbar">
                            <div class="col-lg-7">
                                <!-- The fileinput-button span is used to style the file input field as button -->
                                <span class="btn btn-success fileinput-button">
                                    <i class="glyphicon glyphicon-plus"></i>
                                    <span>Add files...</span>
                                    <input type="file" name="files[]">
                                </span>
                                <button type="submit" class="btn btn-primary start">
                                    <i class="glyphicon glyphicon-upload"></i>
                                    <span>Start upload</span>
                                </button>
                                <button type="reset" class="btn btn-warning cancel">
                                    <i class="glyphicon glyphicon-ban-circle"></i>
                                    <span>Cancel upload</span>
                                </button>
                                <button type="button" class="btn btn-danger delete">
                                    <i class="glyphicon glyphicon-trash"></i>
                                    <span>Delete</span>
                                </button>
                                <input type="checkbox" class="toggle">

                                <!-- The global file processing state -->
                                <span class="fileupload-process"></span>
                                <br/><br/><br/><br/>
                                <div style="padding-left:20px" class="uploadbox">
                                    <strong> Upload To: </strong><br/>
                                    <input id='fb_site' type="checkbox" name=site[] value='fb'><img src="/images/button-facebook.png" height="22px" /><br/>
                                    <input id='yt_site' type="checkbox" name=site[] value='yt'><img src="/images/youtube.png" height="22px" /><br/>
                                </div>
                            </div>
                            <!-- The global progress state -->
                            <div class="col-lg-5 fileupload-progress fade">
                                <!-- The global progress bar -->
                                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                                </div>
                                <!-- The extended global progress state -->
                                <div class="progress-extended">&nbsp;</div>
                            </div>
                        </div>
                        <br/><br/>
                        <!-- The table listing the files available for upload/download -->
                        <table role="presentation" class="table table-striped"><tbody class="files">
                           <label style="padding-left:20px">Title:</label><input type="text" id='title' name="title">
                           <label style="padding-left:40px;">Description:</label><input type="text" id="desc" name="desc" >
                           <br/><br/><br/>
                           <!-- <tr class="template-download fade in">
                                <td>
                                    <span class="preview"><video controls="" src="http://localhost/upload/server/php/files/12623269_1556799504641932_1582560992_n%20%281%29.mp4"></video>
                                    </span>
                                </td>
                                <td>
                                    <p class="name">
                                        <a href="http://localhost/upload/server/php/files/12623269_1556799504641932_1582560992_n%20%281%29.mp4" title="12623269_1556799504641932_1582560992_n (1).mp4" download="12623269_1556799504641932_1582560992_n (1).mp4">12623269_1556799504641932_1582560992_n (1).mp4</a>
                                        
                                    </p>
                                    
                                </td>
                                <td>
                                    <span class="size">6.45 MB</span>
                                </td>
                                <td>
                                    
                                    <button class="btn btn-danger delete" data-type="DELETE" data-url="http://localhost/upload/server/php/index.php?file=12623269_1556799504641932_1582560992_n%20%281%29.mp4">
                                        <i class="glyphicon glyphicon-trash"></i>
                                        <span>Delete</span>
                                    </button>
                                    <input name="delete" value="1" class="toggle" type="checkbox">
                                    
                                </td>
                            </tr>
                            -->
                        </tbody></table>
                    </form>
                </div>
                <div class='modal-footer'>
                  <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                </div>
            </div>
        </div>
    </div>

<script id="template-upload" type="text/x-tmpl">

    {% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td>
            <p class="size">Processing...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
            <button class="btn btn-primary start" disabled>
                <i class="glyphicon glyphicon-upload"></i>
                <span>Start</span>
            </button>
            {% } %}
            {% if (!i) { %}
            <button class="btn btn-warning cancel">
                <i class="glyphicon glyphicon-ban-circle"></i>
                <span>Cancel</span>
            </button>
            {% } %}
        </td>
    </tr>
    {% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        <td>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                {% } %}
            </span>
        </td>
        <td>
            <p class="name">
                {% if (file.url) { %}
                <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                {% } else { %}
                <span>{%=file.name%}</span>
                {% } %}
            </p>
            {% if (file.error) { %}
            <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
            {% if (file.deleteUrl) { %}
            <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                <i class="glyphicon glyphicon-trash"></i>
                <span>Delete</span>
            </button>
            <input type="checkbox" name="delete" value="1" class="toggle">
            {% } else { %}
            <button class="btn btn-warning cancel">
                <i class="glyphicon glyphicon-ban-circle"></i>
                <span>Cancel</span>
            </button>
            {% } %}
        </td>
    </tr>
    {% } %}
</script>

<script type="text/javascript">
    if(window.opener){
        self.close();
    }
    function popupCenter(url, title, w, h) {
        var left = (screen.width/2)-(w/2);
        var top = (screen.height/2)-(h/2);
        popup = window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
        return popup;
    } 

    $('#fb_site').change(function() {
        if($(this).is(":checked")) {
            $.post("../validity_fb.php",
            {
                check:"data"
            },
            function(data,status){
                if(data==0){
                   var y = confirm("Please authorize us to post to facebook");
                   if(y){
                    popupCenter('/fb_authorize.php', 'Facebook Login',450,450);
                   }
                }
            });
        }       
    });
    $('#yt_site').change(function() {
        if($(this).is(":checked")) {
            $.post("../validity_yt.php",
            {
                check:"data"
            },
            function(data,status){
                if(data==0){
                   var y = confirm("Please authorize us to post to youtube");
                   if(y){
                    popupCenter('/google_authorize.php', 'Google Token',450,450);
                   }
                }
            });
        }       
    });
        
    </script>