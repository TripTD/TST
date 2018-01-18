<?php
  require_once("config.php");

  $conn = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

  if ( $conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  function img( $fl_extn,$fl_temp) {
      $file_path = 'Images/'.$fl_extn;
      move_uploaded_file($fl_temp,$file_path);
      $time = time();
      $query = "INSERT INTO products (imeg) VALUES($file_path)";
      $stmt= $conn->prepare($query);
      $stmt->execute();
  }

    if ( !isset($_SESSION["translate"])) {
        $language = LANG_ENGLISH;
        $_SESSION["translate"] = 0;
    } elseif ( $_SESSION["translate"] == 1) {
        $language = LANG_FRENCH;
    }
    function t($string, $args = array(), $langcode = NULL) {
        global $language, $translation;
        $langcode = isset($langcode) ? $langcode : $language;
        if ( isset($translation[$langcode][$string]) ) {
                $string = $translation[$langcode][$string];
        }
        if ( empty($args) ) {
                return $string;
        } else {
            foreach ( $args as $key => $value ) {
                switch ( $key[0] ) {
                    case '!':
                    case '@':
                    case '%':
                    default: $args[$key] = $value; break;
                }
            }

            return strtr($string, $args);
        }
    }
    $translation["fr"] = array(
        "Username" => "Nom d'utilisateur",
        "Name" => "Nom",
        "Add Item" => "Ajouter l'objet",
        "Remove Item" => "Retirer l'objet",
        "Contact details" => "Details du contact",
        "Comments" => "Commentaires ",
        "Index" => "Index",
        "Password" => "Mot de passe",
        "Go to Cart" => "Aller au panier",
        "Log in" => "S'identifier",
        "Log out" => "Se deconnecter",
        "Edit Item" => "modifier l'article",
        "Go to Market" => "Aller au marche",
        "Title" => "Titre",
        "Description" => "La description",
        "Price" => "Prix",
        "Image" => "Image",
        "Language preference" => "Preference de langue",
        "You have not selected items yet!" => "Vous n'avez pas encore selectionne d'elements!",
        "Product editing" => "Edition de produit",
            );



?>
