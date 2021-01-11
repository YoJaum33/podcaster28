<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>📻 podcaster</title>
  <meta name="description" content="que personne ne fasse la blaque avec la pod'castor 🦫">
</head>
<body><pre><?php

  // séparer ses identifiants et les protéger, une bonne habitude à prendre
  include "the-podcaster.dbconf.php";

  try {

    // instancie un objet $connexion à partir de la classe PDO
    $connexion = new PDO(DB_DRIVER . ":host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_LOGIN, DB_PASS, DB_OPTIONS);

    // Requête de sélection 01
    $requete = "SELECT * FROM `podcasts`";
    $prepare = $connexion->prepare($requete);
    $prepare->execute();
    $resultat = $prepare->fetchAll();
    print_r([$requete, $resultat]); // debug & vérification

    // Requête de sélection 02
    $requete = "SELECT *
                FROM `podcasts`
                WHERE `podcasts_id` = :podcasts_id"; // on cible l'épisode dont l'id est ...
    $prepare = $connexion->prepare($requete);
    $prepare->execute(array(":podcasts_id" => 2)); // on cible l'épisode dont l'id est 2
    $resultat = $prepare->fetchAll();
    print_r([$requete, $resultat]); // debug & vérification

    // Requête d'insertion
    $requete = "INSERT INTO `podcasts` (`podcasts_name`, `podcasts_description`, `podcasts_url`, `episode_podcast_id`)
                VALUES (:podcasts_name, :podcasts_description, :podcasts_url);";
    $prepare = $connexion->prepare($requete);
    $prepare->execute(array(
      ":podcasts_name" => "Mécaniques du journalisme",
      ":podcasts_description" => "Après Mécaniques du complotisme qui révélait les ressorts et les modes de propagation des rumeurs complotistes, France Culture plonge dans les coulisses de grandes enquêtes contemporaines pour mettre au jour la réalité du travail des journalistes, loin des idées toutes faites.",
      ":podcasts_url" => "https://radiofrance-podcast.net/podcast09/rss_21810.xml",
    ));
    $resultat = $prepare->rowCount(); // rowCount() nécessite PDO::MYSQL_ATTR_FOUND_ROWS => true
    $lastInsertedEpisodeId = $connexion->lastInsertId(); // on récupère l'id automatiquement créé par SQL
    print_r([$requete, $resultat, $lastInsertedpodcastsId]); // debug & vérification

    // Requête de modification
    $requete = "UPDATE `podcasts`
                SET `podcasts_description` = :podcasts_description
                WHERE `podcasts_id` = :podcasts_id;";
    $prepare = $connexion->prepare($requete);
    $prepare->execute(array(
      ":podcasts_id"   => 3,
      ":podcasts_description" => "Dans Le gras c'est la vie !"
    ));
    $resultat = $prepare->rowCount();
    print_r([$requete, $resultat]); // debug & vérification

    // Requête de suppression
    $requete = "DELETE FROM `podcasts`
                WHERE ((`podcasts_id` = :podcasts_id));";
    $prepare = $connexion->prepare($requete);
    $prepare->execute(array($lastInsertedPodcastsId)); // on lui passe l'id tout juste créé
    $resultat = $prepare->rowCount();
    print_r([$requete, $resultat, $lastInsertedPodcastsId]); // debug & vérification

  } catch (PDOException $e) {

    // en cas d'erreur, on récup et on affiche, grâce à notre try/catch
    exit("❌🙀💀 OOPS :\n" . $e->getMessage());

  }

?></pre></body>
</html>