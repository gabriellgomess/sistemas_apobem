<?php

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <title>Atualizador ASAAS</title>
</head>
<body>
    <button onclick="atualizar()">Atualizar Boleto</button>
    <div class="resposta">
        <p>Resposta</p>
    </div>
    



    <script>
        function atualizar(){
            var body = {
                "customer": 'cus_000032113319',
                "billingType": 'BOLETO',
                "dueDate": '2022-11-18',
                "value": 234.01,
                "description": 'Teste de atualização',
                "externalReference": "",
                "installmentCount": 0,
                "installmentValue": 0,                
                "discountValue": 0,
                "discountDays": 0,
                "discountType": 'FIXED',
                "fineValue": 0,
                "interestValue": 0,
                "postalService": false
            };

            $.ajax({
                url: 'https://www.grupofortune.com.br/integracao/asaas/atualizador_boleto.php',
                type: 'POST',
                data: body,
                success: function(response){
                    console.log(response);
                    $(".resposta").html(response);
                },
                error: function(response){
                    console.log(response);
                    $(".resposta").html(response);
                }
            });

        
        //    var request = new XMLHttpRequest();

        //     request.open('POST', 'https://www.asaas.com/api/v3/payments/cus_000032113319');

        //     request.setRequestHeader('Content-Type', 'application/json');
        //     request.setRequestHeader('access_token', '49188e8a6a676a2c5d5c553225d856040497580ae7552b34e260489cdab4da2c');
        //     request.setRequestHeader('Access-Control-Allow-Origin', '*');
        //     request.setRequestHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, PATCH, DELETE');
        //     request.setRequestHeader('Access-Control-Allow-Headers', 'Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers');


        //     request.onreadystatechange = function () {
        //     if (this.readyState === 4) {
        //         console.log('Status:', this.status);
        //         console.log('Headers:', this.getAllResponseHeaders());
        //         console.log('Body:', this.responseText);
        //         document.getElementsByClassName("resposta")[0].innerHTML = this.responseText;
        //     }
        //     };

        //     var body = {
        //     'billingType': 'BOLETO',
        //     'dueDate': '2022-11-18',
        //     'value': 234.01,
        //     'description': 'Pedido 123456',
        //     'externalReference': '',
        //     'discount': {
        //         'value': 0,
        //         'dueDateLimitDays': 0
        //     },
        //     'fine': {
        //         'value': 0
        //     },
        //     'interest': {
        //         'value': 0
        //     },
        //     'postalService': false
        //     };

        //     request.send(JSON.stringify(body)); 
        }
        
    </script>
</body>
</html>