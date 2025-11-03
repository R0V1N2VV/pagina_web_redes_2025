<?php
session_start(); // Necesario para mostrar usuario si está logueado

$db_host = '127.0.0.1';
$db_name = 'cripto_db';
$db_user = 'root';
$db_pass = '';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (Exception $e) {
    die("Error DB: " . htmlspecialchars($e->getMessage()));
}

// 1. OBTENER BALANCE DEL USUARIO LOGUEADO
$user_balance = 0.00;
// Verifica si la variable de sesión 'usuario' existe y tiene el 'id'
if (isset($_SESSION['usuario']['id'])) { 
    $user_id = $_SESSION['usuario']['id']; 
    
    // Consulta para obtener el 'saldo' del usuario
    $stmt_balance = $pdo->prepare("SELECT saldo FROM usuarios WHERE id = :id");
    $stmt_balance->execute([':id' => $user_id]);
    $result = $stmt_balance->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        $user_balance = $result['saldo'];
    }
}

// Consulta principal (solo cripto)
$stmt = $pdo->query("SELECT simbolo, nombre, precio, change_24h, market_cap, imagen 
                     FROM precios 
                     WHERE tipo = 'cripto' 
                     ORDER BY simbolo ASC");
$precios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Plan A: Obtener historial de los últimos 5 minutos (filtrando por tipo 'cripto')
$histStmt_5min = $pdo->prepare("
    SELECT ph.precio 
    FROM precios_historial ph 
    JOIN precios p ON ph.simbolo = p.simbolo
    WHERE ph.simbolo = :s AND p.tipo = 'cripto' AND ph.fecha >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)
    ORDER BY ph.fecha ASC
");

// Plan B (Fallback): Obtener los últimos 10 puntos si los 5 minutos están vacíos (filtrando por tipo 'cripto')
$histStmt_fallback = $pdo->prepare("
    SELECT ph.precio 
    FROM precios_historial ph 
    JOIN precios p ON ph.simbolo = p.simbolo
    WHERE ph.simbolo = :s AND p.tipo = 'cripto'
    ORDER BY ph.fecha DESC
    LIMIT 10
");


$series = [];
foreach ($precios as $p) {
    $simbolo = $p['simbolo'];
    
    // 1. Intentar Plan A (5 minutos)
    $histStmt_5min->execute([':s' => $simbolo]);
    $data = array_column($histStmt_5min->fetchAll(PDO::FETCH_ASSOC), 'precio');
    
    // 2. Si Plan A no tiene datos, usar Plan B (últimos 10 puntos)
    if (empty($data)) {
        $histStmt_fallback->execute([':s' => $simbolo]);
        // Los resultados del fallback vienen en DESC, hay que invertirlos para el gráfico
        $data = array_reverse(array_column($histStmt_fallback->fetchAll(PDO::FETCH_ASSOC), 'precio'));
    }

    $series[$simbolo] = $data;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Criptomonedas</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<style>
  
body {
  background:#0f1724;
  color:#e6eef8;
  font-family: Arial, sans-serif;
  padding-top: 80px; /* margen para el navbar fijo */
  display:flex;
  justify-content:center;
}
.container {
  width:90%;
  max-width:800px;
  background:rgba(255,255,255,0.03);
  padding:20px;
  border-radius:15px;
  box-shadow:0 0 20px rgba(0,0,0,0.2);
}
.change-up { color:#38c172; }
.change-down { color:#ff6b6b; }
/* Estático y con tamaño fijo para evitar saltos */
.spark { 
  width: 100px !important; 
  height: 35px !important;
}
.marketcap { text-align:right; color:#9fb3d6; }
.price { font-weight:bold; }

/* Estilos de tabla más limpios */
table {
    border-spacing: 0;
}
th, td {
    padding: 8px 10px;
    text-align: left;
}
</style>
</head>

<body>

<?php
// Código del navbar insertado directamente
$is_logged_in = isset($_SESSION['usuario']);
?>

<nav class="bg-gray-800 fixed w-full top-0 z-50 shadow-xl border-b border-green-500/20">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-4 flex justify-between items-center">
        
        <h1 class="text-3xl font-extrabold text-green-400 tracking-wider">Cripto Sim</h1>
        
        <div class="flex items-center space-x-4">
            
            <a href="index.php" class="text-gray-300 hover:text-green-400 transition duration-300 font-medium flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Inicio
            </a>

            <a href="criptomonedas.php" class="text-gray-300 hover:text-purple-400 transition duration-300 font-medium flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.01 12.01 0 002.932 12c0 3.072 1.258 5.857 3.24 7.962l4.8 4.8a1 1 0 001.414 0l4.8-4.8c1.982-2.105 3.24-4.89 3.24-7.962a12.01 12.01 0 00-1.07-5.056z"></path></svg>
                Criptomonedas
            </a>

            <a href="bolsa.php" class="text-gray-300 hover:text-yellow-400 transition duration-300 font-medium flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v2m8-2v2m-8 4v2m8-4v2m4-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Bolsa
            </a>
            
            <?php if ($is_logged_in): ?>
            
            <a href="usuario.php" class="text-gray-300 hover:text-blue-400 transition duration-300 font-medium flex items-center border-l border-gray-700 pl-4">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Mi Cuenta
            </a>

            
            
            <?php else: ?>
            
            <a href="login.php" class="bg-green-500 hover:bg-green-400 px-4 py-2 rounded-xl text-white font-semibold transition duration-300 shadow-md flex items-center border-l border-gray-700 pl-4">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                Iniciar sesión
            </a>
            
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container">
<h2 class="text-center text-xl font-semibold text-purple-400 mb-4">Criptomonedas</h2>

<?php if (isset($_SESSION['usuario']['id'])): ?>
    <div class="bg-gray-800 p-3 mb-4 rounded-lg shadow-lg border border-purple-700/50">
        <p class="text-purple-300 font-medium text-sm">💰 Saldo Disponible:</p>
        <p class="text-3xl font-extrabold text-green-400">$<?php echo number_format($user_balance, 2); ?> USD</p>
    </div>
<?php else: ?>
    <div class="bg-gray-800 p-3 mb-4 rounded-lg text-center text-gray-400">
        Inicia sesión para ver tu saldo.
    </div>
<?php endif; ?>

<div class="mb-4 flex flex-wrap gap-2 items-center p-2 bg-gray-800 rounded-lg shadow-inner">
    <span class="text-sm font-bold text-purple-400 mr-2">Ordenar por:</span>
    <button onclick="ordenarTabla('nombre', 'asc')" class="bg-gray-700 hover:bg-gray-600 text-purple-300 font-semibold px-4 py-1 rounded text-sm transition shadow-md">A-Z</button>
    <button onclick="ordenarTabla('nombre', 'desc')" class="bg-gray-700 hover:bg-gray-600 text-purple-300 font-semibold px-4 py-1 rounded text-sm transition shadow-md">Z-A</button>
    <button onclick="ordenarTabla('precio', 'desc')" class="bg-gray-700 hover:bg-gray-600 text-purple-300 font-semibold px-4 py-1 rounded text-sm transition shadow-md">Más Caros</button>
    <button onclick="ordenarTabla('precio', 'asc')" class="bg-gray-700 hover:bg-gray-600 text-purple-300 font-semibold px-4 py-1 rounded text-sm transition shadow-md">Más Baratos</button>
</div>

<table class="w-full border-collapse">
<thead>
<tr class="text-gray-400 text-sm border-b border-gray-600">
  <th></th> <th>#</th>
  <th>Nombre</th>
  <th>Precio</th>
  <th>24H</th>
  <th>Gráfico</th>
  <th>Market Cap</th>
  <th>Acciones</th>
</tr>
</thead>
<tbody id="tabla-body">
<?php $i=1; foreach($precios as $p): ?>
<tr data-simbolo="<?= $p['simbolo'] ?>" class="border-b border-gray-700 hover:bg-gray-800 transition duration-150 ease-in-out">
  <td class="px-2 py-1"> 
      <img src="imagenes/<?php echo htmlspecialchars($p['imagen']); ?>" alt="<?php echo htmlspecialchars($p['nombre']); ?>" class="w-6 h-6 inline-block" onerror="this.style.display='none'">
  </td>
  <td><?= $i++ ?></td>
  <td><?= htmlspecialchars($p['simbolo']) ?> <small class="text-gray-400"><?= htmlspecialchars($p['nombre']) ?></small></td>
  <td class="price" data-value="<?= $p['precio'] ?>">$<?= number_format($p['precio'], 4) ?></td>
  <td class="<?= $p['change_24h']>=0?'change-up':'change-down' ?>" data-value="<?= $p['change_24h'] ?>">
      <?= ($p['change_24h']>=0?'+':'').$p['change_24h'] ?>%
  </td>
  <td><canvas id="spark-<?= $p['simbolo'] ?>" class="spark"></canvas></td>
  <td class="marketcap" data-value="<?= $p['market_cap'] ?>">$<?= number_format($p['market_cap'],2) ?></td>

  <td class="text-center">
    <div class="flex flex-col space-y-1">
        <button onclick="comprarCripto('<?= $p['simbolo'] ?>', <?= $p['precio'] ?>)"
          class="bg-green-600 hover:bg-green-500 text-white px-3 py-1 rounded text-xs transition">
          Comprar
        </button>
        <button onclick="venderCripto('<?= $p['simbolo'] ?>', <?= $p['precio'] ?>)"
          class="bg-red-600 hover:bg-red-500 text-white px-3 py-1 rounded text-xs transition">
          Vender
        </button>
    </div>
  </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>


<script>
const series = <?= json_encode($series) ?>;
const charts = {};

function drawSpark(id, data){
  const ctx = document.getElementById('spark-'+id).getContext('2d');
  if (charts[id]) charts[id].destroy();
  charts[id] = new Chart(ctx,{
    type:'line',
    data:{
      labels: data.map((_, i) => i),
      datasets:[{
        data: data,
        borderColor:'#4a90e2', // Línea de color azul más brillante
        backgroundColor: 'rgba(74, 144, 226, 0.2)', // Relleno semi-transparente
        fill:'origin', // Asegura que el relleno comience en el eje X
        pointRadius:0,
        tension:0.3 
      }]
    },
    options:{
      // Asegurar que el gráfico no cambie sus dimensiones internas
      responsive: false, 
      maintainAspectRatio: false, 
      scales:{x:{display:false},y:{display:false}},
      plugins:{legend:{display:false}},
      elements:{line:{borderWidth:2}} // LÍNEA MÁS GRUESA (Resaltado)
    }
  });
}

Object.entries(series).forEach(([sym,data])=>drawSpark(sym,data));


// FUNCIÓN DE ORDENAMIENTO (se mantiene igual)
function ordenarTabla(columna, orden) {
    const tbody = document.getElementById('tabla-body');
    const filas = Array.from(tbody.querySelectorAll('tr'));

    const columnaIndice = {
        'nombre': 2, 
        'precio': 3  
    };
    const indice = columnaIndice[columna];

    filas.sort((a, b) => {
        let valA, valB;

        if (columna === 'nombre') {
            valA = a.children[indice].textContent.toLowerCase();
            valB = b.children[indice].textContent.toLowerCase();
        } 
        else if (columna === 'precio') {
            valA = parseFloat(a.children[indice].getAttribute('data-value') || 0);
            valB = parseFloat(b.children[indice].getAttribute('data-value') || 0);
        }

        if (orden === 'asc') {
            return valA > valB ? 1 : -1;
        } else {
            return valA < valB ? 1 : -1;
        }
    });

    filas.forEach(fila => tbody.appendChild(fila));
    
    tbody.querySelectorAll('tr').forEach((row, index) => {
        row.children[1].textContent = index + 1;
    });
}


// Actualización cada 7 segundos
async function actualizar(){
  try{
    const res = await fetch('actualizar_precios.php');
    const json = await res.json();
    if(!json.precios) return;

    json.precios.forEach(p=>{
      const fila=document.querySelector(`tr[data-simbolo="${p.simbolo}"]`);
      if(!fila) return; 

      const precioCelda=fila.querySelector('.price');
      const cambioCelda=fila.querySelector('td:nth-child(5)'); 
      const nuevoPrecio=p.nuevo_precio.toFixed(4);
      
      precioCelda.textContent='$'+nuevoPrecio;
      precioCelda.setAttribute('data-value', p.nuevo_precio); 

      cambioCelda.textContent=(p.change_24h>=0?'+':'')+p.change_24h+'%';
      cambioCelda.className=p.change_24h>=0?'change-up':'change-down';
      cambioCelda.setAttribute('data-value', p.change_24h);

      if(series[p.simbolo]){
          series[p.simbolo].push(p.nuevo_precio);
          
          if(series[p.simbolo].length > 20) {
              series[p.simbolo].shift();
          }
          drawSpark(p.simbolo, series[p.simbolo]);
      }
    });
  }catch(e){console.error(e);}
}
setInterval(actualizar,7000);
</script>
</body>
<script>
// Funciones comprarCripto y venderCripto (se mantienen iguales)
async function comprarCripto(moneda, precio) {
  const cantidad = prompt(`¿Cuántas unidades de ${moneda} deseas comprar?`);
  if (!cantidad || isNaN(cantidad) || cantidad <= 0) return;

  const formData = new FormData();
  formData.append("moneda", moneda);
  formData.append("cantidad", cantidad);
  formData.append("precio", precio);

  try {
    const res = await fetch("comprar.php", {
      method: "POST",
      body: formData
    });
    const text = await res.text();
    if (text.trim() === "OK") {
      alert(`✅ Compra realizada: ${cantidad} ${moneda}`);
    } else if (text.trim() === "login.php") {
      alert("⚠️ Debes iniciar sesión para comprar.");
      window.location.href = "login.php";
    } else {
      alert("⚠️ " + text);
    }
  } catch (e) {
    console.error(e);
    alert("❌ Error al procesar la compra");
  }
}

async function venderCripto(moneda, precio) {
  const cantidad = prompt(`¿Cuántas unidades de ${moneda} deseas vender?`);
  if (!cantidad || isNaN(cantidad) || cantidad <= 0) return;

  const formData = new FormData();
  formData.append("moneda", moneda);
  formData.append("cantidad", cantidad);
  formData.append("precio", precio);

  try {
    const res = await fetch("vender.php", {
      method: "POST",
      body: formData
    });
    const text = await res.text();
    if (text.trim() === "OK") {
      alert(`✅ Venta realizada: ${cantidad} ${moneda}`);
    } else if (text.trim() === "login.php") {
      alert("⚠️ Debes iniciar sesión para vender.");
      window.location.href = "login.php";
    } else {
      alert("⚠️ " + text);
    }
  } catch (e) {
    console.error(e);
    alert("❌ Error al procesar la venta");
  }
}
</script>
</html>