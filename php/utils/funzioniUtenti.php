<?php
require_once "SingletonDB.php";
require_once "filtraTestoInglese.php";
require_once "controlli.php";

class Users
{
    function insert()
    {
        if (
            isset($_POST["name_register"]) &&
            isset($_POST["username_register"]) &&
            isset($_POST["password_register"]) &&
            isset($_POST["email_register"]) &&
            isset($_POST["surname_register"]) &&
            isset($_POST["pass_register_confirm"])
        ) {

			/*pulisci($_POST["username_register"])
            pulisci($_POST["name_register"]);
            pulisci($_POST["surname_register"]);
            pulisci($_POST["email_register"]);*/

			$username = $_POST["username_register"];
            $password = $_POST["password_register"];
            $name = $_POST["name_register"];
            $surname = $_POST["surname_register"];
            $email = $_POST["email_register"];

            $confirm_password = $_POST["pass_register_confirm"];

            $result = registerControls(
                $username,
                $name,
                $surname,
                $email,
                $password,
                $confirm_password
            );
            $hash = password_hash($password, PASSWORD_DEFAULT);
            if ($result == "OK") {
                $db = SingletonDB::getInstance();

                $query =
                    "INSERT INTO Utente(Username,Email,Nome,Cognome,Password) VALUES (?,?,?,?,?)";
                $preparedQuery = $db->getConnection()->prepare($query);
                $preparedQuery->bind_param(
                    "sssss",
                    $username,
                    $email,
                    $name,
                    $surname,
                    $hash
                );

                $_SESSION["a"] = $username;
                $_SESSION["b"] = $email;
                $_SESSION["c"] = $password;
                $preparedQuery->execute();
                $db->disconnect();
                $preparedQuery->close();
            }

            return $result;
        }
    }

    function search()
    {
        if (
            isset($_POST["username_login"]) &&
            isset($_POST["password_login"])
        ) {
			//pulisci($_POST["username_login"]);

			$username = $_POST["username_login"];
            $password = $_POST["password_login"];

            $result = loginControls($username, $password);

            if ($result == "OK") {
                $db = SingletonDB::getInstance();
                $query = "SELECT * FROM Utente WHERE Username=?";
                $preparedQuery = $db->getConnection()->prepare($query);
                $preparedQuery->bind_param("s", $username);

                $preparedQuery->execute();
                $resultCast = $preparedQuery->get_result();

                if ($resultCast->num_rows > 0) {
                    $row = $resultCast->fetch_assoc();
                    $hash = $row["Password"];
                    $v = password_verify($password, $hash);

                    if ($v) {
                        $_SESSION["a"] = $row["Username"];
                        $_SESSION["b"] = $row["Email"];
                        $_SESSION["c"] = $password;

                        $db2 = SingletonDB::getInstance();
                        $query2 =
                            "SELECT Username FROM Amministratori WHERE Username=?";
                        $preparedQuery2 = $db2
                            ->getConnection()
                            ->prepare($query2);
                        $preparedQuery2->bind_param("s", $row["Username"]);

                        $preparedQuery2->execute();
                        $resultCast2 = $preparedQuery2->get_result();

                        $db2->disconnect();
                        $preparedQuery2->close();
                        if ($resultCast2->num_rows > 0) {
                            $_SESSION["admin"] = true;
                        } else {
                            $_SESSION["admin"] = false;
                        }
                        $db->disconnect();
                        $preparedQuery->close();
                        header("location:home.php");
                    } else {
                        unset($_SESSION["a"]);
                        unset($_SESSION["b"]);
                        unset($_SESSION["c"]);
                        header(
                            "location:area_utenti.php?action=login_page&errorLogin=true"
                        );
                    }
                } else {
                    unset($_SESSION["a"]);
                    unset($_SESSION["b"]);
                    unset($_SESSION["c"]);
                    header(
                        "location:area_utenti.php?action=login_page&errorLogin=true"
                    );
                }
            }

            return $result;
        }
    }

