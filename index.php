<?php session_start(); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cripto Sim</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="favicon.png">
    <style>
        .custom-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.06);
        }
        .green-button {
            background-color: #10B981; /* green-500 */
            color: white;
        }
        .green-button:hover {
            background-color: #059669; /* green-600 */
        }
        .portfolio-chart {
            height: 8px; /* Altura de la barra de gráfico */
        }
        /* Para un scroll suave al usar los anclas */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body class="bg-gray-900 text-gray-100 flex flex-col min-h-screen">
    
   <?php include("navbar.php") ?>

    
    <main class="flex-grow pt-24 px-6 max-w-6xl mx-auto space-y-16">

        <section class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-8 lg:space-y-0 lg:space-x-12 pt-8">
            
            <div class="lg:w-2/3">
                <h2 class="text-4xl sm:text-5xl font-extrabold mb-4 text-gray-50">
                    <span class="text-green-400">Simulador de Inversiones</span>
                    <br>
                    <span class="text-red-400">aprende a operar sin riesgo</span>
                </h2>
                <p class="text-lg text-gray-400 mb-6">
                    Empieza con $10.000 de saldo virtual. Practica la compra/venta de Criptomonedas y Acciones de Bolsa en tiempo real. ¡El riesgo es CERO!
                </p>
                <div class="flex space-x-4">
                    <a href="#como-funciona" class="px-4 py-3 rounded-lg font-semibold transition green-button">¿Como funciona? </a>
                    <a href="#productos-inversion" class="px-6 py-3 rounded-lg font-semibold transition bg-gray-700 text-white hover:bg-gray-600">Productos de inversión</a>
                    <a href="#comparador" class="px-6 py-3 rounded-lg font-semibold transition bg-gray-700 text-white hover:bg-gray-600 hidden sm:inline-block">Comparador</a>
                </div>
            </div>

        </section>

        <hr class="border-gray-700">

        <section id="como-funciona" class="text-center">
            <h3 class="text-3xl font-bold mb-10 text-gray-50">¿Cómo funciona?</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="p-4 bg-gray-800 rounded-lg shadow-md border-t-4 border-green-400">
                    <div class="text-3xl mb-3">🎯</div>
                    <h4 class="font-semibold text-green-400 mb-2">1. Define objetivos</h4>
                    <p class="text-xs text-gray-400">Plazo, monto y tolerancia al riesgo. Es la base del plan.</p>
                </div>
                <div class="p-4 bg-gray-800 rounded-lg shadow-md border-t-4 border-yellow-400">
                    <div class="text-3xl mb-3">👤</div>
                    <h4 class="font-semibold text-yellow-400 mb-2">2. Perfil de riesgo</h4>
                    <p class="text-xs text-gray-400">Conoce tu perfil: conservador, moderado o agresivo.</p>
                </div>
                <div class="p-4 bg-gray-800 rounded-lg shadow-md border-t-4 border-purple-400">
                    <div class="text-3xl mb-3">💰</div>
                    <h4 class="font-semibold text-purple-400 mb-2">3. Arma tu cartera</h4>
                    <p class="text-xs text-gray-400">Diversifica entre Criptos, Bolsa o timba.</p>
                </div>
                <div class="p-4 bg-gray-800 rounded-lg shadow-md border-t-4 border-red-400">
                    <div class="text-3xl mb-3">📈</div>
                    <h4 class="font-semibold text-red-400 mb-2">4. Monitoreo</h4>
                    <p class="text-xs text-gray-400">Rebalancea periódicamente según mercado y objetivos.</p>
                </div>
            </div>
        </section>
        
        <hr class="border-gray-700">

        <section id="productos-inversion" class="pt-8 text-center">
            <h3 class="text-3xl font-bold mb-10 text-center text-gray-50">Productos de inversión</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-4xl mx-auto"> 
                
                <div class="p-6 bg-gray-800 rounded-lg shadow-lg border border-gray-700 hover:border-green-400 transition-all text-left">
                    <div class="text-3xl mb-3 text-green-400">₿</div>
                    <h4 class="font-semibold text-xl text-green-400 mb-3">Criptomonedas</h4>
                    <p class="text-sm text-gray-400 mb-4">Activos digitales con alta volatilidad. Potencial de crecimiento exponencial a largo plazo.</p>
                    <div class="flex flex-wrap gap-2 text-xs font-medium">
                        <span class="bg-gray-700 text-green-400 px-3 py-1 rounded-full">Alto riesgo</span>
                        <span class="bg-gray-700 text-green-400 px-3 py-1 rounded-full">Volátil</span>
                        <span class="bg-gray-700 text-green-400 px-3 py-1 rounded-full">Largo plazo</span>
                    </div>
                </div>

                <div class="p-6 bg-gray-800 rounded-lg shadow-lg border border-gray-700 hover:border-yellow-400 transition-all text-left">
                    <div class="text-3xl mb-3 text-yellow-400">📜</div>
                    <h4 class="font-semibold text-xl text-yellow-400 mb-3">Bolsa</h4>
                    <p class="text-sm text-gray-400 mb-4">Instrumentos con bajo riesgo y rendimientos fijos.</p>
                    <div class="flex flex-wrap gap-2 text-xs font-medium">
                        <span class="bg-gray-700 text-yellow-400 px-3 py-1 rounded-full">Cupones Periódicos</span>
                        <span class="bg-gray-700 text-yellow-400 px-3 py-1 rounded-full">Preservación de Capital</span>
                        <span class="bg-gray-700 text-yellow-400 px-3 py-1 rounded-full">Calificación Crediticia</span>
                    </div>
                </div>
                
                <div class="p-6 bg-gray-800 rounded-lg shadow-lg border border-gray-700 relative overflow-hidden flex flex-col justify-between text-left">
                    <div>
                        <div class="text-3xl mb-3 text-gray-500 opacity-50">🎰</div> 
                        <h4 class="font-semibold text-xl text-gray-500 mb-3">Timba</h4>
                        <p class="text-sm text-gray-600 mb-4">Operaciones de alto riesgo con potencial de ganancias rápidas.</p>
                        <div class="flex flex-wrap gap-2 text-xs font-medium opacity-50">
                            <span class="bg-gray-700 text-gray-500 px-3 py-1 rounded-full">Alto riesgo</span>
                            <span class="bg-gray-700 text-gray-500 px-3 py-1 rounded-full">Demasiado Volátil</span>
                            <span class="bg-gray-700 text-gray-500 px-3 py-1 rounded-full">corto plazo</span>
                        </div>
                    </div>
                    <div class="mt-auto pt-4"> 
                        <span class="block text-xl font-bold text-red-500 bg-red-900 bg-opacity-30 px-3 py-1 rounded-full text-center">Próximamente</span>
                    </div>
                </div>

            </div>
        </section>

        <hr class="border-gray-700">

        <section id="comparador" class="pb-16 pt-8">
            <h3 class="text-3xl font-bold mb-10 text-center text-gray-50">Comparador rápido</h3>
            <p class="text-center text-gray-400 mb-8">Una guía orientativa de riesgo, plazo sugerido, objetivo típico, liquidez y costos.</p>

            <div class="overflow-x-auto bg-gray-800 rounded-lg shadow-xl">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Instrumento</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Riesgo</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Plazo sugerido</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Objetivo típico</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Liquidez</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Costos</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        <tr class="hover:bg-gray-700 transition">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-white">Criptomonedas</td>
                            <td class="px-6 py-4 whitespace-nowrap"><span class="bg-red-900 text-red-400 px-3 py-1 text-xs rounded-full font-semibold">Alto</span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">3-5 años+</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">Crecimiento capital</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">Alta</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">Medios-Altos</td>
                        </tr>
                        <tr class="hover:bg-gray-700 transition">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-white">Bolsa</td>
                            <td class="px-6 py-4 whitespace-nowrap"><span class="bg-yellow-900 text-yellow-400 px-3 py-1 text-xs rounded-full font-semibold">Medio-Alto</span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">1-3 años+</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">Crecimiento/Dividendos</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">Media</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">Bajos-Medios</td>
                        </tr>
                        <tr class="hover:bg-gray-700 transition">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-white">Casino</td>
                            <td class="px-6 py-4 whitespace-nowrap"><span class="bg-red-900 text-red-400 px-3 py-1 text-xs rounded-full font-semibold">Muy Alto</span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">Día/Semana</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">Ganancia rápida</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">Inmediata</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">Variables (Altos)</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

    <footer class="bg-gray-800 text-gray-400 mt-auto py-6">
        <div class="max-w-7xl mx-auto px-4 text-center text-sm">
            &copy; 2025 Cripto Sim. Simulador de inversiones.
        </div>
    </footer>

    <script>
    function cargarSaldo() {
        // Simulación de valor "YTD" ya que no hay un campo real en tu API, usando el saldo USD.
        // Se carga el saldo real y se muestra el % simulado.
        fetch('api_billetera.php', { credentials: 'same-origin' })
            .then(r => r.json())
            .then(d => {
                if (d && typeof d.saldo !== 'undefined') {
                    // Simulación de un porcentaje de retorno (YTD) para el dashboard
                    const saldo = Number(d.saldo);
                    let ytd = 0.00;
                    if (saldo > 0) {
                        ytd = (saldo / 100) * 12.4; // Ejemplo: 12.4% de YTD
                    }
                    // Aquí mostramos un valor de ejemplo estático para el YTD como en la foto
                    document.getElementById('usd-balance').textContent = '12.4%'; 
                }
            })
            .catch(()=>{
                // Si falla la carga, muestra el valor simulado
                document.getElementById('usd-balance').textContent = '12.4%';
            });
    }
    cargarSaldo();
    </script>
</body>
</html>