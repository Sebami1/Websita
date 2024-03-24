<?php
require 'vendor/autoload.php'; // Incluye el autoloader de Composer
use Mailgun\Mailgun;

// Datos del formulario
$nombre = $_POST['nombre'];
$email = $_POST['email'];
$mensaje = $_POST['mensaje'];

// Archivo adjunto
$archivoAdjunto = $_FILES['archivo_adjunto'];

// Correo corporativo al que se enviará el mensaje
$correoCorporativo = 'sebami@wolfstore.shop';

// Correo del remitente para enviar una copia
$correoRemitente = $_POST['correo_remitente'];

// Asunto del correo
$asunto = 'Nuevo mensaje desde formulario de contacto';

// Construir el cuerpo del mensaje
$cuerpoMensaje = "Nombre: $nombre\n";
$cuerpoMensaje .= "Email: $email\n";
$cuerpoMensaje .= "Mensaje:\n$mensaje";

// Inicializa la instancia de Mailgun con tu API key y dominio
$mg = Mailgun::create('d090618a3640c08f74d8dd261c387efc-309b0ef4-66ac1695', 'wolfstore.shop');

// Define los parámetros del correo electrónico
$params = [
    'from'    => "$nombre <$email>",
    'to'      => $correoCorporativo,
    'subject' => $asunto,
    'text'    => $cuerpoMensaje
];

// Manejo del archivo adjunto
if (!empty($archivoAdjunto['tmp_name']) && is_uploaded_file($archivoAdjunto['tmp_name'])) {
    $params['attachment'][] = [
        'filePath' => $archivoAdjunto['tmp_name']
    ];
}

// Envía el correo electrónico
$mg->messages()->send('wolfstore.shop', $params);

// Envío del correo al correo ingresado en el formulario (si se proporcionó)
if (!empty($correoRemitente)) {
    $params['to'] = $correoRemitente;
    $mg->messages()->send('wolfstore.shop', $params);
}

header('Location: index.html'); // Redirige después de enviar el correo

?>