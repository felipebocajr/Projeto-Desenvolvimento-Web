
<?php

$nome = $_POST['nome'];
$email = $_POST['email'];
$cpf = $_POST['cpf'];
$telefone = $_POST['telefone'];

$strcon = mysqli_connect("localhost","root","","sunsage_db") or die ("Erro ao conectar com o banco");

// Inserir os dados do candidato na tabela candidato usando instrução preparada
$sql_candidato = "INSERT INTO candidato (nome, email, CPF, numero_telefone) VALUES (?, ?, ?, ?)";
$stmt_candidato = $strcon->prepare($sql_candidato);
$stmt_candidato->bind_param("ssis", $nome, $email, $cpf, $telefone);
$stmt_candidato->execute();
$stmt_candidato->close();

// Enviar o currículo para a pasta de currículos e inserir o caminho na tabela curriculo
$curriculo = $_FILES['curriculo'];

$pasta = "curriculos/";
$nomeDoCurriculo = $curriculo["name"];
$novoNomeDoCurriculo = uniqid();
$extensao = strtolower(pathinfo($nomeDoCurriculo, PATHINFO_EXTENSION));

if ($extensao != "pdf")
    die("Tipo de arquivo não aceito");

$path = $pasta. $novoNomeDoCurriculo . "." . $extensao;

$deu_certo = move_uploaded_file($curriculo["tmp_name"], $path);
if($deu_certo) { 
    $strcon ->query("INSERT INTO curriculo (curriculo, paths) VALUES ('$nomeDoCurriculo', '$path')") or die($strcon->error);
    echo "<p>Currículo enviado com sucesso";
} else  
    echo "Falha ao enviar o arquivo<br>";
    echo "Erro: " . $_FILES['curriculo']['error'];

?>
    
