<?php
    //Dichiariamo delle variabili e daremo il nome del nostro server e del nostro database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "progetto finale";

    // Connessione al database
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Controllare la connessione
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Controlla se i parametri 'temperatura', 'ledstate' e 'livelloacqua' sono stati inviati tramite POST
    if (isset($_POST['temperatura']) && isset($_POST['ledstate']) && isset($_POST['livelloacqua'])) {

        // Assegna i valori inviati tramite POST alle variabili locali
        $temp = $_POST['temperatura'];
        $led = $_POST['ledstate'];
        $livelloacqua = $_POST['livelloacqua'];
        //$sql = "INSERT INTO prova (Matricola, Alunno) VALUES ('$matricola','$alunno')";
        
        // Inserisce il valore della temperatura nella tabella 'termometro'
        $sql1 = "INSERT INTO termometro (temperatura) VALUES ('$temp')";

        // Esegue la query e controlla se è stata eseguita con successo
        if ($conn->query($sql1) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql1 . "<br>" . $conn->error;
        }

        // Inserisce il valore dello stato del LED nella tabella 'led'
        $sql2 = "INSERT INTO led (stato) VALUES ('$led')";

        // Esegue la query e controlla se è stata eseguita con successo
        if ($conn->query($sql2) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql2 . "<br>" . $conn->error;
        }

        // Inserisce il valore dello stato del LED (usato per la valvola) nella tabella 'valvola'
        $sql3 = "INSERT INTO valvola (statoservo) VALUES ('$led')";

        // Esegue la query e controlla se è stata eseguita con successo
        if ($conn->query($sql3) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql3 . "<br>" . $conn->error;
        }

        // Inserisce il valore del livello dell'acqua nella tabella 'livelloacqua'
        $sql4 = "INSERT INTO livelloacqua (distanza) VALUES ('$livelloacqua')";

        // Esegue la query e controlla se è stata eseguita con successo
        if ($conn->query($sql4) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql4 . "<br>" . $conn->error;
        }
    }
    //Chiusura della connessione
    $conn->close();


    // Connessione al database
    $conn = mysqli_connect($servername, $username, $password,$dbname);
    // Verifica della connessione al database
    if (!$conn) {
    die("Connessione fallita: " . mysqli_connect_error());
    } 

    // Query per ottenere l'ultimo record dalla tabella 'termometro'
    $query1 = "SELECT * FROM termometro ORDER BY id DESC LIMIT 1";
    $result1 = mysqli_query($conn,$query1);
    $lastTemperature = "";
    $lastId = "";
    if (mysqli_num_rows($result1) > 0) {
        // Recupera l'ultimo record inserito
        $row1 = mysqli_fetch_array($result1);
        $lastId1 = $row1["id"];
        $lastTemperature = $row1["temperatura"];
    } else {
        echo "0 risultati";
    }

    // Query per ottenere l'ultimo record dalla tabella 'led'
    $query2 = "SELECT * FROM led ORDER BY id DESC LIMIT 1";
    $result2 = mysqli_query($conn,$query2);
    if (mysqli_num_rows($result2) > 0) {
        // Recupera l'ultimo record inserito
        $row2 = mysqli_fetch_array($result2);
        $lastLedState = $row2["stato"];
    } else {
        echo "0 risultati";
    }

    // Query per ottenere l'ultimo record dalla tabella 'valvola'
    $query3 = "SELECT * FROM valvola ORDER BY id DESC LIMIT 1";
    $result3 = mysqli_query($conn,$query3);
    if (mysqli_num_rows($result3) > 0) {
        // Recupera l'ultimo record inserito
        $row3 = mysqli_fetch_array($result3);
        $lastServoState = $row3["statoservo"];
        //echo "Ultimo record inserito - ID: " . $row3["id"] . " - STATOSERVO: " . $row3["statoservo"] . "<br>";
    } else {
        echo "0 risultati";
    }

    // Query per ottenere l'ultimo record dalla tabella 'livelloacqua'
    $query4 = "SELECT * FROM livelloacqua ORDER BY id DESC LIMIT 1";
    $result4 = mysqli_query($conn,$query4);
    if (mysqli_num_rows($result4) > 0) {
        // Recupera l'ultimo record inserito
        $row4 = mysqli_fetch_array($result4);
        $lastLivelloAcqua = $row4["distanza"];
        //echo "Ultimo record inserito - ID: " . $row3["id"] . " - STATOSERVO: " . $row3["statoservo"] . "<br>";
    } else {
        echo "0 risultati";
    }
    // Chiude la connessione al database
    mysqli_close($conn);

    
?>




<!DOCTYPE html>
<html>
    <head>
        <title>
            Smart PC Case
        </title>

        <link rel="stylesheet" href="nuovo.css">

        <script>
        // Funzione per aggiornare automaticamente la pagina ogni 5 secondi
        setInterval(function() {
            window.location.reload();
        }, 5000);
        </script>

    </head>


    <body class = "sfondo"> 
        <div class = "main-box">
            <h1 class = "titolo">
                SMART PC CASE
            </h1>

        <p class = "paragrafi"> Utilizzeremo questo sito per monitorare la temperatura del processore</p>
            
        <div class = "container">
            <h1>DATI RICEVUTI DAL DATABASE</h1>
            <table>
                <tr>
                    <th>Numero di controlli</th>
                    <th>Temperatura</th>
                    <th>Stato LED</th>
                    <th>Stato Servo</th>
                    <th>Livello Acqua</th>
                </tr>
                <tr>
                    <td><?php echo $lastId1; ?></td>
                    <td><?php echo $lastTemperature; ?></td>
                    <td><?php if($lastLedState==1){echo "Acceso";}else{ echo "Spento";} ?></td>
                    <td><?php if($lastServoState==1){echo "Aperto";}else{ echo "Chiuso";} ?></td>
                    <td><?php echo $lastLivelloAcqua; ?></td>
                </tr>
            </table>
    </body>
</html>
body {
    background-image: url('Immagine.jpg');
    background-size: cover;
    background-repeat: no-repeat;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh; /* Altezza dell'intera viewport */
    margin: 0; /* Rimuovere margini di default */
}

.main-box {
    background-color: rgba(255, 255, 255, 0.8); /* Opacità per vedere lo sfondo */
    padding: 20px;
    border-radius: 10px;
    width: 100%;
    max-width: 800px; /* Larghezza massima della box */
    text-align: center;
    margin: 0 auto; /* Centrare orizzontalmente */
}

.paragrafi{
    color: #000000;
    font-family:Arial, Helvetica, sans-serif;
    font-size: large;
}

.titolo{
    color: #0004ff;
    text-align: center;
    font-size: xx-large;
    font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.container {
    background-color: #ffffff;
    padding: 20px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    text-align: center;
    max-width: 600px;
    width: 100%;
    margin: 0 auto;
    margin-bottom: 50px;
}
h1 {
    color: #0004ff;
    margin-bottom: 20px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
th, td {
    padding: 15px;
    border: 1px solid #ddd;
    text-align: center;
}
th {
    background-color: #0004ff;
    color: white;
}
tr:nth-child(even) {
    background-color: #f2f2f2;
}
tr:hover {
    background-color: #ddd;
}
