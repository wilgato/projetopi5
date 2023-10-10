<style>

.heart-rate {
max-width: 180px;
height: 100px;
position: relative;
margin:5px;
top:10px;
overflow:hidden;
    
}

.fade-in {
  position: absolute;
  width: 100%;
  height:100%;
  background-color: white;
  top: 0;
  right: 0;
  animation: heartRateIn 4.5s linear infinite;

 /* Gia na katalavw ti ginetai des auto
    border:1px solid red;
    */

}

.fade-out {
  position: absolute;
  width: 120%;
  height: 100%;
  top: 0;
  left: -120%;
  animation: heartRateOut 4.5s linear infinite;
  background: rgba(255, 255, 255, 1);
  background: -moz-linear-gradient(left, rgba(255, 255, 255, 1) 0%, rgba(255, 255, 255, 1) 50%, rgba(255, 255, 255, 0) 100%);
  background: -webkit-linear-gradient(left, rgba(255, 255, 255, 1) 0%, rgba(255, 255, 255, 1) 50%, rgba(255, 255, 255, 0) 100%);
  background: -o-linear-gradient(left, rgba(255, 255, 255, 1) 0%, rgba(255, 255, 255, 1) 50%, rgba(255, 255, 255, 0) 100%);
  background: -ms-linear-gradient(left, rgba(255, 255, 255, 1) 0%, rgba(255, 255, 255, 1) 50%, rgba(255, 255, 255, 0) 100%);
  background: linear-gradient(to right, rgba(255, 255, 255, 1) 0%, rgba(255, 255, 255, 1) 80%, rgba(255, 255, 255, 0) 100%);
}

@keyframes heartRateIn {
  0% {
    width: 100%;
  }
  50% {
    width: 0%;
  }
  100% {
    width: 0;
  }
}

@keyframes heartRateOut {
  0% {
    left: -120%;
  }
  30% {
    left: -120%;
  }
  100% {
    left: 0;
  }
}

</style>



<?php
session_start();
include_once('includes/config.php');
if (strlen($_SESSION['aid']==0)) {
  header('location:logout.php');
  } else{

?>
<!DOCTYPE html>
<html lang="Pt_Br">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>MR Monitoramento Remoto</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
      
       <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css" rel=" stylesheet">
    <!--Replace with your tailwind.css once created-->
    <link href="https://afeld.github.io/emoji-css/emoji.css" rel="stylesheet">
    <!--Totally optional :) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js" integrity="sha256-XF29CBwU1MWLaGEnsELogU6Y6rcc5nCkhhx89nFMIDQ=" crossorigin="anonymous"></script>
      

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
       <?php include_once('includes/sidebar.php');?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
<?php include_once('includes/topbar.php');?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Monitoramento</h1>
                        <hr />
                        <a href="bwdates-report-ds.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Relatorios</a>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

<?php
/* Database connection settings */
include_once 'includes/db.php';
$dataTemperature = array(); // Initialize as an array for temperature
$dataHumidity = array();    // Initialize as an array for humidity
$dataPressure = array();    // Initialize as an array for pressure
$times = array();           // Initialize as an array for timestamps

$query = "SELECT * FROM dados_sensor1 ORDER BY id DESC LIMIT 10 ";
$runQuery = mysqli_query($conn, $query);

while ($row = mysqli_fetch_array($runQuery)) {
    // Add data to arrays
    $dataTemperature[] = $row['temperatura'];
    $dataHumidity[] = $row['umidade'];
    $dataPressure[] = $row['pressao'];
    $times[] = $row['postingTime'];
    $paciente_id[] = $row['paciente_id'];
}
?>

