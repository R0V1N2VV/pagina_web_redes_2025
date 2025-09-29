<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cripto Sim</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" type="image/png" href="favicon.png">
</head>
<body class="bg-gray-900 text-gray-100 p-0 flex flex-col min-h-screen">

  <!-- Navbar fijo -->
  <nav class="bg-gray-800 fixed w-full top-0 z-50 shadow-md">
    <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
      <h1 class="text-2xl font-bold text-green-400">Cripto Sim</h1>
      <div class="flex items-center space-x-3">
        <a href="#activos" class="bg-purple-600 px-4 py-2 rounded shadow-lg hover:bg-purple-500 transition">Criptomonedas</a>
        <a href="#bolsa" class="bg-yellow-600 px-4 py-2 rounded shadow-lg hover:bg-yellow-500 transition">Bolsa</a>
        <a href="#billetera" class="bg-blue-600 px-4 py-2 rounded shadow-lg hover:bg-blue-500 transition">Billetera</a>
        <button onclick="window.location.href='ComoInvertir.php'" 
          class="bg-green-500 hover:bg-green-600 px-4 py-2 rounded-lg text-white font-semibold">
          Cómo Invertir
        </button>

        <!-- Menú de usuario -->
        <?php if(isset($_SESSION['usuario'])): ?>
          <span class="text-yellow-400 ml-4 mr-2">👋 Hola, <?php echo $_SESSION['usuario']['nombre']; ?></span>
          <a href="usuario.php" class="bg-gray-700 px-3 py-2 rounded hover:bg-gray-600 transition">👤 Mi cuenta</a>
          <a href="logout.php" class="bg-red-600 px-3 py-2 rounded hover:bg-red-500 transition">🚪 Cerrar sesión</a>
        <?php else: ?>
          <a href="login.php" class="bg-gray-700 px-3 py-2 rounded hover:bg-gray-600 transition">🔑 Iniciar sesión</a>
          <a href="registro.php" class="bg-gray-700 px-3 py-2 rounded hover:bg-gray-600 transition">📝 Registrarse</a>
        <?php endif; ?>
      </div>
    </div>
  </nav>

  <main class="flex-grow pt-24 px-6">
    <!-- Dinero disponible -->
    <p class="mb-6 text-center">
      <span class="text-gray-400 block mb-1">USD disponible</span>
      <span id="usd" class="text-3xl font-bold text-green-400 bg-gray-800 px-4 py-2 rounded shadow-lg inline-block">$10000.00</span>
    </p>

    <!-- Sección de cripto activos -->
    <section id="activos" class="mb-16">
      <h2 class="text-xl font-semibold mb-2 text-purple-400">Criptomonedas</h2>
      <div id="cripto-list" class="space-y-4"></div>
    </section>

    <!-- Sección de bolsa -->
    <section id="bolsa" class="mb-16">
      <h2 class="text-xl font-semibold mb-2 text-yellow-400">Bolsa</h2>
      <div id="bolsa-list" class="space-y-4"></div>
    </section>

    <!-- Sección de billetera -->
    <section id="billetera">
      <h2 class="text-xl font-semibold mb-2 text-blue-400">Billetera</h2>
      <div id="wallet" class="space-y-2 bg-gray-800 p-3 rounded">
        No tienes activos en la billetera.
      </div>
    </section>
  </main>

  <footer class="bg-gray-800 text-gray-400 text-center py-4 mt-6 rounded">
    <p>© 2025 CriptoSim | Desarrollado por Felipe Mochi </p>
  </footer>

<script>
let usd = 10000;

const criptos = [
  {nombre:"Bitcoin",simbolo:"BTC",precio:65000},
  {nombre:"Ethereum",simbolo:"ETH",precio:3500},
  {nombre:"Ripple",simbolo:"XRP",precio:1.2},
  {nombre:"Litecoin",simbolo:"LTC",precio:180},
  {nombre:"Cardano",simbolo:"ADA",precio:2.5}
];

const acciones = [
  {nombre:"Apple",simbolo:"AAPL",precio:190},
  {nombre:"Google",simbolo:"GOOGL",precio:2800},
  {nombre:"Amazon",simbolo:"AMZN",precio:3500},
  {nombre:"Microsoft",simbolo:"MSFT",precio:330},
  {nombre:"Tesla",simbolo:"TSLA",precio:750}
];


