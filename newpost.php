<?php
// Conexão com o banco de dados (substitua com suas configurações)
$host = "localhost";
$usuario = "energizz_jd";
$senha = "Tucg9167";
$banco = "energizz_pi5";

$conn = new mysqli($host, $usuario, $senha, $banco);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
$patientID = $_POST["patientID"];
// Recebe os dados dos sensores
$temperaturaBME = $_POST["temperaturaBME"];
$umidadeBME = $_POST["umidadeBME"];
$pressaoBME = $_POST["pressaoBME"];
$spo2 = $_POST["spo2"];
$bpm = $_POST["bpm"];
$temperaturaDS18B20 = $_POST["temperaturaDS18B20"];
$user_id = 1;
$status = 1;

// Insere os dados nas tabelas correspondentes
$sql1 = "INSERT INTO dados_sensor1 (temperatura, umidade, pressao, paciente_id) VALUES (?, ?, ?, ?)";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("ddds", $temperaturaBME, $umidadeBME, $pressaoBME, $patientID);

$sql2 = "INSERT INTO dados_sensor2 (spo2, bpm, paciente_id) VALUES (?, ?, ?)";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("dds", $spo2, $bpm, $patientID);

$sql3 = "INSERT INTO dados_sensor3 (temperatura, paciente_id) VALUES (?, ?)";
$stmt3 = $conn->prepare($sql3);
$stmt3->bind_param("ds", $temperaturaDS18B20, $patientID);

$sqlMedicao = "INSERT INTO tblmedicao (paciente_id, user_id,temp_amb, um_amb, pres_amb, temp_corp, spo2, bpm, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmtMedicao = $conn->prepare($sqlMedicao);
$stmtMedicao->bind_param("dddddddds", $patientID, $user_id,$temperaturaBME, $umidadeBME, $pressaoBME, $temperaturaDS18B20, $spo2, $bpm, $status);

$response = [];

// Iniciar a transação
mysqli_begin_transaction($conn);

if ($stmt1->execute() && $stmt2->execute() && $stmt3->execute() && $stmtMedicao->execute()) {
    // Commit a transação se todas as consultas foram bem-sucedidas
    mysqli_commit($conn);

    $response["message"] = "Todas as medições inseridas com sucesso!";
} else {
    // Rollback se houver falha em alguma consulta
    mysqli_rollback($conn);

    $response["message"] = "Erro ao inserir medições. Por favor, tente novamente.";
}

$stmt1->close();
$stmt2->close();
$stmt3->close();
$stmtMedicao->close();
$conn->close();

echo json_encode($response);
?>

