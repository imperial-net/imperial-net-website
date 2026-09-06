<?php
/**
 * Endpoint API para envío de emails - Portfolio MJI
 */

// Headers CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');

// 1. Cargar dependencias PRIMERO
require_once __DIR__ . '/../php/utils/helpers.php';
require_once __DIR__ . '/../email/EmailService.php';

// 2. Cargar variables de entorno
$envConfigPath = __DIR__ . '/../../../env-config.php'; // Fuera de public_html
$envConfigPathAlt = __DIR__ . '/../../env-config.php'; // Dentro de public_html
$envPath = __DIR__ . '/../../../.env'; // Desarrollo local

if (file_exists($envConfigPath)) {
    require_once $envConfigPath;
} elseif (file_exists($envConfigPathAlt)) {
    require_once $envConfigPathAlt;
} elseif (file_exists($envPath)) {
    loadEnv($envPath);
}

// 3. Configuración de errores
if (getenv('NODE_ENV') !== 'production') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// 4. Cargar configuración
$config = require __DIR__ . '/../php/config/mail.php';

// Manejar preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Solo permitir POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, null, 'Método no permitido. Use POST.', 405);
}

// --- Filtro anti-bot: el POST tiene que venir de nuestro propio sitio ---
// Un bot que pega directo al endpoint no manda un Origin/Referer válido.
$allowedHosts = ['imperial-net.com', 'www.imperial-net.com'];
$origin  = $_SERVER['HTTP_ORIGIN']  ?? '';
$referer = $_SERVER['HTTP_REFERER'] ?? '';
$source  = $origin !== '' ? $origin : $referer;
$sourceHost = $source !== '' ? parse_url($source, PHP_URL_HOST) : '';

if (!in_array($sourceHost, $allowedHosts, true)) {
    // Respondemos 200 "OK" para que el bot crea que funcionó, pero NO enviamos nada.
    logMessage('🤖 POST rechazado: origen no autorizado (origin="' . $origin . '", referer="' . $referer . '")', 'WARNING');
    jsonResponse(true, 'Mensaje enviado correctamente', ['timestamp' => date('c')], 200);
}

