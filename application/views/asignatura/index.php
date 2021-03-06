<div class="container-fluid">
  <div class="row">
        <div class="col-md-6">
            <div class="btn-group btn-group-md">
                <a href="<?php echo base_url(); ?>asignatura/agregar" ><button type='button' class='btn btn-primary' title="Agregar asignatura"><span class="glyphicon glyphicon-copy"></span> Agregar asignatura</button></a>
            </div>
        </div>
        <div class="col-md-6 text-right">
            <form action="" class="search-form">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" name="search" id="search" title="Buscar" placeholder="Nombre o Clave de Carrera">
                    <span class="glyphicon glyphicon-search form-control-feedback"></span>
                </div>
            </form>
        </div>
  </div>
</div>

<table id="card-table" class="table table-striped">
    <thead>
      <tr>
        <th>CLAVE</th>
        <th>NOMBRE DE LA ASIGNATURA</th>
        <th>PROFESOR</th>
        <th>OPCIONES</th>
      </tr>
    </thead>
    <tbody>
<?php if($resultado){
      foreach($resultado->result() as $row){ ?>
        <tr>
            <td><?= $row->clave; ?></td>
            <td><?= $row->nombre_as; ?></td>
            <td><?= $row->idProfesor; ?></td>
            <td>
            <a href='<?= base_url().'asignatura/modificar/'.$row->idAsignatura ?>'><button type='button' class='btn btn-warning' title="Modificar"><span class="glyphicon glyphicon-pencil"></span></button></a>
            <?php  if($row->idEstatus==3){ ?>
              <a href="#" data-href='<?= base_url().'asignatura/baja/'.$row->idAsignatura ?>' data-toggle="modal" data-target="#confirm-delete"><button type='button' class='btn btn-danger' title="Dar de baja"><span class="glyphicon glyphicon-paste"></span></button></a>
            <?php }?>
            </td>
		</tr>
<?php  }	

}?>
    </tbody>
</table>
	<p><center><?=$pagination?></center></p>