    function searchRegistered($email, $username, $value)
    {
        $db = SingletonDB::getInstance();

        $queryE = "SELECT * FROM Utente WHERE Email=?";
        $preparedQueryE = $db->getConnection()->prepare($queryE);
        $preparedQueryE->bind_param("s", $email);
        $preparedQueryE->execute();
        $resultCastE = $preparedQueryE->get_result();
        $isDoubled = false;
        $isEmail = false;
        if ($value == 0) {
            if ($resultCastE->num_rows > $value) {
                $isEmail = true;
                $isDoubled = true;
            }
        } else {
            if ($resultCastE->num_rows == $value) {
                $row = $resultCastE->fetch_assoc();
                if ($row["Email"] == $_SESSION["b"]) {
                } else {
                    $isEmail = true;
                    $isDoubled = true;
                }
            } else {
                if ($resultCastE->num_rows > $value) {
                    $isEmail = true;
                    $isDoubled = true;
                }
            }
        }

        $db = SingletonDB::getInstance();
        $queryU = "SELECT * FROM Utente WHERE Username=?";
        $preparedQueryU = $db->getConnection()->prepare($queryU);
        $preparedQueryU->bind_param("s", $username);

        $preparedQueryU->execute();
        $resultCastU = $preparedQueryU->get_result();
        $isUser = false;
        if ($value == 0) {
            if ($resultCastU->num_rows > $value) {
                $isUser = true;
                $isDoubled = true;
            }
        } else {
            if ($resultCastU->num_rows == $value) {
                $row = $resultCastU->fetch_assoc();
                if ($row["Username"] == $_SESSION["a"]) {
                } else {
                    $isUser = true;
                    $isDoubled = true;
                }
            } else {
                if ($resultCastU->num_rows > $value) {
                    $isUser = true;
                    $isDoubled = true;
                }
            }
        }

        $preparedQueryU->close();
        $preparedQueryE->close();
        return [$isDoubled, $isUser, $isEmail];
    }

