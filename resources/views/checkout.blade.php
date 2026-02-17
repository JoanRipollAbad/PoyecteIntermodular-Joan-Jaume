
@extends('layouts.app') {{-- Asumiendo que app.blade.php está en resources/views/layouts/ --}}

@section('title', 'Finalizar Compra')

@section('styles')
    <!-- Cargamos Bootstrap y el CSS específico que usaba el HTML original -->
    <link href="{{ asset('css/boostrap.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/product.css') }}" />
    
    <style>
      /* Estilos específicos para esta página */
      .form-label {
        font-weight: bold;
        color: #222;
      }
      .form-control:focus {
        border: 2px solid black;
        box-shadow: 0 0 0 0.25rem rgba(126, 217, 199, 0.5);
      }
      fieldset {
        border: 1px solid #ccc;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
      }
      legend {
        width: auto;
        padding: 0 10px;
        font-size: 1.2rem;
        font-weight: bold;
      }
      .oculto {
        display: none;
      }
      
      /* Ajuste para que el contenido no choque con el sidebar de la plantilla */
      .main-wrapper-checkout {
        padding: 20px;
      }
    </style>
@endsection

@section('content')
    <div class="main-wrapper-checkout">
      {{-- El h1 del header original lo podemos poner aquí si queremos un subtítulo, 
           ya que el Header de la plantilla ya dice "JJ-SECURITY" --}}
      <h2 class="text-center mb-4">Pago Seguro</h2>

      <div class="container py-5" id="contenido-principal">
        <!-- SECCIÓN 1: USUARIO REGISTRADO (Confirmación rápida) -->
        <section id="vista-registrado" class="tarjeta-principal-contenedor shadow-lg bg-white p-4 rounded-4 oculto" aria-labelledby="titulo-confirmacion">
          <h2 id="titulo-confirmacion" class="text-center mb-4">Confirmar Pedido</h2>
          <div class="alert alert-info text-center" role="status">
              Bienvenido de nuevo, <strong>{{ Auth::user()->name ?? 'Joan Ripoll' }}</strong>. Usaremos tus datos guardados.
          </div>

          <div class="row g-4">
            <div class="col-md-6">
              <h3>Envío a:</h3>
              <p>Calle Falsa 123, 28001 Madrid<br />España</p>
            </div>
            <div class="col-md-6 text-md-end">
              <h3>Método de pago:</h3>
              <p>Visa terminada en **** 4422</p>
            </div>
          </div>

          <hr aria-hidden="true" />

          <div class="d-flex justify-content-between align-items-center mb-4">
            <span class="fs-4">Total a pagar:</span>
            <span class="fs-2 fw-bold" style="color: #7ED9C7">129.99€</span>
          </div>

          <div class="d-grid gap-3">
            <button class="btn btn-lg text-white py-3 fw-bold" style="background-color: #000; border-radius: 30px">CONFIRMAR Y PAGAR AHORA</button>
            <button class="btn btn-outline-danger" style="border-radius: 30px" onclick="window.history.back()">Cancelar compra</button>
          </div>
        </section>

        <!-- SECCIÓN 2: USUARIO NO REGISTRADO (Formulario completo) -->
        <section id="vista-invitado" class="tarjeta-principal-contenedor shadow-lg bg-white p-4 rounded-4" aria-labelledby="titulo-pago">
          <h2 id="titulo-pago" class="text-center mb-4">Datos de Envío y Pago</h2>

          <form id="form-pago" action="{{ route('checkout.process') }}" method="POST">
            @csrf {{-- Token de seguridad obligatorio en Laravel --}}
            
            <!-- Datos Personales -->
            <fieldset>
              <legend>Información Personal</legend>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="nombre" class="form-label">Nombre Completo</label>
                  <input type="text" id="nombre" name="nombre" class="form-control" required placeholder="Ej: Maria García" />
                </div>
                <div class="col-md-6 mb-3">
                  <label for="email" class="form-label">Correo Electrónico</label>
                  <input type="email" id="email" name="email" class="form-control" required placeholder="nombre@ejemplo.com" />
                </div>
              </div>
            </fieldset>

            <!-- Dirección -->
            <fieldset>
              <legend>Dirección de Envío</legend>
              <div class="mb-3">
                <label for="direccion" class="form-label">Dirección de la calle</label>
                <input type="text" id="direccion" name="direccion" class="form-control" required placeholder="Calle, número, piso..." />
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="ciudad" class="form-label">Ciudad</label>
                  <input type="text" id="ciudad" name="ciudad" class="form-control" required />
                </div>
                <div class="col-md-6 mb-3">
                  <label for="cp" class="form-label">Código Postal</label>
                  <input type="text" id="cp" name="cp" class="form-control" required pattern="[0-9]{5}" />
                </div>
              </div>
            </fieldset>

            <!-- Pago -->
            <fieldset>
              <legend>Método de Pago</legend>
              <div class="mb-3">
                <label for="tarjeta" class="form-label">Número de Tarjeta</label>
                <input type="text" id="tarjeta" name="tarjeta" class="form-control" required placeholder="0000 0000 0000 0000" />
                <small class="text-muted">Aceptamos Visa, MasterCard y Amex.</small>
              </div>
              <div class="row">
                <div class="col-6 mb-3">
                  <label for="caducidad" class="form-label">Caducidad (MM/AA)</label>
                  <input type="text" id="caducidad" name="caducidad" class="form-control" required placeholder="12/26" />
                </div>
                <div class="col-6 mb-3">
                  <label for="cvv" class="form-label">CVV</label>
                  <input type="text" id="cvv" name="cvv" class="form-control" required placeholder="123" />
                </div>
              </div>
            </fieldset>

            <div class="mt-4 d-grid">
              <button type="submit" class="btn btn-lg text-white py-3 fw-bold" style="background-color: #000; border-radius: 30px">FINALIZAR COMPRA (129.99€)</button>
            </div>
          </form>
        </section>

        <!-- BOTÓN DE PRUEBA -->
        <div class="text-center mt-4">
          <button class="btn btn-sm btn-secondary" onclick="alternarVista()">Alternar simulación (Registrado / Invitado)</button>
        </div>
      </div>
    </div>
@endsection

@section('scripts')
    <script>
      function alternarVista() {
        const vRegistrado = document.getElementById('vista-registrado')
        const vInvitado = document.getElementById('vista-invitado')

        vRegistrado.classList.toggle('oculto')
        vInvitado.classList.toggle('oculto')
      }

      document.getElementById('form-pago').addEventListener('submit', function (e) {
        // e.preventDefault(); // Comenta esto si quieres que el formulario se envíe realmente
        alert('¡Compra realizada con éxito! Recibirás un correo de confirmación.');
      })
    </script>
@endsection
