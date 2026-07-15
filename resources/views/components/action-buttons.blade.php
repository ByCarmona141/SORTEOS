<td class="text-center">
    <div class="btn-group" role="group" aria-label="Basic mixed styles example">

        <form
            action="{{ route($routeName . '.edit', [$routeName => $params])}}"
            method="get">
            <button type="submit" class="btn btn-sm btn-warning" title="Editar">
                <i class="fa fa-pencil" aria-hidden="true"></i>
            </button>&nbsp;
        </form>

        <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                data-bs-target="#ConfirmModal-{{$params->id}}"
                title="Eliminar">
            <i class="fa fa-trash" aria-hidden="true"></i>
        </button>

    </div>

    <!-- Modal Eliminar -->
    <div class="modal fade" id="ConfirmModal-{{$params->id}}" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                        ¿Estás seguro de eliminar el registro?
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>&nbsp;
                </div>
                <div class="modal-body">
                    Asegurese de eliminar el registro deseado ya que no se podrá revertir.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>&nbsp;
                    <form action="{{route($routeName . '.destroy', [$routeName => $params->id])}}"
                          method="post">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="btn btn-danger">Eliminar</button>&nbsp;
                    </form>
                </div>
            </div>
        </div>
    </div>
</td>
