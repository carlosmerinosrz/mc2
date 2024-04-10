<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archivos Subidos</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Archivos Subidos</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="file">Selecciona un archivo para subir:</label>
                <input type="file" class="form-control-file" id="file" name="file">
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Subir Archivo</button>
        </form>

        <hr>

        <h2>Archivos Disponibles:</h2>
        <ul>
            <?php
            // Configuración de la base de datos
            $servername = "localhost";
            $username = "root";
            $password = "";
            $database = "prueba_modals";

            // Crear conexión
            $conn = new mysqli($servername, $username, $password, $database);

            // Verificar conexión
            if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error);
            }

            if (isset($_POST['submit'])) {
                if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = 'uploads/';
                    $uploadFile = $uploadDir . basename($_FILES['file']['name']);
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
                        // Guardar información en la base de datos
                        $filePath = mysqli_real_escape_string($conn, $uploadFile);
                        $uploadTime = date('Y-m-d H:i:s');
                        $sql = "INSERT INTO uploads (file_path, upload_time) VALUES ('$filePath', '$uploadTime')";
                        if ($conn->query($sql) === TRUE) {
                            echo '<div class="alert alert-success" role="alert">El archivo se ha subido correctamente.</div>';
                        } else {
                            echo '<div class="alert alert-danger" role="alert">Hubo un error al subir el archivo a la base de datos.</div>';
                        }
                    } else {
                        echo '<div class="alert alert-danger" role="alert">Hubo un error al subir el archivo.</div>';
                    }
                } else {
                    echo '<div class="alert alert-danger" role="alert">Error al subir el archivo: ' . $_FILES['file']['error'] . '</div>';
                }
            }

            // Cerrar conexión
            $conn->close();

            $uploadDirectory = 'uploads/';
            $allowedExtensions = ['pdf', 'png', 'jpg', 'jpeg'];
            $files = array_diff(scandir($uploadDirectory), array('..', '.'));
            foreach ($files as $file) {
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                if (in_array($extension, $allowedExtensions)) {
                    echo '<li><a href="#" class="openFileModal" data-file="' . $uploadDirectory . $file . '">' . $file . '</a></li>';
                }
            }
            ?>
        </ul>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="fileModal" tabindex="-1" role="dialog" aria-labelledby="fileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fileModalLabel">Archivo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Contenedor para cargar el archivo -->
                    <div id="fileViewer"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap y jQuery JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            // Cuando se hace clic en un enlace para abrir el modal
            $('.openFileModal').click(function() {
                var file = $(this).data('file');
                var extension = file.split('.').pop().toLowerCase();
                if (extension === 'pdf') {
                    $('#fileViewer').html('<iframe src="' + file + '" frameborder="0" style="width:100%;height:500px;"></iframe>');
                } else {
                    $('#fileViewer').html('<img src="' + file + '" class="img-fluid" alt="Visualización de Imagen">');
                }
                $('#fileModal').modal('show');
            });
        });
    </script>
</body>
</html>
