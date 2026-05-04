<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Panel Verificador</title>

<style>
body{
    margin:0;
    background:#0a0a0a;
    font-family:Arial, Helvetica, sans-serif;
    color:white;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.panel{
    width:520px;
    background:#111;
    border:2px solid #00ff88;
    border-radius:18px;
    padding:25px;
    box-shadow:0 0 25px #00ff88;
}

h2{
    text-align:center;
    color:#00ff88;
    margin-top:0;
}

textarea{
    width:100%;
    height:150px;
    background:#1b1b1b;
    border:none;
    border-radius:12px;
    padding:12px;
    color:white;
    resize:none;
    font-size:13px;
}

button{
    width:100%;
    margin-top:15px;
    padding:14px;
    border:none;
    border-radius:12px;
    background:#00ff88;
    color:black;
    font-weight:bold;
    cursor:pointer;
    font-size:16px;
}

.box{
    margin-top:20px;
    background:#1a1a1a;
    padding:15px;
    border-radius:12px;
    line-height:1.8;
}

.ok{color:#00ff88;}
.bad{color:#ff3b3b;}
.info{color:#00bfff;}
</style>
</head>
<body>

<div class="panel">

<h2>🔐 Panel Verificador</h2>

<textarea id="token">
eyJhbGciOiJIUzUxMiIsInR5cCI6IkpXVCJ9.eyJleHAiOiIxNzc3NTA3OTU1Iiwic2lwIjoiMTgxLjEyNC4xOTQuNjAiLCJwYXRoIjoiL2xpdmUvYzZlZHMvVGVsZWZlSEQvU0FfTGl2ZV9kYXNoX2VuY19DLyIsInNlc3Npb25fY2RuX2lkIjoiNzJkMjJhMDdjNTU2YmI5ZSIsInNlc3Npb25faWQiOiIiLCJjbGllbnRfaWQiOiIiLCJkZXZpY2VfaWQiOiIiLCJtYXhfc2Vzc2lvbnMiOjAsInNlc3Npb25fZHVyYXRpb24iOjAsInVybCI6Imh0dHBzOi8vMjAxLjIzNS42Ni4xMTQiLCJhdWQiOiI2MiIsInNvdXJjZXMiOls4NSw4Niw4OF19.LVZliCpGN8GnTm1uRMc7Y3j2E9QN94QuJ6Yq4OhmIZ3VX4oxoW9AtSAxD3zRh6aiAVDN1v8RDSszlzzlQU808g
</textarea>

<button onclick="verificar()">VERIFICAR TOKEN</button>

<div class="box" id="resultado">
Esperando análisis...
</div>

</div>

<script>
let timer;

function base64UrlDecode(str){
    str = str.replace(/-/g, '+').replace(/_/g, '/');
    while(str.length % 4) str += '=';
    return decodeURIComponent(escape(atob(str)));
}

function verificar(){

    clearInterval(timer);

    const token = document.getElementById("token").value.trim();
    const box = document.getElementById("resultado");

    try{

        const partes = token.split(".");
        const payload = JSON.parse(base64UrlDecode(partes[1]));

        const exp = parseInt(payload.exp);

        function actualizar(){

            let now = Math.floor(Date.now()/1000);
            let restante = exp - now;

            let fechaUTC = new Date(exp*1000).toUTCString();
            let fechaLocal = new Date(exp*1000).toLocaleString();

            if(restante <= 0){
                box.innerHTML = `
                <span class="bad">❌ TOKEN EXPIRADO</span><br>
                Fecha Local: ${fechaLocal}<br>
                UTC: ${fechaUTC}
                `;
                clearInterval(timer);
                return;
            }

            let dias = Math.floor(restante/86400);
            let horas = Math.floor((restante%86400)/3600);
            let min = Math.floor((restante%3600)/60);
            let seg = restante%60;

            box.innerHTML = `
            <span class="ok">✅ TOKEN ACTIVO</span><br><br>

            <span class="info">📅 Expira:</span><br>
            ${fechaLocal}<br><br>

            <span class="info">🌍 UTC:</span><br>
            ${fechaUTC}<br><br>

            ⏳ Tiempo restante:<br>
            ${dias} días ${horas} hs ${min} min ${seg} seg
            `;
        }

        actualizar();
        timer = setInterval(actualizar,1000);

    }catch(e){
        box.innerHTML = `<span class="bad">❌ Token inválido</span>`;
    }
}
</script>

</body>
</html>
