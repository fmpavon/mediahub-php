<?php

use Daw\Mediahub\AlertTypes;
use Daw\Mediahub\Controllers\UsuarioController;
use Daw\Mediahub\Models\Usuario;

require('../vendor/autoload.php');

session_start();

if (!$_SESSION['user']) {
    header("Location: ../login.php?message=<b>Credenciales+caducadas</b>.+Inicie+sesión+de+nuevo.");
}

/**
 * @var Usuario $user
 */
$user = $_SESSION['user'];

use Daw\Mediahub\SidebarOrigins;
use Daw\Mediahub\UIBuilder;

UIBuilder::doctype('light', 'es');
UIBuilder::head('Inicio', '../resources/styles/style.css');

?>

<body class='d-flex flex-nowrap'>
    <?php
    //Lógica de ajustes
    $settingsAlert = '';

    if (isset($_FILES['formFile']) && $_FILES['formFile']['error'] === UPLOAD_ERR_OK) {
        $allowedExtensions = ['png', 'jpg', 'jpeg'];
        $targetDirectory = '../resources/images/profilePicture/';
        $fileNameText = $user->id;

        $fileExtension = strtolower(pathinfo($_FILES['formFile']['name'], PATHINFO_EXTENSION));

        // Comprobar extensión de archivo
        if (in_array($fileExtension, $allowedExtensions)) {
            // Nombre del archivo
            $newFileName = $fileNameText . '.png';

            // Nuevo tamaño y transformación a PNG
            $tmpFilePath = $_FILES['formFile']['tmp_name'];
            $image = imagecreatefromstring(file_get_contents($tmpFilePath));
            $resizedImage = imagescale($image, 512, 512);
            imagedestroy($image);
            imagepng($resizedImage, $targetDirectory . $newFileName);
            imagedestroy($resizedImage);

            $settingsAlert = UIBuilder::alert(AlertTypes::SUCCESS->value, 'Nueva imágen de perfil actualizada de forma correcta.');
        } else {
            $settingsAlert = UIBuilder::alert(AlertTypes::DANGER->value, '<b>Error</b>. La imagen especificada contiene errores o su extensión no es compatible.');
        }
    }

    if (isset($_POST['newName'])) {
        if ($_POST['newName'] == '' || $_POST['newUsername'] == '') {
            $settingsAlert = UIBuilder::alert(AlertTypes::DANGER->value, '<b>Todos los campos son obligatorios</b>. Se han mantenido los datos anteriores.');
        } else {
            if ($user->username != $_POST['newUsername'] && UsuarioController::getUserByUsername($_POST['newUsername']) != null) {
                $settingsAlert = UIBuilder::alert(AlertTypes::DANGER->value, '<b>Nombre de usuario no disponible</b>. Se han mantenido los datos anteriores.');
            } else {
                $user->name = $_POST['newName'];
                $user->username = $_POST['newUsername'];
                if (UsuarioController::modifyUser($user)) {
                    $_SESSION['user'] = UsuarioController::getUserByUsername($user->username);
                    $settingsAlert = UIBuilder::alert(AlertTypes::SUCCESS->value, 'Cambios guardados correctamente.');
                } else {
                    $settingsAlert = UIBuilder::alert(AlertTypes::WARNING->value, '<b>Error desconocido</b>. Contacte con soporte.');
                }
            }
        }
    }

    if (isset($_POST['newPassword'])) {
        if ($_POST['newPassword'] == '') {
            $settingsAlert = UIBuilder::alert(AlertTypes::DANGER->value, '<b>Debes indicar una nueva contraseña</b>. Se han mantenido la contraseña anterior.');
        } else {
            $user->password = password_hash( $_POST['newPassword'], PASSWORD_DEFAULT);
            if (UsuarioController::modifyUser($user)) {
                $_SESSION['user'] = UsuarioController::getUserByUsername($user->username);
                $settingsAlert = UIBuilder::alert(AlertTypes::SUCCESS->value, 'Contraseña modificada correctamente.');
            } else {
                $settingsAlert = UIBuilder::alert(AlertTypes::WARNING->value, '<b>Error desconocido</b>. Contacte con soporte.');
            }
        }
    }

    UIBuilder::svgIcons();
    UIBuilder::sidebar($_SESSION['user']);
    ?>
    <main>
        <h1>Ajustes de usuario</h1>
        <br>
        <?php
        echo $settingsAlert;
        ?>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">
                    👤 Cuenta
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">
                    🔒 Seguridad
                </button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                <br>
                <form action="settings.php" method="post">
                    <h4>Datos personales</h4>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="newName" name="newName" placeholder="Francisco" value="<?php echo $user->name ?>">
                        <label for="floatingInput">Nombre</label>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text">@</span>
                        <div class="form-floating">
                            <input type="text" class="form-control" id="newUsername" name="newUsername" placeholder="Username" value="<?php echo $user->username ?>">
                            <label for="floatingInputGroup1">Usuario</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Guardar cambios</button>
                </form>
                <br>
                <form action="settings.php" method="post" enctype="multipart/form-data">
                    <h4>Imagen de perfil</h4>
                    <div class="mb-3">
                        <label for="formFile" class="form-label">Archivo .PNG, .JPG o .JPEG</label>
                        <input class="form-control" type="file" id="formFile" name="formFile" accept="image/png, image/jpeg">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Actualizar imagen</button>
                </form>
            </div>
            <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                <br>
                <form action="settings.php" method="post">
                    <h4>Cambiar contraseña</h4>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="newPassword" name="newPassword">
                        <label for="floatingInput">Nueva contraseña</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Cambiar contraseña</button>
                </form>
            </div>
        </div>
    </main>
</body>
