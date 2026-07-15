@if($isEdit)
    <div class="col-12 text-center">
        <button type="submit" class="btn btn-success">Actualizar</button>
        <a type="button" class="btn btn-danger" href="{{ route($routeName . '.index') }}">Cancelar</a>
    </div>
@else
    <div class="col-md-12 mb-2 text-center">
        <button type="submit" class="btn btn-success" name="accion" value="continuar">
            <i class="fa fa-save" aria-hidden="true"></i>
            Guardar y continuar
            <i class="fa fa-arrow-right" aria-hidden="true"></i>
        </button>
        <button type="submit" class="btn btn-primary" name="accion" value="regresar">
            <i class="fa fa-save" aria-hidden="true"></i>
            Guardar y regresar
            <i class="fa fa-undo" aria-hidden="true"></i>
        </button>
        <a class="btn btn-danger" href="{{ route($routeName . '.index') }}">Cancelar</a>
    </div>
@endif