<!-- Your HTML and chart setup -->
<div class="flex flex-row flex-wrap flex-grow mt-2">
    <div class="w-full md:w-1/2 xl:w-1/3 p-6">
        <div class="bg-white border-transparent rounded-lg shadow-xl">
            <div class="bg-gradient-to-b from-gray-300 to-gray-100 uppercase text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                <h5 class="font-bold uppercase text-gray-600"><?php  echo utf8_encode("Temperatura do Ambiente")?></h5>
            </div>
            <div class="p-5">
                <canvas id="chartjs-7" class="chartjs" width="undefined" height="undefined"></canvas>
                <script>
                    new Chart(document.getElementById("chartjs-7"), {
                        "type": "line",
                        "data": {
                            "labels": <?php echo json_encode($times); ?>,
                            "datasets": [{
                                "label": "Temperatura Ambiente",
                                "data": <?php echo json_encode($dataTemperature); ?>,
                                "borderColor": "rgb(255, 99, 132)",
                                "backgroundColor": "rgba(255, 99, 132, 0.2)"
                            }]
                        },
                        "options": {
                            "scales": {
                                "yAxes": [{
                                    "ticks": {
                                        "beginAtZero": true
                                    }
                                }]
                            }
                        }
                    });
                </script>
                        </div>
                    </div>
                    <!--/Graph Card-->
                </div>

<?php
/* Database connection settings */
include_once 'includes/db.php';
$dataTemperature = array(); // Initialize as an array for temperature
$dataHumidity = array();    // Initialize as an array for humidity
$dataPressure = array();    // Initialize as an array for pressure
$times = array();           // Initialize as an array for timestamps

$query = "SELECT * FROM dados_sensor1 ORDER BY id DESC LIMIT 10 ";
$runQuery = mysqli_query($conn, $query);

while ($row = mysqli_fetch_array($runQuery)) {
    // Add data to arrays
  $dataTemperature[] = $row['temperatura'];
    $dataHumidity[] = $row['umidade'];
    $dataPressure[] = $row['pressao'];
    $times[] = $row['postingTime'];
    $paciente_id[] = $row['paciente_id'];
}
?>
                <div class="w-full md:w-1/2 xl:w-1/3 p-6">
                    <!--Graph Card-->
                    <div class="bg-white border-transparent rounded-lg shadow-xl">
                        <div class="bg-gradient-to-b from-gray-300 to-gray-100 uppercase text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                            <h5 class="font-bold uppercase text-gray-600"><?php  echo utf8_encode("Umidade do Ambiente")?></h5>
                        </div>
                        <div class="p-5">
                            <canvas id="chartjs-0" class="chartjs" width="undefined" height="undefined"></canvas>
                            <script>
                                new Chart(document.getElementById("chartjs-0"), {
                                    "type": "line",
                                    "data": {
                                        "labels":  <?php echo json_encode($times); ?>,
                                        "datasets": [{
                                            "label": "Umidade Ambiente",
                                            "data": <?php echo json_encode($dataTemperature); ?>,
                                            "fill": false,
                                            "borderColor": "rgb(75, 192, 192)",
                                            "lineTension": 0.1
                                        }]
                                    },
                                    "options": {}
                                });
                            </script>
                        </div>
                    </div>
                    <!--/Graph Card-->
                </div>

   <?php
/* Database connection settings */
include_once 'includes/db.php';
$Temperature = array(); // Initialize as an array for temperature
$times1 = array();           // Initialize as an array for timestamps

$query = "SELECT * FROM dados_sensor3 ORDER BY id DESC LIMIT 10 ";
$runQuery = mysqli_query($conn, $query);

