
    <h4>Marcar como terminado</h4>
    <h6 class="red-text">¡Importante!</h6> 
    <div class="row">
        <form id="actForm" method="post" action="../Controllers/ctrl_missolicitudes.php" class="col l12 m12 s12"
            autocomplete="off">
            <div class="row">
                <input id="cod" name="cod" type="hidden" value="">
                <p>Al hacer clic en <b>Terminado</b> usted dejará de ver este producto/servicio y estará indicando que ha culminado su labor para el mismo. Tenga en cuenta que esto no lo deja como cerrado oficialmente.</p>
                <p>Por favor antes de hacer clic verifique que ya reportó todos sus tiempos y que no va a realizar nada más para este producto/servicio.</p>
            </div>
            <button class="btn waves-effect grey darken-4 waves-light " name="btnInactivar" type="submit">Terminado</button>
            <div class="row">
                <p>Si desea volver a activar este producto/servicio debe comunicarse con la mesa de servicio.</p>
            </div>
        </form>
    </div>

