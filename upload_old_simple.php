<?php
      ob_start();
      session_start();
      $path = "http://videoapi.edubrandmedia.com/files/";
      
      // Configuration - Your Options
      $allowed_filetypes = array('.mp4','.3gp','.webm'); 
      $max_filesize = 104857600;
      $upload_path = 'files/'; 
 
      $filename = $_FILES['userfile']['name']; // Get the name of the file (including file extension).
      $ext = substr($filename, strpos($filename,'.'), strlen($filename)-1); // Get the extension from the filename.
     

      // Check if the filetype is allowed, if not DIE and inform the user.
      
      if(!in_array($ext,$allowed_filetypes));
         die('The file you attempted to upload is not allowed.');
      
     

      // Now check the filesize, if it is too large then DIE and inform the user.
      if(filesize($_FILES['userfile']['tmp_name']) > $max_filesize)
         die('The file you attempted to upload is too large.');
    
      // Check if we can upload to the specified path, if not DIE and inform the user.
      if(!is_writable($upload_path))
         die('You cannot upload to the specified directory, please CHMOD it to 777.');
      


      $fileold=$_FILES['userfile']['tmp_name'];
      echo $filename;
      


      // Upload the file to your specified path.
      if(move_uploaded_file($_FILES['userfile']['tmp_name'],$upload_path.$filename)){
         $_SESSION['title'] = $_POST['title'];
         $_SESSION['desc'] = $_POST['desc'];
         echo 'Your file upload was successful, view the file <a href="' . $upload_path . $filename . '" title="Your File">here</a>'; // It worked.
      }
         else{
               echo 'There was an error during the file upload.  Please try again.'; // It failed :(.
         }
    
?>


<form action="test.php" method="post"><br/>
<input type="hidden" name="video" value="<?php echo $filename; ?>">
<input type="checkbox" name="check_list[]" value="facebook"><label>Facebook</label><br/>
<input type="checkbox" name="check_list[]" value="youtube"><label>Youtube</label><br/>
<input type="submit" />
</form>