while ($row = mysqli_fetch_array($runQuery)) {
    // Add data to arrays
  $Temperature[] = $row['temperature'];
    $times1[] = $row['postingTime'];
    $paciente_id[] = $row['paciente_id'];
}
?>             
                <div class="w-full md:w-1/2 xl:w-1/3 p-6">
                    <!--Graph Card-->
                    <div class="bg-white border-transparent rounded-lg shadow-xl">
                        <div class="bg-gradient-to-b from-gray-300 to-gray-100 uppercase text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                            <h5 class="font-bold uppercase text-gray-600"><?php  echo utf8_encode("Pressão do Ambiente")?></h5>
                        </div>
                        <div class="p-5">
                            <canvas id="chartjs-1" class="chartjs" width="undefined" height="undefined"></canvas>
                            <script>
                                new Chart(document.getElementById("chartjs-1"), {
                                    "type": "radar",
                                    "data": {
                                        "labels": <?php echo json_encode($times1); ?>,
                                        "datasets": [{
                                            "label": "Temp. Corporal",
                                            "data":  <?php echo json_encode( $Temperature); ?>,
                                            "fill": false,
                                            "backgroundColor": ["rgba(255, 99, 132, 0.2)", "rgba(255, 159, 64, 0.2)", "rgba(255, 205, 86, 0.2)", "rgba(75, 192, 192, 0.2)", "rgba(54, 162, 235, 0.2)", "rgba(153, 102, 255, 0.2)", "rgba(201, 203, 207, 0.2)"],
                                            "borderColor": ["rgb(255, 99, 132)", "rgb(255, 159, 64)", "rgb(255, 205, 86)", "rgb(75, 192, 192)", "rgb(54, 162, 235)", "rgb(153, 102, 255)", "rgb(201, 203, 207)"],
                                            "borderWidth": 1
                                        }]
                                    },
                                    "options": {
                                        "scales": {
                                            "yAxes": [{
                                                "ticks": {
                                                    "beginAtZero": true
                                                }
                                            }]
                                        }
                                    }
                                });
                            </script>
                        </div>
                    </div>
                    <!--/Graph Card-->
                </div>
 <?php
// Configurações do banco de dados


$host = "localhost";
$usuario = "root";
$senha =  "";
$bancoDeDados = "univesp";



// Conexão com o banco de dados
$conexao = new mysqli($host, $usuario, $senha, $bancoDeDados);

// Verifica a conexão
if ($conexao->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conexao->connect_error);
}


// ID do paciente desejado
$pacienteIdDesejado = 101; // Substitua pelo ID do paciente desejado

// Consulta SQL para obter os dados do paciente com a ID especificada

$query = "SELECT * FROM tblpaciente where registro= '$pacienteIdDesejado' ORDER BY id DESC LIMIT 1";
$resultado = $conexao->query($query);

