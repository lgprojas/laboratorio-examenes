<div class="container">
<h3>Prestaciones</h3>

{literal}
    <script type="text/javascript">
    function cb(formObj) {
                if(confirm("¿Desea registrar?")) {
                    return true;                     
                } else {
                    return false;
                }
            }
    </script>
  
{/literal}

<form name="form" method="post">
    <input type="hidden" name="guardar" value="1" />
    <input type="hidden" name="idp1" value="1" />
<!-- persona 01 -->
<div class="cols-lg-12">
    <div class="cols-lg-12 text-start">
        <div class="contenedor-persona row align-items-center">
            <div class="col-md-auto">
                <i title="Persona 1" class="bi bi-person-lines-fill"></i> Persona 1
            </div>           
            <div class="col-md-auto">
                <a class="btn btn-outline-secondary position-relative btn-sm" data-toggle="modal" data-target="#modal_new1"><i title="Selección de prestaciones" class="bi bi-person-lines-fill"></i> Prestaciones <span id="count-pres_1" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark">0<span class="visually-hidden">New alerts</span></span></a>
            </div>
            <div class="col col-md-2">
            Tipo / N° Doc. <select name="tdoc1" class="tdoc form-select col-lg-2">
                                <option value="1">RUN</option>
                                <option value="2">Pasaporte</option>
                           </select>
            </div>
            <div class="col col-lg-2">
                RUN/Pasaporte: <input type="text" class="form-control" name="doc1" placeholder="Ingrese RUN/Pasaporte"/>
            </div>
            <div class="col col-lg-3">
                Teléfono: <input type="text" class="form-control" name="fono1" placeholder="Ingrese teléfono"/>
            </div>
        </div>
    </div>

        <div class="modal fade" id="modal_new1">
            <div class="modal-dialog modal-xl">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Prestaciones</h5>
                      <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>            
                <div class="modal-body">
                <div class="row row-cols-2 row-cols-lg-12 p-2" style="background:#F5F5F5;border-radius: 5px;">
                <fieldset class="border rounded-3 p-3 section">
                    <legend class="float-none w-auto px-3">Baterías</legend>

                        <div class="tile">
                            <input type="checkbox" name="pres_1[]" class="pres_1" id="bat_1_1" value="1"/>
                            <label for="bat_1_1">
                                <h6>Smiling</h6>
                            </label>
                        </div>
                        <div class="tile">
                            <input type="checkbox" name="pres_1[]" class="pres_1" id="bat_1_2" value="2"/>
                            <label for="bat_1_2">
                                <h6>WhatsApp</h6>
                            </label>
                        </div>
                        <div class="tile">
                            <input type="checkbox" name="pres_1[]" class="pres_1" id="bat_1_3" value="3"/>
                            <label for="bat_1_3">
                                <h6>Time</h6>
                            </label>
                        </div>
                        
                </fieldset>
                    
                <fieldset class="border rounded-3 p-3 section">
                    <legend class="float-none w-auto px-3">Exámenes</legend>

                        <div class="tile">
                            <input type="checkbox" name="pres_1[]" class="pres_1" id="exam_1_1"  value="4"/>
                            <label for="exam_1_1">
                                <h6>Smiling</h6>
                            </label>
                        </div>
                        <div class="tile">
                            <input type="checkbox" name="pres_1[]" class="pres_1" id="exam_1_2"  value="5"/>
                            <label for="exam_1_2">
                                <h6>WhatsApp</h6>
                            </label>
                        </div>
                        <div class="tile">
                            <input type="checkbox" name="pres_1[]" class="pres_1" id="exam_1_3" value="6"/>
                            <label for="exam_1_3">
                                <h6>Time</h6>
                            </label>
                        </div>
                        
                </fieldset>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="modal-footer">
                <button type="button" id="limpiar_modal1" class="btn btn-primary" data-dismiss="modal">Listo</button>
            </div>
        </div>
        </div>
        </div>
    <!--/persona 01 -->
    
    <!-- persona 02 -->

    <div class="cols-lg-12 text-start">
        <div class="contenedor-persona row align-items-center">
            <div class="col-md-auto">
                <i title="Persona 2" class="bi bi-person-lines-fill"></i> Persona 2
            </div>           
            <div class="col-md-auto">
                <a class="btn btn-outline-secondary position-relative btn-sm" data-toggle="modal" data-target="#modal_new2"><i title="Selección de prestaciones" class="bi bi-person-lines-fill"></i> Prestaciones <span id="count-pres_2" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark">0<span class="visually-hidden">New alerts</span></span></a>
            </div>
            <div class="col col-md-2">
            Tipo / N° Doc. <select name="tdoc2" class="tdoc form-select col-lg-2">
                                <option value="1">RUN</option>
                                <option value="2">Pasaporte</option>
                           </select>
            </div>
            <div class="col col-lg-2">
                RUN/Pasaporte: <input type="text" class="form-control" name="doc2" placeholder="Ingrese RUN/Pasaporte"/>
            </div>
            <div class="col col-lg-3">
                Teléfono: <input type="text" class="form-control" name="fono2" placeholder="Ingrese teléfono"/>
            </div>
        </div>
    </div>

        <div class="modal fade" id="modal_new2">
            <div class="modal-dialog modal-xl">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Prestaciones</h5>
                      <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>            
                <div class="modal-body">
                <div class="row row-cols-2 row-cols-lg-12 p-2" style="background:#F5F5F5;border-radius: 5px;">
                <fieldset class="border rounded-3 p-3 section">
                    <legend class="float-none w-auto px-3">Baterías</legend>

                        <div class="tile">
                            <input type="checkbox" name="pres_2[]" class="pres_2" id="bat_2_1" value="1"/>
                            <label for="bat_2_1">
                                <h6>Smiling</h6>
                            </label>
                        </div>
                        <div class="tile">
                            <input type="checkbox" name="pres_2[]" class="pres_2" id="bat_2_2" value="2"/>
                            <label for="bat_2_2">
                                <h6>WhatsApp</h6>
                            </label>
                        </div>
                        <div class="tile">
                            <input type="checkbox" name="pres_2[]" class="pres_2" id="bat_2_3" value="3"/>
                            <label for="bat_2_3">
                                <h6>Time</h6>
                            </label>
                        </div>
                        
                </fieldset>
                    
                <fieldset class="border rounded-3 p-3 section">
                    <legend class="float-none w-auto px-3">Exámenes</legend>

                        <div class="tile">
                            <input type="checkbox" name="pres_2[]" class="pres_2" id="exam_2_1" value="4"/>
                            <label for="exam_2_1">
                                <h6>Smiling</h6>
                            </label>
                        </div>
                        <div class="tile">
                            <input type="checkbox" name="pres_2[]" class="pres_2" id="exam_2_2" value="5"/>
                            <label for="exam_2_2">
                                <h6>WhatsApp</h6>
                            </label>
                        </div>
                        <div class="tile">
                            <input type="checkbox" name="pres_2[]" class="pres_2" id="exam_2_3" value="6"/>
                            <label for="exam_2_3">
                                <h6>Time</h6>
                            </label>
                        </div>
                        
                </fieldset>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="modal-footer">
                <button type="button" id="limpiar_modal1" class="btn btn-primary" data-dismiss="modal">Listo</button>
            </div>
        </div>
        </div>
        </div>
    <!--/persona 02 -->
    
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary" onclick='return cb(this);'>Agendar</button>
    </div>
    </div>
</form>
</div>
