<?php 

function notBlank(?string $value): bool 
{
    return is_string($value) && trim($value) !== '';
}

function validName(string $name): bool 
{
    return (bool) preg_match('/^[\p{L}]+$/u', $name);
}

function validEmail(string $email): bool 
{
    return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
}

function fileNotBlank(array $file): bool 
{
    if(!isset($file['error'])) {
        trigger_error('La clé "error" n\'a pas été trouvé dans le tableau $file', E_USER_ERROR);
    }

    return $file['error'] === UPLOAD_ERR_OK;
}

function validFileSize(array $file, int $maxSize): bool 
{
    if(!isset($file['size'])) {
        trigger_error('Clé "size" manquante', E_USER_ERROR);
    }

    return $file['size'] <= $maxSize;
}

function validFileType(array $file, array $allowedMimes): bool 
{
    if(!isset($file['type'])) {
        trigger_error('La clé "type" n\'a pas été trouvé dans le tableau $file', E_USER_ERROR);
    }

    return in_array($file['type'], $allowedMimes);
}