if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $pacienteId = $row["id"];
        $registro = $row["registro"];
        $pacienteNome = $row["name"];
        $idade = $row["idade"];
        $sexo = $row["sexo"];
        
        // Consulta SQL para obter o último registro de BPM do paciente
    
        $queryBPM = "SELECT * FROM dados_sensor2 where paciente_id= '$registro' ORDER BY id DESC LIMIT 1";
        
        $resultadoBPM = $conexao->query($queryBPM);
        
        if ($resultadoBPM->num_rows > 0) {
            $rowBPM = $resultadoBPM->fetch_assoc();
            $bpm = $rowBPM["bpm"];
            $spo = $rowBPM["spo2"];
            
            $mysqlDateTime = $rowBPM["postingTime"];
            $dateTime = new DateTime($mysqlDateTime);
            $dateOnly = $dateTime->format('d/m/Y');
            // Lógica de comparação com base nos parâmetros para sexo e idade
            $status_bpm = "";
            $cor_fonte = "";
            
       if ($sexo == "Masculino") {
                if ($idade >= 18 && $idade <= 25) {
                    if ($bpm >= 56 && $bpm <= 61) {
                        $status_bpm = "Excelente";
                        $cor_fonte = "green"; // Define a cor da fonte para verde
                    } elseif ($bpm >= 62 && $bpm <= 65) {
                        $status_bpm = "Boa";
                         $cor_fonte = "blue"; // Define a cor da fonte para azul
                    } elseif ($bpm >= 70 && $bpm <= 73) {
                        $status_bpm = "Normal";
                        $cor_fonte = "black"; // Define a cor da fonte para preto
                    } elseif ($bpm >= 74 && $bpm <= 81) {
                        $status_bpm = "Menos boa";
                        $cor_fonte = "orange"; // Define a cor da fonte para laranja
                    } elseif ($bpm >= 82) {
                        $status_bpm = "Ruim";
                          $cor_fonte = "red"; // Define a cor da fonte para vermelho
                    }
                } elseif ($idade >= 26 && $idade <= 35) {
                    if ($bpm >= 55 && $bpm <= 61) {
                        $status_bpm = "Excelente";
                    } elseif ($bpm >= 62 && $bpm <= 65) {
                        $status_bpm = "Boa";
                    } elseif ($bpm >= 71 && $bpm <= 74) {
                        $status_bpm = "Normal";
                    } elseif ($bpm >= 75 && $bpm <= 81) {
                        $status_bpm = "Menos boa";
                    } elseif ($bpm >= 82) {
                        $status_bpm = "Ruim";
                    }
                } elseif ($idade >= 36 && $idade <= 45) {
                    if ($bpm >= 57 && $bpm <= 62) {
                        $status_bpm = "Excelente";
                    } elseif ($bpm >= 63 && $bpm <= 66) {
                        $status_bpm = "Boa";
                    } elseif ($bpm >= 71 && $bpm <= 75) {
                        $status_bpm = "Normal";
                    } elseif ($bpm >= 76 && $bpm <= 82) {
                        $status_bpm = "Menos boa";
                    } elseif ($bpm >= 83) {
                        $status_bpm = "Ruim";
                    }
                } elseif ($idade >= 46 && $idade <= 55) {
                    if ($bpm >= 58 && $bpm <= 63) {
                        $status_bpm = "Excelente";
                        $cor_fonte = "green"; // Define a cor da fonte para verde
                    } elseif ($bpm >= 64 && $bpm <= 67) {
                        $status_bpm = "Boa";
                        $cor_fonte = "blue"; // Define a cor da fonte para azul
                    } elseif ($bpm >= 72 && $bpm <= 76) {
                        $status_bpm = "Normal";
                        $cor_fonte = "black"; // Define a cor da fonte para preto
                    } elseif ($bpm >= 77 && $bpm <= 83) {
                        $status_bpm = "Menos boa";
                        $cor_fonte = "orange"; // Define a cor da fonte para laranja
                    } elseif ($bpm >= 84) {
                        $status_bpm = "Ruim";
                          $cor_fonte = "red"; // Define a cor da fonte para vermelho
                    }
                } elseif ($idade >= 56 && $idade <= 65) {
                    if ($bpm >= 57 && $bpm <= 61) {
                        $status_bpm = "Excelente";
                    } elseif ($bpm >= 62 && $bpm <= 67) {
                        $status_bpm = "Boa";
                    } elseif ($bpm >= 72 && $bpm <= 75) {
                        $status_bpm = "Normal";
                    } elseif ($bpm >= 76 && $bpm <= 81) {
                        $status_bpm = "Menos boa";
                    } elseif ($bpm >= 82) {
                        $status_bpm = "Ruim";
                    }
                } elseif ($idade > 65) {
                    if ($bpm >= 56 && $bpm <= 61) {
                        $status_bpm = "Excelente";
                    } elseif ($bpm >= 62 && $bpm <= 65) {
                        $status_bpm = "Boa";
                    } elseif ($bpm >= 70 && $bpm <= 73) {
                        $status_bpm = "Normal";
                    } elseif ($bpm >= 74 && $bpm <= 79) {
                        $status_bpm = "Menos boa";
                    } elseif ($bpm >= 80) {
                        $status_bpm = "Ruim";
                    }
                }
            } elseif ($sexo == "feminino") {
                if ($idade >= 18 && $idade <= 25) {
                    if ($bpm >= 61 && $bpm <= 65) {
                        $status_bpm = "Excelente";
                    } elseif ($bpm >= 66 && $bpm <= 69) {
                        $status_bpm = "Boa";
                    } elseif ($bpm >= 74 && $bpm <= 78) {
                        $status_bpm = "Normal";
                    } elseif ($bpm >= 79 && $bpm <= 84) {
                        $status_bpm = "Menos boa";
                    } else {
                        $status_bpm = "Ruim";
                    }
                } elseif ($idade >= 26 && $idade <= 35) {
                    // Repita a lógica para outras faixas etárias e status
                }
                // Repita para outras faixas etárias e sexos
            }
            
            // Exiba os resultados
          
        } else {
            echo "Paciente não possui medição de BPM.<br><br>";
        }
    }
} else {
    echo "Nenhum resultado encontrado.";
}