function render(billetera = {}) {
  document.getElementById("usd").textContent = "$" + usd.toFixed(2);

 
  const criptoList = document.getElementById("cripto-list");
  criptoList.innerHTML = "";
  criptos.forEach(a => {
    let bal = billetera[a.simbolo] ? parseFloat(billetera[a.simbolo]) : 0;
    criptoList.innerHTML += `
      <div class="bg-gray-800 p-3 rounded shadow-md">
        <b>${a.nombre} (${a.simbolo})</b><br>
        Precio: $${a.precio.toFixed(2)}<br>
        Balance: ${bal.toFixed(4)}<br>
        <input id="amt-${a.simbolo}" type="number" min="0" placeholder="Cantidad" class="text-black px-1 w-24">
        <button onclick="trade('${a.simbolo}','buy', this)" class="bg-green-600 px-2 py-1 rounded shadow hover:bg-green-500 transition">Comprar</button>
        <button onclick="trade('${a.simbolo}','sell', this)" class="bg-red-600 px-2 py-1 rounded shadow hover:bg-red-500 transition">Vender</button>
      </div>`;
  });

  
  const bolsaList = document.getElementById("bolsa-list");
  bolsaList.innerHTML = "";
  acciones.forEach(a => {
    let bal = billetera[a.simbolo] ? parseFloat(billetera[a.simbolo]) : 0;
    bolsaList.innerHTML += `
      <div class="bg-gray-800 p-3 rounded shadow-md">
        <b>${a.nombre} (${a.simbolo})</b><br>
        Precio: $${a.precio.toFixed(2)}<br>
        Balance: ${bal.toFixed(4)}<br>
        <input id="amt-${a.simbolo}" type="number" min="0" placeholder="Cantidad" class="text-black px-1 w-24">
        <button onclick="trade('${a.simbolo}','buy', this)" class="bg-green-600 px-2 py-1 rounded shadow hover:bg-green-500 transition">Comprar</button>
        <button onclick="trade('${a.simbolo}','sell', this)" class="bg-red-600 px-2 py-1 rounded shadow hover:bg-red-500 transition">Vender</button>
      </div>`;
  });

 
  const wallet = document.getElementById("wallet");
  wallet.innerHTML = "";
  let hayActivos = false;
  Object.keys(billetera).forEach(simbolo => {
    let total = parseFloat(billetera[simbolo]);
    if (total > 0) {
      hayActivos = true;
      wallet.innerHTML += `<div>${simbolo}: ${total.toFixed(4)}</div>`;
    }
  });
  if (!hayActivos) wallet.innerHTML = "No tienes activos en la billetera.";
}


function cargarBilleteraYRenderizar() {
  fetch('api_billetera.php', {credentials: 'same-origin'})
    .then(resp => resp.ok ? resp.json() : {})
    .then(data => {
      let billetera = (data && data.billetera) ? data.billetera : {};
      let saldo = (data && typeof data.saldo !== "undefined") ? data.saldo : 10000;
      document.getElementById("usd").textContent = "$" + saldo.toFixed(2);
      render(billetera);
    })
    .catch(() => {
      document.getElementById("usd").textContent = "$10000.00";
      render({});
    });
}


function trade(simbolo, tipo, btn){
  if (btn.disabled) return; 
  btn.disabled = true; 

  const activo = [...criptos,...acciones].find(a=>a.simbolo===simbolo);
  const input = document.getElementById("amt-"+simbolo);
  const amt = parseFloat(input.value);

  if(amt>0){
    if(tipo==="buy"){
      fetch('comprar.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `moneda=${encodeURIComponent(simbolo)}&cantidad=${encodeURIComponent(amt)}&precio=${encodeURIComponent(activo.precio)}`,
        credentials: 'same-origin'
      })
      .then(resp => resp.text())
      .then(data => {
        if(data === 'OK'){
          alert('¡Compra realizada y guardada en la base de datos!');
          input.value = "";
          cargarBilleteraYRenderizar();
        } else if(data.includes('login.php')) {
          alert('Debes iniciar sesión para comprar.');
          window.location.href = 'login.php';
        } else {
          alert('Respuesta del servidor: ' + data);
        }
        btn.disabled = false;
      })
      .catch(err => {
        btn.disabled = false;
        alert('Error al conectar con el servidor: '+err);
      });
    }
    if(tipo==="sell"){
      fetch('vender.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `moneda=${encodeURIComponent(simbolo)}&cantidad=${encodeURIComponent(amt)}&precio=${encodeURIComponent(activo.precio)}`,
        credentials: 'same-origin'
      })
      .then(resp => resp.text())
      .then(data => {
        if(data === 'OK'){
          alert('¡Venta realizada y guardada en la base de datos!');
          input.value = "";
          cargarBilleteraYRenderizar();
        } else if(data.includes('login.php')) {
          alert('Debes iniciar sesión para vender.');
          window.location.href = 'login.php';
        } else {
          alert('Respuesta del servidor: ' + data);
        }
        btn.disabled = false;
      })
      .catch(err => {
        btn.disabled = false;
        alert('Error al conectar con el servidor: '+err);
      });
    }
  } else {
    btn.disabled = false;
  }
}
// Actualiza precios y recarga billetera cada 5 segundos
setInterval(()=>{
  [...criptos,...acciones].forEach(a=>{
    a.precio += (Math.random()-0.5)*a.precio*0.05;
    if(a.precio<1) a.precio=1;
  });
  cargarBilleteraYRenderizar();
},5000);

// Render inicial
cargarBilleteraYRenderizar();
</script>
</body>
</html>