    function getProfile()
    {
        if (isset($_SESSION["a"])) {
            $db = SingletonDB::getInstance();

            $query =
                "SELECT Username, Nome,Cognome,Password,Email FROM Utente WHERE Username=? ";
            $preparedQuery = $db->getConnection()->prepare($query);
            $username = $_SESSION["a"];
            $preparedQuery->bind_param("s", $username);

            $preparedQuery->execute();
            $resultCast = $preparedQuery->get_result();
            $db->disconnect();
            $preparedQuery->close();
            $row = $resultCast->fetch_assoc();
            $home_content = file_get_contents(
                "../html/items/aggiorna_profilo.html"
            );

            if ($resultCast->num_rows > 0) {
                $home_content = str_replace(
                    "<USERNAME>",
                    $row["Username"],
                    $home_content
                );
                $home_content = str_replace(
                    "<NOME>",
                    $row["Nome"],
                    $home_content
                );
                $home_content = str_replace(
                    "<COGNOME>",
                    $row["Cognome"],
                    $home_content
                );
                $home_content = str_replace(
                    "<EMAIL>",
                    $row["Email"],
                    $home_content
                );
                $home_content = str_replace(
                    "<PASSWORD>",
                    $_SESSION["c"],
                    $home_content
                );
            }
            if (isset($_GET["error"])) {
                $error = $_GET["error"];

                if ($error == 3) {
                    $home_content = str_replace(
                        "<ERRORMESSAGE>",
                        "Email e username già registrati",
                        $home_content
                    );
                }

                if ($error == 2) {
                    $home_content = str_replace(
                        "<ERRORMESSAGE>",
                        "Username già registrato",
                        $home_content
                    );
                }

                if ($error == 1) {
                    $home_content = str_replace(
                        "<ERRORMESSAGE>",
                        "Email già registrato",
                        $home_content
                    );
                }

                unset($_GET["error"]);
            }

            if (isset($_SESSION["insertError"])) {
                $home_content = str_replace(
                    "<ERRORMESSAGE>",
                    $_SESSION["insertError"],
                    $home_content
                );
            }
            return $home_content;
        }
    }
    function deleteProfile()
    {
        $db = SingletonDB::getInstance();

        $query = "DELETE FROM Utente WHERE Username=?";
        if ($preparedQuery = $db->getConnection()->prepare($query)) {
            $preparedQuery->bind_param("s", $username);
            if (isset($_SESSION["a"])) {
                $username = $_SESSION["a"];
                unset($_SESSION["a"]);
                unset($_SESSION["b"]);
                unset($_SESSION["c"]);
                $preparedQuery->execute();
                $db->disconnect();
                $preparedQuery->close();
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    function changeProfile()
    {
        if (
            isset($_POST["username_profile"]) &&
            isset($_POST["name_profile"]) &&
            isset($_POST["email_profile"]) &&
            isset($_POST["surname_profile"]) &&
            isset($_SESSION["a"])
        ) {
			/*pulisci($_POST["username_profile"]);
            pulisci($_POST["name_profile"]);
            pulisci($_POST["surname_profile"]);
            pulisci($_POST["email_profile"]);*/

			$newusername = $_POST["username_profile"];
            $oldusername = $_SESSION["a"];
            $name = $_POST["name_profile"];
            $surname = $_POST["surname_profile"];
            $email = $_POST["email_profile"];

            $result = registerControls(
                $newusername,
                $name,
                $surname,
                $email,
                null,
                null
            );

            if ($result == "OK") {
                $db = SingletonDB::getInstance();

                $query =
                    "UPDATE Utente SET Username=?, Nome=?, Cognome=?,Email=? WHERE Username=?";
                if ($preparedQuery = $db->getConnection()->prepare($query)) {
                    $preparedQuery->bind_param(
                        "sssss",
                        $newusername,
                        $name,
                        $surname,
                        $email,
                        $oldusername
                    );

                    $preparedQuery->execute();

                    $db->disconnect();
                    $preparedQuery->close();
                    $_SESSION["a"] = $newusername;
                    $_SESSION["b"] = $email;
                } else {
                    header("location:500.php");
                    die();
                }
            }
            return $result;
        }
    }
    function checkPassword($passOld, $pass, $passConf)
    {
        if ($passOld != null && isset($pass) && isset($passConf)) {
            if ($passOld != $_SESSION["c"]) {
                return [false, 1];
            }

            $result = loginControls($_SESSION["a"], $pass);
            if ($result != "OK") {
                return [false, 2];
            }
            //in questo caso result sarà sempre OK dato che ho commentato la funzione dentro controls, mi dava errore idk why
            if (
                $pass == $passConf &&
                $passOld == $_SESSION["c"] &&
                $result == "OK"
            ) {
                return [true, 0];
            } else {
                return [false, 3];
            }
        }

        if ($passOld == null && isset($pass) && isset($passConf)) {
            $result = loginControls($_SESSION["a"], $pass);

            if ($pass != $passConf) {
                return [false, 3];
            }

            if ($pass == $_SESSION["c"] && $pass == $passConf) {
                return [true, 4];
            } else {
                return [false, 1];
            }
        }
    }

    function changePassword()
    {
        if (
            isset($_POST["password_profile"]) &&
            isset($_POST["password_profile_confirm"])
        ) {
            $username = $_SESSION["a"];
            $password = $_POST["password_profile"];
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $db = SingletonDB::getInstance();

            $query = "UPDATE Utente SET Password=? WHERE Username=?";

            if ($preparedQuery = $db->getConnection()->prepare($query)) {
                $preparedQuery->bind_param("ss", $hash, $username);
                $preparedQuery->execute();
                $db->disconnect();
                $preparedQuery->close();
                $_SESSION["c"] = $password;
            }
        }
    }

    function getHistory()
    {
        if (isset($_SESSION["a"])) {
            $db = SingletonDB::getInstance();
			$db->connect();
            $query = "
                SELECT pe1.Orario as orario, p1.ID, f1.Titolo, f1.SrcImg, pe1.Data, pe1.NumeroSala, p1.NumeroPersone
                FROM Prenotazione p1,Film f1,Proiezione pe1
                WHERE p1.IDProiezione=pe1.ID AND pe1.IDFilm=f1.ID AND p1.UsernameUtente=? ORDER BY p1.ID DESC";

            $preparedQuery = $db->getConnection()->prepare($query);

            $username = $_SESSION["a"];
            $preparedQuery->bind_param("s", $username);

            $preparedQuery->execute();
            $result = $preparedQuery->get_result();
            $db->disconnect();
            $preparedQuery->close();


			$tot = "";
            $home_content = file_get_contents(
                "../html/items/storico_profilo.html"
            );
            $content = file_get_contents("../html/items/card_prenotazione.html");

			$today = new DateTime();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $content = file_get_contents(
                        "../html/items/card_prenotazione.html"
                    );


                    $content = str_replace("<CODICE>", $row["ID"], $content);

					$content = str_replace(
                        "<ELIMINA-PRENOTAZ>",
                        ($today < new DateTime($row["Data"])) ?
						'<a href="../php/area_utenti.php?action=deleteReservation&codice='. $row["ID"] . '" class="reservation_link btn-storico" '.
						'title="Elimina prenotazione" aria-label="Elimina prenotazione" >Elimina</a>' : '',
                        $content
                    );


                    $content = str_replace(
                        "<TITOLO>",
                        filtraTestoInglese($row["Titolo"]),
                        $content
                    );
                    $content = str_replace(
                        "<PERSONE>",
                        $row["NumeroPersone"],
                        $content
                    );

                    $content = str_replace(
                        "<ORA>",
                        $row["orario"],
                        $content
                    );

					$content = str_replace(
						"<DATA>",
						$row["Data"],
						$content
					);

					$content = str_replace(
                        "<SALA>",
                        $row["NumeroSala"],
                        $content
                    );

                    $tot = $tot . $content;
                }


            }
			$home_content = str_replace(
                    "<CARD-RESERVATION>",
                    ($tot != "") ? $tot : "<p> Non ci sono prenotazioni! </p>",
                    $home_content
            );
            return $home_content;
        }
    }

    function viewReservation($codice)
    {
        if (isset($codice)) {
            $db = SingletonDB::getInstance();
            $query = "
    			SELECT pe1.Orario as orario, p1.ID, f1.Titolo, f1.SrcImg, pe1.Data, pe1.NumeroSala, p1.NumeroPersone,pe1.IDFilm
    			FROM Prenotazione p1,Film f1,Proiezione pe1
    			WHERE p1.IDProiezione=pe1.ID AND pe1.IDFilm=f1.ID AND p1.UsernameUtente=? AND p1.ID=? ";

            $preparedQuery = $db->getConnection()->prepare($query);

            $username = $_SESSION["a"];
            $preparedQuery->bind_param("si", $username, $codice);
            $preparedQuery->execute();
            $result = $preparedQuery->get_result();
            $db->disconnect();
            $preparedQuery->close();

            $home_content = file_get_contents(
                "../html/prenotazione.html"
            );

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $home_content = str_replace(
                        "<CODICE>",
                        $row["ID"],
                        $home_content
                    );
                    $home_content = str_replace(
                        "<TITOLO>",
                        filtraTestoInglese($row["Titolo"]),
                        $home_content
                    );
                    $home_content = str_replace(
                        "<PERSONE>",
                        $row["NumeroPersone"],
                        $home_content
                    );

                    $home_content = str_replace(
                        "<ORA>",
                        $row["orario"],
                        $home_content
                    );
                    $home_content = str_replace(
                        "<DATA>",
                        $row["Data"],
                        $home_content
                    );
                    $home_content = str_replace(
                        "<SALA>",
                        $row["NumeroSala"],
                        $home_content
                    );
                    $home_content = str_replace(
                        "<IMAGE>",
                        $row["SrcImg"],
                        $home_content
                    );
                    $home_content = str_replace(
                        "<IDFILM>",
                        $row["IDFilm"],
                        $home_content
                    );

                }
            }



			// cerca posti

			$db = SingletonDB::getInstance();
			$db->connect();
            $query = "SELECT p.NumeroPosto, p.FilaPosto FROM Occupa as p WHERE p.IDPrenotazione=?";

            $preparedQuery = $db->getConnection()->prepare($query);

            $preparedQuery->bind_param("i", $codice);
            $preparedQuery->execute();
            $result = $preparedQuery->get_result();
            $db->disconnect();
            $preparedQuery->close();

			$listaPosti = "";

			if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
					$listaPosti = $listaPosti . $row["FilaPosto"] . $row["NumeroPosto"] . ", ";
				}
			}

			$home_content = str_replace(
                        "<POSTI>",
                        substr($listaPosti, 0, -2),
                        $home_content
            );

			unset($codice);
		}

        return $home_content;
    }

	function deleteReservation($codice) {

		if (isset($codice)) {
			$db = SingletonDB::getInstance();
			$db->connect();
			$query = "DELETE FROM Prenotazione WHERE ID=?";

			$preparedQuery = $db->getConnection()->prepare($query);
			$preparedQuery->bind_param("i", $codice);
			$preparedQuery->execute();
			$preparedQuery->close();
			$db->disconnect();
		}
		return $this->getHistory();
	}
}
?>
