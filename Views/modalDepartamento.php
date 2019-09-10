<div class="modal-content center-align">
	<h4>Editar/Eliminar Departamento</h4>
	<div class="row">
		<form id="actForm" action="../Controllers/ctrl_departamento.php" method="post" class="col l12 m12 s12">
			<div class="row">
				<input id="cod" name="cod" type="hidden">
				<input id="val" name="val" type="hidden">
				<?php
					require ('../Controllers/ctrl_departamento.php');
				?>
				<div class="input-field col l10 m10 s12 offset-l1 offset-m1">
                    <select id="selEntidad" name="selEntidad" class="select" required onchange="cargaSelect('#selEntidad.select','../Controllers/ctrl_departamento.php','#sltDinamicoModal')">
                        <option value="<?php echo $idEntidad?>" selected><?php echo $nomEntidad?></option>
                        <?php
                            require ('../Core/connection.php');

                            $consulta = "SELECT * FROM pys_entidades WHERE est = '1' ORDER BY nombreEnt;";

                            $resultado = mysqli_query($connection, $consulta);

                            $count = mysqli_num_rows($resultado);
            
                            if ($count==0){
                                echo "<script> alert ('No hay categorías registradas en la base de datos');</script>";
                            }else {
                                while ($datos = mysqli_fetch_array($resultado)){
                                    echo "<option value='". $datos["idEnt"] ."'> ". $datos["nombreEnt"] ." </option>";
                                }
                            }
                        ?>
                    </select>
                    <label>Entidad/Empresa</label>
                </div>
                <div id="sltDinamicoModal" class="col l10 m10 s12 offset-l1 offset-m1">
                    <div class="input-field col l12 m12 s12">
                        <select name="selFacultad" id="selFacultad" required>
                            <option value="<?php echo $idFacultad?>" selected><?php echo $nomFacultad?></option>
                        </select>
                        <label for="selFacultad">Facultad</label>
                    </div>
                    <input id="txtNomFacultad" name="txtNomFacultad" type="hidden" class="validate" value="<?php echo $nomFacultad?>">
                </div>
                <div class="input-field col l10 m10 s12 offset-l1 offset-m1">
                    <input id="txtNomDepartamento" name="txtNomDepartamento" type="text" class="validate" required value="<?php echo $nomDepartamento?>">
                    <label for="txtNomDepartamento" class="active">Nombre del departamento</label>
                </div>
			</div>
			<button class="btn waves-effect red darken-4 waves-light " type="submit" name="action" onclick="suprimir('2','Controllers/ctrl_departamento.php')">Eliminar</button>
			<button class="btn waves-effect waves-light" type="submit" name="action" onclick="actualiza('1','Controllers/ctrl_departamento.php')">Actualizar</button>
		</form>
	</div>
</div>