// Fecha a conexão com o banco de dados
$conexao->close();
?>
   
                <div class="w-full md:w-1/2 xl:w-1/3 p-6">
                    <!--Graph Card-->
                    <div class="bg-white border-transparent rounded-lg shadow-xl">
                        <div class="bg-gradient-to-b from-gray-300 to-gray-100 uppercase text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                            <h5 class="font-bold uppercase text-gray-600"><?php  echo utf8_encode("Info Paciente:")?> <td><?php echo($dateOnly); ?></h5>
                        </div>
                                  <div class="p-5">
                         
                                <table class="w-full p-5 text-gray-700">
                                <thead>
                                 <tr>
                                        <th class="text-left text-blue-90">Nome</th>
                                       
                                    </tr>
                                 <tr>
                                        <td><?php  echo utf8_encode("$pacienteNome")?></td>
                                      
                                    </tr>
                                 <table class="w-full p-5 text-gray-700">
                                    <tr>
                                       
                                        <th class="text-left text-blue-900">Idade</th>
                                        <th class="text-left text-blue-90">Sexo</th>
                                    </tr>
                              
                                    <tr>
                                  
                                        <td><?php echo($idade); ?></td>
                                        <td><?php echo($sexo); ?></td>
                                    </tr>
                           
                                    <tr>
                                
                                   <th class="text-left text-blue-900">SPo2: <td><?php echo($spo); ?> <?php  echo utf8_encode("/oxigenação")?></td>
                                      
                                        <th class="text-left text-blue-900">Bpm:<td style="color: <?php echo($cor_fonte); ?>"><?php echo($bpm); ?>  / <?php echo($status_bpm); ?></td> </th>
                                    </tr>
                        
                                  </tbody>
                            </table> 
                           
                            <div align=center> <div class="heart-rate">
  <svg version="1.0" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="150px" height="73px" viewBox="0 0 150 73" enable-background="new 0 0 150 73" xml:space="preserve">
    <polyline fill="none" stroke="green" stroke-width="3" stroke-miterlimit="10" points="0,45.486 38.514,45.486 44.595,33.324 50.676,45.486 57.771,45.486 62.838,55.622 71.959,9 80.067,63.729 84.122,45.486 97.297,45.486 103.379,40.419 110.473,45.486 150,45.486"
    />
  </svg>
  <div class="fade-in"></div>
  <div class="fade-out"></div>
</div></div>
     


                        </div>
                    </div>
                    <!--/table Card-->
                </div>
                    <!--/Graph Card-->
        
  <?php
/* Database connection settings */
include_once 'includes/db.php';
$dataspo = array(); // Initialize as an array for temperature
$databmp = array();    // Initialize as an array for humidity
$times = array();           // Initialize as an array for timestamps

$query = "SELECT * FROM dados_sensor2 ORDER BY id DESC LIMIT 10 ";
$runQuery = mysqli_query($conn, $query);

while ($row = mysqli_fetch_array($runQuery)) {
    // Add data to arrays
   $dataspo[] = $row['spo2'];
    $databmp[] = $row['bpm'];
    $times[] = $row['postingTime'];
    $paciente_id[] = $row['paciente_id'];
}
?>      
        
        
        

                        

                <div class="w-full md:w-1/2 xl:w-1/3 p-6">
                    <!--Table Card-->
                    <div class="bg-white border-transparent rounded-lg shadow-xl">
                        <div class="bg-gradient-to-b from-gray-300 to-gray-100 uppercase text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                            <h5 class="font-bold uppercase text-gray-600"><?php  echo utf8_encode("Monitoramento Bpm ")?></h5>
                        </div>
                        
                        <div class="p-5">
                            
    
                            <canvas id="chartjs-6" class="chartjs" width="undefined" height="undefined"></canvas>
                            <script>
                                new Chart(document.getElementById("chartjs-6"), {
                                    "type": "line",
                                    "data": {
                                        "labels":  <?php echo json_encode($times); ?>,
                                        "datasets": [{
                                            "label": "<?php  echo utf8_encode("Bpm")?>",
                                            "data": <?php echo json_encode($databmp); ?>,
                                            "fill": false,
                                            "borderColor": "rgb(85, 180, 145)",
                                            "lineTension": 0.1
                                        }]
                                    },
                                    "options": {}
                                });
                            </script>
 </div>
                          </br>
                    </div>
                    <!--/table Card-->
                </div>

                
  <?php
