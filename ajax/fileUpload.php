?php

    
    if ($_FILES["bestand"]["type"] == "image/jpeg" || $_FILES["bestand"]["type"] == "image/png"){
          $bestandsnaam = $_FILES["bestand"]["name"];
          move_uploaded_file($_FILES["bestand"]["tmp_name"], "./uploads/" . $bestandsnaam);

?>