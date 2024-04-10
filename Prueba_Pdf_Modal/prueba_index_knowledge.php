<?php
if ($action == 'upload_file') {  // Verifica si la acción es 'upload_file'

    $id_wikisys = $_POST['id_wikisys']; // Obtenemos el id

    $folderpath_public = '/uploads/files'; // La ruta donde se van a subir los archivos

    $folderpath_private = get_sys_dir_nodev() . $folderpath_public; // Función que obtiene la ruta del directorio para mayor seguridad

    if (!is_dir($folderpath_private)) { // Si el directorio no existe, creamos uno
        $res = mkdir($folderpath_private, 0755, true);
        if (!$res) {
            ret(['error' => 'Ocurrió un error al crear el directorio']);
        }
    }

    // Verifica si se ha subido algún archivo
    if (!isset($_FILES['file'])) {
        ret(['error' => 'No se ha recibido ningún archivo']);
    }

    // Obtiene la extensión del archivo y la verificamos
    $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

    // Verifica si la extensión es válida
    if (!in_array($ext, ['jpg', 'png', 'jpeg', 'pdf'])) {
        ret(['error' => 'Sólo se permiten subir archivos con las extensiones JPG, PNG, JPEG o PDF.']);
    }

    // Nombre de archivo
    $filename = 'wiki-' . $id_wikisys . '.' . $ext;

    // Ruta del archivo en el directorio público
    $filepath_public = $folderpath_public . '/' . $filename;
    // Ruta del archivo en el directorio privado
    $filepath_private = $folderpath_private . '/' . $filename;

    // Mueve el archivo subido al directorio privado
    if (!move_uploaded_file($_FILES['file']['tmp_name'], $filepath_private)) {
        ret(['error' => 'Ha ocurrido un error al subir el archivo.']);
    }

    // Datos que subiremos a la base de datos
    $data = [
        'file_path'      => $filepath_public,
        'upload_time'   => date('Y-m-d H:i:s'),
        'id_user'       => sys::$curr_user_id, // Asegúrate de definir correctamente este valor o cambiarlo según tu lógica de usuario actual
    ];

    // Inserta la información del archivo en la base de datos
    // Aquí deberías tener una función que maneje la inserción en la base de datos, reemplaza 'insert_file_to_db' con esa función
    insert_file_to_db($data);

    ret();
}
?>