/* Database connection settings */
include_once 'includes/db.php';
$dataspo = array(); // Initialize as an array for temperature
$databmp = array();    // Initialize as an array for humidity
$times = array();           // Initialize as an array for timestamps

$query = "SELECT * FROM dados_sensor2 ORDER BY id DESC LIMIT 10 ";
$runQuery = mysqli_query($conn, $query);

while ($row = mysqli_fetch_array($runQuery)) {
    // Add data to arrays
   $dataspo[] = $row['spo2'];
    $databmp[] = $row['bpm'];
    $times[] = $row['postingTime'];
    $paciente_id[] = $row['paciente_id'];
}
?>             
                
                
                
                <div class="w-full md:w-1/2 xl:w-1/3 p-6">
                    <!--Advert Card-->
                    <div class="bg-white border-transparent rounded-lg shadow-xl">
                        <div class="bg-gradient-to-b from-gray-300 to-gray-100 uppercase text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                            <h5 class="font-bold uppercase text-gray-600"><?php  echo utf8_encode("Monitoramento SPo2")?></h5>
                             </div>
                        <div class="p-5">
                            <canvas id="chartjs-5" class="chartjs" width="undefined" height="undefined"></canvas>
                            <script>
                                new Chart(document.getElementById("chartjs-5"), {
                                    "type": "line",
                                    "data": {
                                        "labels":  <?php echo json_encode($times); ?>,
                                        "datasets": [{
                                            "label": "<?php  echo utf8_encode("SPo2")?>",
                                            "data": <?php echo json_encode($dataspo); ?>,
                                            "fill": false,
                                            "borderColor": "rgb(75, 192, 192)",
                                            "lineTension": 0.1
                                        }]
                                    },
                                    "options": {}
                                });
                            </script>


                            <script async type="text/javascript" src="//cdn.carbonads.com/carbon.js?serve=CK7D52JJ&placement=wwwtailwindtoolboxcom" id="_carbonads_js"></script>

</br>
                        </div>
                    </div>
                    <!--/Advert Card-->
                </div>


            </div>
        </div>
    </div>

    </div>






    <script>
        /*Toggle dropdown list*/
        function toggleDD(myDropMenu) {
            document.getElementById(myDropMenu).classList.toggle("invisible");
        }
        /*Filter dropdown options*/
        function filterDD(myDropMenu, myDropMenuSearch) {
            var input, filter, ul, li, a, i;
            input = document.getElementById(myDropMenuSearch);
            filter = input.value.toUpperCase();
            div = document.getElementById(myDropMenu);
            a = div.getElementsByTagName("a");
            for (i = 0; i < a.length; i++) {
                if (a[i].innerHTML.toUpperCase().indexOf(filter) > -1) {
                    a[i].style.display = "";
                } else {
                    a[i].style.display = "none";
                }
            }
        }
        // Close the dropdown menu if the user clicks outside of it
        window.onclick = function(event) {
            if (!event.target.matches('.drop-button') && !event.target.matches('.drop-search')) {
                var dropdowns = document.getElementsByClassName("dropdownlist");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (!openDropdown.classList.contains('invisible')) {
                        openDropdown.classList.add('invisible');
                    }
                }
            }
        }
    </script>


</body>

                    <!-- Content Row -->

              

             

            <!-- Footer -->
       <?php include_once('includes/footer.php');?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
           <?php include_once('includes/footer2.php');?>
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>
<?php } ?>