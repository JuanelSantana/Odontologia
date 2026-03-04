<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /* ===================== CATΓÇôLOGOS ===================== */

        Schema::create('Especialidades', function (Blueprint $table) {
            $table->increments('id_esp'); // INT IDENTITY PRIMARY KEY
            $table->string('nom_esp', 100);
            $table->string('dsc_esp', 200)->nullable();
        });

        Schema::create('Seguros', function (Blueprint $table) {
            $table->increments('id_seg');
            $table->string('nom_seg', 100);
            $table->string('tel_seg', 20)->nullable();
        });

        Schema::create('Servicios', function (Blueprint $table) {
            $table->increments('id_srv');
            $table->string('nom_srv', 100);
            $table->text('dsc_srv')->nullable();
            $table->decimal('cst_srv', 10, 2); // MONEY
        });

        Schema::create('Tipos_Tratamiento', function (Blueprint $table) {
            $table->increments('id_ttr');
            $table->string('nom_ttr', 100);
            $table->text('dsc_ttr')->nullable();
        });

        Schema::create('Proveedores', function (Blueprint $table) {
            $table->increments('id_prv');
            $table->string('nom_prv', 100);
            $table->string('loc_prv', 100)->nullable();
            $table->string('tel_prv', 20)->nullable();
        });

        Schema::create('Materiales', function (Blueprint $table) {
            $table->increments('id_mat');
            $table->string('nom_mat', 100);
            $table->text('dsc_mat')->nullable();
            $table->integer('cnt_mat')->nullable();
            $table->decimal('cst_mat', 10, 2)->nullable();
            $table->string('tip_mat', 50)->nullable();
            $table->unsignedInteger('id_prv')->nullable();
            $table->foreign('id_prv')->references('id_prv')->on('Proveedores')->onDelete('set null');
        });

        Schema::create('Metodos_Pago', function (Blueprint $table) {
            $table->increments('id_mpa');
            $table->string('nom_mpa', 50);
        });

        Schema::create('Estado_Cita', function (Blueprint $table) {
            $table->increments('id_eci');
            $table->string('nom_eci', 50);
        });

        Schema::create('Roles', function (Blueprint $table) {
            $table->increments('id_rol');
            $table->string('nom_rol', 50);
        });

        Schema::create('Permisos', function (Blueprint $table) {
            $table->increments('id_prm');
            $table->string('nom_prm', 100);
        });

        /* ===================== PERSONAS ===================== */

        Schema::create('Pacientes', function (Blueprint $table) {
            $table->string('ced_pac', 20)->primary();
            $table->string('nom_pac', 100);
            $table->string('ape_pac', 100);
            $table->string('gen_pac', 10);
            $table->date('fec_nac_pac');
            $table->string('tel_pac', 20);
            $table->string('eml_pac', 100);
            $table->string('tip_pac', 50)->nullable();
            $table->text('cnd_sal_pac')->nullable();
            $table->unsignedInteger('id_seg')->nullable();
            $table->foreign('id_seg')->references('id_seg')->on('Seguros')->onDelete('set null');
        });

        Schema::create('Doctores', function (Blueprint $table) {
            $table->increments('id_doc');
            $table->string('nom_doc', 100);
            $table->string('ape_doc', 100);
            $table->string('ced_doc', 20)->default('N/A');
            $table->string('tel_doc', 20)->default('N/A');
            $table->string('eml_doc', 100)->default('N/A');
            $table->unsignedInteger('id_esp');
            $table->foreign('id_esp')->references('id_esp')->on('Especialidades')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('Usuarios', function (Blueprint $table) {
            $table->increments('id_usr');
            $table->string('nom_usr', 50)->unique();
            $table->string('pas_usr', 255);
            $table->unsignedInteger('id_rol');
            $table->boolean('atv_usr')->default(true);
            $table->string('nmb_usr', 100)->nullable();
            $table->foreign('id_rol')->references('id_rol')->on('Roles')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('Empleados', function (Blueprint $table) {
            $table->increments('id_emp');
            $table->string('nom_emp', 100);
            $table->string('ape_emp', 100);
            $table->string('dir_emp', 200)->nullable();
            $table->string('tel_emp', 20)->nullable();
            $table->string('crg_emp', 50)->nullable();
        });

        /* ===================== HORARIOS ===================== */

        Schema::create('Horarios_Doctores', function (Blueprint $table) {
            $table->increments('id_hdo');
            $table->unsignedInteger('id_doc');
            $table->unsignedTinyInteger('dia_semana');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->foreign('id_doc')->references('id_doc')->on('Doctores')->onDelete('cascade')->onUpdate('cascade');
        });

        /* ===================== OPERACIΓôN Y CONSULTA ===================== */

        Schema::create('Inventario', function (Blueprint $table) {
            $table->increments('id_inv');
            $table->unsignedInteger('id_mat');
            $table->unsignedInteger('id_prv');
            $table->integer('cnt_inv');
            $table->foreign('id_mat')->references('id_mat')->on('Materiales')->onDelete('no action');
            $table->foreign('id_prv')->references('id_prv')->on('Proveedores')->onDelete('no action');
        });

        Schema::create('Historial_Clinico', function (Blueprint $table) {
            $table->increments('id_hcl');
            $table->string('ced_pac', 20);
            $table->text('dig_hcl');
            $table->text('trt_prev_hcl')->nullable();
            $table->text('alg_hcl')->nullable();
            $table->text('mds_hcl')->nullable();
            $table->foreign('ced_pac')->references('ced_pac')->on('Pacientes')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('Consultas_Medicas', function (Blueprint $table) {
            $table->increments('id_con');
            $table->string('ced_pac', 20);
            $table->unsignedInteger('id_doc');
            $table->dateTime('fec_con');
            $table->text('motivo')->nullable();
            $table->text('observaciones')->nullable();
            $table->foreign('ced_pac')->references('ced_pac')->on('Pacientes');
            $table->foreign('id_doc')->references('id_doc')->on('Doctores');
        });

        Schema::create('Diagnosticos', function (Blueprint $table) {
            $table->increments('id_dia');
            $table->string('ced_pac', 20);
            $table->unsignedInteger('id_doc');
            $table->dateTime('fecha_dia');
            $table->text('descripcion')->nullable();
            $table->foreign('ced_pac')->references('ced_pac')->on('Pacientes');
            $table->foreign('id_doc')->references('id_doc')->on('Doctores');
        });

        Schema::create('Evaluaciones', function (Blueprint $table) {
            $table->increments('id_eval');
            $table->string('ced_pac', 20);
            $table->unsignedInteger('id_doc');
            $table->dateTime('fecha_eval');
            $table->text('resultado')->nullable();
            $table->foreign('ced_pac')->references('ced_pac')->on('Pacientes');
            $table->foreign('id_doc')->references('id_doc')->on('Doctores');
        });

        Schema::create('Cotizaciones', function (Blueprint $table) {
            $table->increments('id_coti');
            $table->string('ced_pac', 20);
            $table->dateTime('fecha_coti');
            $table->decimal('monto', 10, 2);
            $table->text('detalle')->nullable();
            $table->foreign('ced_pac')->references('ced_pac')->on('Pacientes');
        });

        Schema::create('Citas', function (Blueprint $table) {
            $table->increments('id_cit');
            $table->string('ced_pac', 20);
            $table->unsignedInteger('id_doc');
            $table->unsignedInteger('id_eci');
            $table->dateTime('fec_cit');
            $table->text('mtv_cit')->nullable();
            $table->text('cmt_cit')->nullable();
            $table->unsignedInteger('id_usr')->nullable();
            $table->foreign('ced_pac')->references('ced_pac')->on('Pacientes');
            $table->foreign('id_doc')->references('id_doc')->on('Doctores');
            $table->foreign('id_eci')->references('id_eci')->on('Estado_Cita')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_usr')->references('id_usr')->on('Usuarios');
        });

        Schema::create('Citas_Servicios', function (Blueprint $table) {
            $table->unsignedInteger('id_cit');
            $table->unsignedInteger('id_srv');
            $table->primary(['id_cit', 'id_srv']);
            $table->foreign('id_cit')->references('id_cit')->on('Citas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_srv')->references('id_srv')->on('Servicios')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('Tratamientos', function (Blueprint $table) {
            $table->increments('id_tra');
            $table->string('ced_pac', 20);
            $table->unsignedInteger('id_doc');
            $table->unsignedInteger('id_ttr');
            $table->unsignedInteger('id_srv');
            $table->text('dsc_tra')->nullable();
            $table->decimal('cst_tra', 10, 2);
            $table->date('fec_ini_tra');
            $table->date('fec_fin_tra')->nullable();
            $table->string('nom_tra', 100)->nullable();
            $table->string('dur_tra', 50)->nullable();
            $table->unsignedInteger('id_cit')->nullable();
            $table->foreign('ced_pac')->references('ced_pac')->on('Pacientes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_doc')->references('id_doc')->on('Doctores');
            $table->foreign('id_ttr')->references('id_ttr')->on('Tipos_Tratamiento')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_srv')->references('id_srv')->on('Servicios')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_cit')->references('id_cit')->on('Citas');
        });

        Schema::create('Tratamientos_Materiales', function (Blueprint $table) {
            $table->unsignedInteger('id_tra');
            $table->unsignedInteger('id_mat');
            $table->integer('cant_usada');
            $table->primary(['id_tra', 'id_mat']);
            $table->foreign('id_tra')->references('id_tra')->on('Tratamientos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_mat')->references('id_mat')->on('Materiales')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('Pagos', function (Blueprint $table) {
            $table->increments('id_pag');
            $table->string('ced_pac', 20);
            $table->unsignedInteger('id_cit');
            $table->unsignedInteger('id_mpa');
            $table->decimal('mnt_pag', 10, 2);
            $table->dateTime('fec_pag');
            $table->foreign('ced_pac')->references('ced_pac')->on('Pacientes');
            $table->foreign('id_cit')->references('id_cit')->on('Citas');
            $table->foreign('id_mpa')->references('id_mpa')->on('Metodos_Pago')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('Facturas', function (Blueprint $table) {
            $table->increments('id_fac');
            $table->unsignedInteger('id_pag');
            $table->string('num_fac', 50);
            $table->dateTime('fec_emis_fac');
            $table->decimal('imp_fac', 10, 2);
            $table->decimal('ttl_fac', 10, 2);
            $table->foreign('id_pag')->references('id_pag')->on('Pagos');
        });

        Schema::create('Detalle_Factura', function (Blueprint $table) {
            $table->increments('id_det');
            $table->unsignedInteger('id_fac');
            $table->unsignedInteger('id_srv');
            $table->integer('cant');
            $table->decimal('precio', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->foreign('id_fac')->references('id_fac')->on('Facturas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_srv')->references('id_srv')->on('Servicios')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('Roles_Permisos', function (Blueprint $table) {
            $table->unsignedInteger('id_rol');
            $table->unsignedInteger('id_prm');
            $table->primary(['id_rol', 'id_prm']);
            $table->foreign('id_rol')->references('id_rol')->on('Roles')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_prm')->references('id_prm')->on('Permisos')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('Tratamientos_Doctores', function (Blueprint $table) {
            $table->unsignedInteger('id_tra');
            $table->unsignedInteger('id_doc');
            $table->primary(['id_tra', 'id_doc']);
            $table->foreign('id_tra')->references('id_tra')->on('Tratamientos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_doc')->references('id_doc')->on('Doctores');
        });

        Schema::create('Servicios_Materiales', function (Blueprint $table) {
            $table->unsignedInteger('id_srv');
            $table->unsignedInteger('id_mat');
            $table->primary(['id_srv', 'id_mat']);
            $table->foreign('id_srv')->references('id_srv')->on('Servicios')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_mat')->references('id_mat')->on('Materiales')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Servicios_Materiales');
        Schema::dropIfExists('Tratamientos_Doctores');
        Schema::dropIfExists('Roles_Permisos');
        Schema::dropIfExists('Detalle_Factura');
        Schema::dropIfExists('Facturas');
        Schema::dropIfExists('Pagos');
        Schema::dropIfExists('Tratamientos_Materiales');
        Schema::dropIfExists('Tratamientos');
        Schema::dropIfExists('Citas_Servicios');
        Schema::dropIfExists('Citas');
        Schema::dropIfExists('Cotizaciones');
        Schema::dropIfExists('Evaluaciones');
        Schema::dropIfExists('Diagnosticos');
        Schema::dropIfExists('Consultas_Medicas');
        Schema::dropIfExists('Historial_Clinico');
        Schema::dropIfExists('Inventario');
        Schema::dropIfExists('Horarios_Doctores');
        Schema::dropIfExists('Empleados');
        Schema::dropIfExists('Usuarios');
        Schema::dropIfExists('Doctores');
        Schema::dropIfExists('Pacientes');
        Schema::dropIfExists('Permisos');
        Schema::dropIfExists('Roles');
        Schema::dropIfExists('Estado_Cita');
        Schema::dropIfExists('Metodos_Pago');
        Schema::dropIfExists('Materiales');
        Schema::dropIfExists('Proveedores');
        Schema::dropIfExists('Tipos_Tratamiento');
        Schema::dropIfExists('Servicios');
        Schema::dropIfExists('Seguros');
        Schema::dropIfExists('Especialidades');
    }
};