try {
    logMessage('📧 API Portfolio: Recibiendo solicitud de contacto...', 'INFO');
    
    // Detectar si es JSON o FormData
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    
    if (strpos($contentType, 'application/json') !== false) {
        // Datos JSON
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        if (!$data) {
            throw new Exception('Datos JSON inválidos');
        }

        // 🪤 Honeypot anti-spam (campo trampa invisible)
        $honeypot = trim($data['website'] ?? '');

        // 🛡️ Ticket de Cloudflare Turnstile
        $turnstileToken = $data['cf-turnstile-response'] ?? '';

        // Separar nombre en firstName y lastName
        $nameParts = explode(' ', trim($data['name'] ?? ''), 2);
        $consultationData = [
            'firstName' => $nameParts[0] ?? '',
            'lastName' => $nameParts[1] ?? '',
            'email' => $data['email'] ?? '',
            'phone' => '',
            'message' => $data['message'] ?? '',
            'additionalData' => '',
            'hasAdditionalFields' => 'false'
        ];
    } else {
        // FormData
        // 🪤 Honeypot anti-spam (campo trampa invisible)
        $honeypot = trim($_POST['website'] ?? '');

        // 🛡️ Ticket de Cloudflare Turnstile
        $turnstileToken = $_POST['cf-turnstile-response'] ?? '';

        $nameParts = explode(' ', trim($_POST['name'] ?? ''), 2);
        $consultationData = [
            'firstName' => $nameParts[0] ?? '',
            'lastName' => $nameParts[1] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => '',
            'message' => $_POST['message'] ?? '',
            'additionalData' => '',
            'hasAdditionalFields' => 'false'
        ];
    }
    
    logMessage("📧 API Portfolio: Datos - Nombre: {$consultationData['firstName']}, Email: {$consultationData['email']}", 'INFO');

    // 🪤 Honeypot: si el campo trampa viene completo, es un bot.
    // Respondemos "200 OK" para que el bot crea que funcionó, pero NO enviamos ningún email.
    if (!empty($honeypot)) {
        logMessage('🤖 API Portfolio: Spam detectado por honeypot. Envío descartado.', 'WARNING');
        jsonResponse(true, 'Mensaje enviado correctamente', ['timestamp' => date('c')], 200);
    }

    // 🛡️ Verificación anti-bot con Cloudflare Turnstile (OBLIGATORIA).
    // Sin una verificación válida, NO se envía ningún email.
    $turnstileSecret = getenv('TURNSTILE_SECRET') ?: '';

    if (empty($turnstileSecret)) {
        // Falta la Secret Key en el servidor (env-config.php). Es un error de configuración:
        // devolvemos un error VISIBLE para que se note (tu propia prueba va a fallar) y NO mandamos nada.
        logMessage('❌ API Portfolio: TURNSTILE_SECRET no configurado en el servidor.', 'ERROR');
        jsonResponse(false, null, 'La verificación de seguridad no está disponible en este momento. Probá de nuevo más tarde.', 503);
    }

    // Pedirle a Cloudflare que valide el ticket
    $verifyData = http_build_query([
        'secret'   => $turnstileSecret,
        'response' => $turnstileToken,
        'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
    ]);

    $verifyResult = '';
    if (function_exists('curl_init')) {
        $ch = curl_init('https://challenges.cloudflare.com/turnstile/v0/siteverify');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $verifyData);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $verifyResult = curl_exec($ch);
        curl_close($ch);
    } else {
        $ctx = stream_context_create(['http' => [
            'method'  => 'POST',
            'header'  => 'Content-Type: application/x-www-form-urlencoded',
            'content' => $verifyData,
            'timeout' => 10,
        ]]);
        $verifyResult = @file_get_contents('https://challenges.cloudflare.com/turnstile/v0/siteverify', false, $ctx);
    }

    $verifyJson = json_decode($verifyResult, true);
    if (empty($verifyJson['success'])) {
        // Ticket inválido, ausente o vencido → es un bot (o no pasó la verificación).
        // Respondemos "200 OK" para que el bot crea que funcionó, pero NO enviamos nada.
        logMessage('🤖 API Portfolio: Turnstile inválido. Envío descartado.', 'WARNING');
        jsonResponse(true, 'Mensaje enviado correctamente', ['timestamp' => date('c')], 200);
    }

    // Validar campos obligatorios
    if (empty($consultationData['firstName'])) {
        jsonResponse(false, null, 'El nombre es requerido', 400);
    }
    
    if (empty($consultationData['email'])) {
        jsonResponse(false, null, 'El email es requerido', 400);
    }
    
    if (!validateEmail($consultationData['email'])) {
        jsonResponse(false, null, 'El formato del email es inválido', 400);
    }
    
    if (empty($consultationData['message'])) {
        jsonResponse(false, null, 'El mensaje es requerido', 400);
    }
    
    // Crear instancia del servicio de email
    $emailService = new EmailService();
    
    // Enviar email principal (consulta)
    logMessage('📤 API Portfolio: Enviando email de consulta...', 'INFO');
    $emailResult = $emailService->sendConsultationEmail($consultationData);
    
    if (!$emailResult['success']) {
        logMessage('❌ API Portfolio: Error al enviar email: ' . $emailResult['error'], 'ERROR');
        jsonResponse(false, ['error' => $emailResult['error']], 'Error al enviar el mensaje', 500);
    }
    
    // Enviar email de confirmación al cliente (no bloquear si falla)
    try {
        logMessage('📧 API Portfolio: Enviando confirmación al cliente...', 'INFO');
        $emailService->sendConfirmationEmail(
            $consultationData['email'],
            "{$consultationData['firstName']} {$consultationData['lastName']}"
        );
    } catch (Exception $e) {
        logMessage('⚠️ API Portfolio: No se pudo enviar confirmación: ' . $e->getMessage(), 'WARNING');
    }
    
    logMessage('✅ API Portfolio: Mensaje enviado exitosamente', 'INFO');
    
    // Respuesta exitosa
    jsonResponse(true, 'Mensaje enviado correctamente', [
        'messageId' => $emailResult['messageId'],
        'timestamp' => date('c')
    ], 200);
    
} catch (Exception $e) {
    logMessage('❌ API Portfolio: Error: ' . $e->getMessage(), 'ERROR');
    jsonResponse(false, ['error' => $e->getMessage()], 'Error al procesar la solicitud', 500);
}
?>
