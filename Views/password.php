<?php  
require('../Estructure/header.php');
?>

<div class="row search">
    <input id="txt-search" name="txt-search" class="hide" type="search" placeholder="Buscar">
    <button id="btn-search" class="btn" onclick="busqueda('../Controllers/ctrl_password.php')">
        <i class="material-icons">search</i>
    </button>
</div>

<div id="content" class="center-align">
    <h4>Asignar Password</h4>

    <div class="row">
        <form action="../Controllers/ctrl_password.php" method="post" class="col l12 m12 s12">
            <div class="row">
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                    <select required name="SMPerfil" id="SMPerfil">
                        <option value="" disabled selected>Seleccione</option>"
                        <?php
                            include_once ('../Core/connection.php');

                            $sql = ("SELECT * FROM `pys_perfil` WHERE `est`= 1 ORDER BY `nombrePerfil`;");

                            $cs = mysqli_query($connection,$sql);

                            $numero_registro=mysqli_num_rows($cs);
                            
                            if ($numero_registro==0){
                                echo "<script> alert ('No hay categorias registradas en la base de datos');</script>";
                            }
                            while ($fila = mysqli_fetch_array($cs)){
                                echo "<option value='". $fila["idPerfil"] ."'> ". $fila["nombrePerfil"] ." </option>";
                            }
                        ?>
                    </select>
                    <label>Perfil</label>
                </div>
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                    <select name="SMPersona" id="SMPersona"  onchange="cargaSelect('#SMPersona','../Controllers/ctrl_password.php','#sltDinamico')">
                    <option value="" disabled selected>Seleccione</option>"
                        <?php

                            $sql1 = ("SELECT * FROM `pys_personas` LEFT OUTER JOIN pys_login ON pys_personas.idPersona = pys_login.idPersona WHERE pys_login.idPersona IS NULL and pys_personas.est = 1 ORDER BY `apellido1`");

                            $cs1 = mysqli_query($connection,$sql1);

                            $numero_registro1=mysqli_num_rows($cs1);
                            
                            if ($numero_registro1==0){
                                echo "<script> alert ('No hay categorias registradas en la base de datos');</script>";
                            }
                            while ($fila1 = mysqli_fetch_array($cs1)){
                                echo "<option value='". $fila1[0] ."'> ". $fila1["apellido1"] ." ". $fila1["apellido2"] ." ". $fila1["nombres"] ." </option>";
                            }
                            mysqli_close($connection);
                        ?>
                    </select>
                    <label>Usuario</label>
                </div>
                <div id="sltDinamico" class="col l6 m6 s12 offset-l3 offset-m3"></div>
                <div class="input-field col l6 m6 s12 offset-l3 offset-m3">
                    <input name="txtloginPer" type="text" class="validate" required value="<?php echo $var4?>" />
                    <label for="txtloginPer">Usuario*</label>
                </div>
                <div class="input-field col l3 m3 s12 offset-l3 offset-m3">
                    <input id="txtpassPer" name="txtpassPer" type="password"  class="validate" required  value="Conecta-TE" />
                    <label for="txtpassPer">Password*</label>
                </div>
                <div class="input-field col l3 m3 s12 ">
                    <input id="txtpass1Per" name="txtpass1Per" type="password"  class="validate" required  value="" />
                    <label for="txtpass1Per">Confirmar Password*</label>
                    <span id="passText" class="red-text helper-text hide">Las contraseñas no coinciden.</span>
                    <a onmouseover="M.toast({html: 'Default Pass: Conecta-TE.'})" class="help teal-text text-accent-4"><i class="material-icons small">help_outline</i></a>
                </div>
            </div>
            <button id="btn-pass" class="btn waves-effect waves-light disabled" type="submit" name="action">Guardar</button>
        </form>
    </div>
</div>

<div class="row">
    <div id="div_dinamico" name="div_dinamico" class="col l10 m10 s12 offset-l1 offset-m1">
    </div>
</div>

<!-- Modal Structure -->
<div id="modalEquipo" class="modal">
    <?php
        //require('');
    ?>
</div>
<?php
require('../Estructure/footer.php');