<?php session_start();
//DB conncetion
include_once('includes/config.php');
error_reporting(0);
//validating Session
if (strlen($_SESSION['aid']==0)) {
  header('location:logout.php');
  } else{


?>
<!DOCTYPE html>
<html lang="Pt_br">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Monitoramento Remoto</title>

    <!-- Custom fonts for this template -->
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

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

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
<?php
$fdate = $_POST['fromdate'];
$dateTime = new DateTime($fdate);
$fdate1 = $dateTime->format('d/m/Y');

$tdate = $_POST['todate'];
$dateTime1 = new DateTime($tdate);
$tdate1 = $dateTime1->format('d/m/Y');

$familymember = $_POST['paciente'];
$familymemberdata = explode("-", $familymember);
$fid = $familymemberdata[0];
$familname = $familymemberdata[1];
$uid = intval($_SESSION['aid']);

$dateOnly = $dateTime->format('d/m/Y');

$query = mysqli_query($con, "SELECT * FROM tblpaciente WHERE registro='$fid'");
$cnt = 1;
while ($row = mysqli_fetch_array($query)) {
    $pacienteNome = $row['name'];
    $sexo = $row['sexo'];
    $idade = $row['idade'];
}
?>

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800"><?php echo utf8_encode("Relatório referente a data de:") ?>
    <?php echo $fdate1; ?> <?php echo utf8_encode("até") ?> <?php echo $tdate1; ?></h1>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><?php echo utf8_encode("Resultados do relatório encontrado:") ?></h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table border="1" class="table table-striped">
                <tr>
                    <th>Paciente</th>
                    <td><?php echo utf8_encode($pacienteNome); ?></td>
                </tr>
                <tr>
                    <th>Sexo</th>
                    <td><?php echo $sexo; ?></td>
                </tr>
                <tr>
                    <th>Idade</th>
                    <td><?php echo $idade; ?></td>
                </tr>
            </table>
            <h5 style="color: blue"><?php echo utf8_encode("Medição do Paciente:") ?> <?php echo utf8_encode($pacienteNome); ?></h5>

            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Sno.</th>
                        <th>SPo2</th>
                        <th>BPM</th>
                        <th>Temp. Corporal</th>
                      
                        <th>Data Postagem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = mysqli_query($con, "SELECT *, tblpaciente.registro as bpid FROM tblpaciente
                                                LEFT JOIN tblmedicao ON tblmedicao.paciente_id = tblpaciente.registro
                                                WHERE tblpaciente.registro = '$fid'
                                                AND (date(postingTime) BETWEEN '$fdate' AND '$tdate')
                                                AND tblmedicao.paciente_id = '$fid' ORDER BY postingTime ASC");
                    
                    
                    
                  

                    
                    
                    
                    
                    
                    $cnt = 1;
                    $count = mysqli_num_rows($query);
                    $spo2 = 0;
                    $bpm = 0;
                    $temperature = 0;

                    if ($count > 0) {
                        while ($row = mysqli_fetch_array($query)) {
                            $spo2 = $row['spo2'];
                            $bpm = $row['bpm'];
                            $temperature = $row['temp_corp'];
                            $bpDateTime = $row['bpDateTime'];
                            $postingTime = $row['postingTime'];
                            $datenew = new DateTime($postingTime);
                            $posdatenew = $datenew->format('d/m/Y -  H:i:s');
                            
                          
                    ?>
                            <tr>
                                <td><?php echo $cnt; ?></td>
                                <td><?php echo $spo2 ; ?></td>
                                <td><?php echo $bpm ; ?></td>
                                <td><?php echo $temperature; ?></td>
                               
                                <td><?php echo $posdatenew; ?></td>
                                <td></td>
                            </tr>
                    <?php
                            $cnt++;
                        }
                    ?>
                        <tr>
                            <th>Media</th>
                            <td><?php echo round($spo2 / $count, 2) ; ?></td>
                            <td><?php echo round($bpm / $count, 2); ?></td>
                            <td colspan="3"><?php echo round($temperature / $count, 2); ?></td>
                        </tr>
                    <?php
                    } else {
                    ?>
                        <tr>
                            <td colspan="5" style="color: red; font-size: 22px;">Nenhum Registro Encontrado</td>
                        </tr>
                    <?php
                    }
                    ?>
                    
                </tbody>
            </table>
        </div>
                      <?php



/* Database connection settings */
include_once 'includes/db.php';
$fdate = $_POST['fromdate'];
$dateTime = new DateTime($fdate);
$fdate1 = $dateTime->format('d/m/Y');

$tdate = $_POST['todate'];
$dateTime1 = new DateTime($tdate);
$tdate1 = $dateTime1->format('d/m/Y');

$fid = $familymemberdata[0];


$dataspo2 = array(); // Initialize as an array for temperature
$databpm = array();    // Initialize as an array for humidity
$times = array();           // Initialize as an array for timestamps

$query = "SELECT *, tblpaciente.registro as bpid FROM tblpaciente
          LEFT JOIN tblmedicao ON tblmedicao.paciente_id = tblpaciente.registro
          WHERE tblpaciente.registro = '$fid'
          AND (date(postingTime) BETWEEN '$fdate' AND '$tdate')
          AND tblmedicao.paciente_id = '$fid'";
          
$runQuery = mysqli_query($conn, $query);

while ($row = mysqli_fetch_array($runQuery)) {
    // Add data to arrays
  
    $dataspo2[] = $row['spo2'];
    $databpm[] = $row['bpm'];
    $times[] = $row['postingTime'];
    $paciente_id[] = $row['paciente_id'];
}
?>

<!-- Your HTML and chart setup -->
<div class="flex flex-row flex-wrap flex-grow mt-4">
    <div class="w-full md:w-1/2 xl:w-1/3 p-6">
        <div class="bg-white border-transparent rounded-lg shadow-xl">
            <div class="bg-gradient-to-b from-gray-300 to-gray-600 uppercase text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                <h5 class="font-bold uppercase text-white"><?php  echo utf8_encode("Spo2:")?> <?php echo $fdate1; ?> <?php echo utf8_encode("até") ?> <?php echo $tdate1; ?></h1></h5>
            </div>
            <div class="p-5">
                <canvas id="chartjs-7" class="chartjs" width="undefined" height="undefined"></canvas>
                <script>
                    new Chart(document.getElementById("chartjs-7"), {
                        "type": "line",
                        "data": {
                            "labels": <?php echo json_encode($times); ?>,
                            "datasets": [{
                                "label": "Spo2",
                                "data": <?php echo json_encode($dataspo2); ?>,
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
$fdate = $_POST['fromdate'];
$dateTime = new DateTime($fdate);
$fdate1 = $dateTime->format('d/m/Y');

$tdate = $_POST['todate'];
$dateTime1 = new DateTime($tdate);
$tdate1 = $dateTime1->format('d/m/Y');

$fid = $familymemberdata[0];


$dataspo2 = array(); // Initialize as an array for temperature
$databpm = array();    // Initialize as an array for humidity
$times1 = array();           // Initialize as an array for timestamps

$query = "SELECT *, tblpaciente.registro as bpid FROM tblpaciente
          LEFT JOIN tblmedicao ON tblmedicao.paciente_id = tblpaciente.registro
          WHERE tblpaciente.registro = '$fid'
          AND (date(postingTime) BETWEEN '$fdate' AND '$tdate')
          AND tblmedicao.paciente_id = '$fid'";
          
$runQuery = mysqli_query($conn, $query);

while ($row = mysqli_fetch_array($runQuery)) {
    // Add data to arrays
  
    $dataspo2[] = $row['spo2'];
    $databpm[] = $row['bpm'];
    $times1[] = $row['postingTime'];
    $paciente_id[] = $row['paciente_id'];
}
?>
                <div class="w-full md:w-1/2 xl:w-1/3 p-6">
                    <!--Graph Card-->
                    <div class="bg-white border-transparent rounded-lg shadow-xl">
                        <div class="bg-gradient-to-b from-gray-300 to-gray-600 uppercase text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                            <h5 class="font-bold uppercase text-white"><?php  echo utf8_encode("bpm:")?> <?php echo $fdate1; ?> <?php echo utf8_encode("até") ?> <?php echo $tdate1; ?></h1></h5>
                        </div>
                        <div class="p-5">
                            <canvas id="chartjs-0" class="chartjs" width="undefined" height="undefined"></canvas>
                            <script>
                                new Chart(document.getElementById("chartjs-0"), {
                                    "type": "line",
                                    "data": {
                                        "labels":  <?php echo json_encode($times1); ?>,
                                        "datasets": [{
                                            "label": "Bpm",
                                            "data": <?php echo json_encode($databpm); ?>,
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
$fdate = $_POST['fromdate'];
$dateTime = new DateTime($fdate);
$fdate1 = $dateTime->format('d/m/Y');

$tdate = $_POST['todate'];
$dateTime1 = new DateTime($tdate);
$tdate1 = $dateTime1->format('d/m/Y');

$fid = $familymemberdata[0];


$dataspo2 = array(); // Initialize as an array for temperature
$databpm = array();    // Initialize as an array for humidity
$times2 = array();           // Initialize as an array for timestamps

$query = "SELECT *, tblpaciente.registro as bpid FROM tblpaciente
          LEFT JOIN tblmedicao ON tblmedicao.paciente_id = tblpaciente.registro
          WHERE tblpaciente.registro = '$fid'
          AND (date(postingTime) BETWEEN '$fdate' AND '$tdate')
          AND tblmedicao.paciente_id = '$fid'";
          
$runQuery = mysqli_query($conn, $query);

while ($row = mysqli_fetch_array($runQuery)) {
    // Add data to arrays
  
    $datastem_corp[] = $row['temp_corp'];
    $times2[] = $row['postingTime'];
    $paciente_id[] = $row['paciente_id'];
}
?>       
                <div class="w-full md:w-1/2 xl:w-1/3 p-6">
                    <!--Graph Card-->
                    <div class="bg-white border-transparent rounded-lg shadow-xl">
                        <div class="bg-gradient-to-b from-gray-300 to-gray-600 uppercase text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                            <h5 class="font-bold uppercase text-white"><?php  echo utf8_encode("Temp.:")?> <?php echo $fdate1; ?> <?php echo utf8_encode("até") ?> <?php echo $tdate1; ?></h1></h5>
                        </div>
                        <div class="p-5">
                            <canvas id="chartjs-1" class="chartjs" width="undefined" height="undefined"></canvas>
                            <script>
                                new Chart(document.getElementById("chartjs-1"), {
                                    "type": "bar",
                                    "data": {
                                        "labels": <?php echo json_encode($times2); ?>,
                                        "datasets": [{
                                            "label": "Temp. Corporal",
                                            "data":  <?php echo json_encode( $datastem_corp); ?>,
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

             </div>
        
    </div>
</div>

                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->


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
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>
</body>
</html>
<?php } ?>