<?php echo form_open(base_url().'profesor/guardar', 'class="form-horizontal" role="form" id="form"'); ?>

  <div class="form-group">
    <label for="nombre_pr" class="col-lg-2 control-label">Nombre</label>
    <div class="col-lg-10">
      <input type="text" class="form-control" id="nombre_pr" name="nombre_pr" value="<?php echo set_value('nombre_pr'); ?>"
             placeholder="Nombre">
      <?php echo form_error('nombre_pr'); ?>
    </div>
  </div>

  <div class="form-group">
    <label for="aPaterno_pr" class="col-lg-2 control-label">Apellido Paterno</label>
    <div class="col-lg-10">
      <input type="text" class="form-control" id="aPaterno_pr" name="aPaterno_pr" value="<?php echo set_value('aPaterno_pr'); ?>"
             placeholder="Apellido Paterno">
      <?php echo form_error('aPaterno_pr'); ?>
    </div>
  </div>

  <div class="form-group">
    <label for="aMaterno_pr" class="col-lg-2 control-label">Apellido Materno</label>
    <div class="col-lg-10">
      <input type="text" class="form-control" id="aMaterno_pr" name="aMaterno_pr" value="<?php echo set_value('aMaterno_pr'); ?>"
             placeholder="Apellido Materno">
      <?php echo form_error('aMaterno_pr'); ?>
    </div>
  </div>

  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <button type="submit" class="btn btn-success" title="Agregar">Agregar</button>
      <a href="<?php echo base_url(); ?>profesor" class="btn btn-primary" title="Cancelar">Cancelar</a>
    </div>
  </div>

<?php echo form_close(); ?>