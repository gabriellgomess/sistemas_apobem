<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"
        integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <title>Teste Index</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        #daterange {
            width: 300px;

        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row d-flex justify-content-center ">

            <div class="col-6 d-flex justify-content-center flex-column">
                <h1>Boletos em cobrança</h1>
                <div class="d-flex">
                    <input class="form-control m-2" type="text" name="daterange" id="daterange" value="" />
                    <input type="button" value="Buscar" id="buscar" class="btn btn-primary m-2">
                </div>
            </div>
        </div>
        <p>A API Asaas retorna no máximo 100 registros</p>
        <div id="root"></div>
    </div>


    <script>
        $(function () {
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();
            var today = mm + '/' + dd + '/' + yyyy;
            $("#dateranger").val(today + " - " + today);
            $('input[name="daterange"]').daterangepicker({
                opens: 'left',
                autoApply: true,
                locale: {
                    format: 'DD/MM/YYYY'
                }
            }, function (start, end, label) {
                var data_inicio = start.format('YYYY-MM-DD');
                var data_fim = end.format('YYYY-MM-DD');
                $("#buscar").on("click", function () {
                    var url = "atualiza_boleto.php";
                    var metodo = "POST";
                    $.ajax({
                        url: url,
                        type: metodo,
                        data: {
                            data_inicio: data_inicio,
                            data_fim: data_fim
                        },
                        beforeSend: function () {
                            $("#root").html("<h3>Carregando...<h3>");
                        },
                        success: function (response) {
                            $("#root").html(response);
                        }
                    });
                });
            });

        });
    </script>

</body>

</html>