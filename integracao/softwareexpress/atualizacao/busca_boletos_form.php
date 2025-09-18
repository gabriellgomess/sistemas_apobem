<!doctype html>
<html lang="pt-BR">
  <head>
    <title>Busca de Boletos</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.2/jquery.min.js" integrity="sha512-tWHlutFnuG0C6nQRlpvrEhE4QpkG1nn2MOUMWmUeRePl4e3Aki0VB6W1v3oLjFtd0hVOtRQ9PHpSfN6u6/QXkQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  </head>
  <body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
            <h1>Filtre a busca de boletos gerados por data</h1>
            <input style="width: 250px" class="form-control mt-5" type="text" name="daterange" value="" />
            <p class="mt-5" id="resposta"></p>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div id="resultado"></div>
            </div>
        </div>
    </div>
      
    <!-- Optional JavaScript -->
    <script>
        $(document).ready(function(){
            $('input[name="daterange"]').daterangepicker({
            opens: 'left',
            format: 'DD-MM-YYYY',
            locale: {
              format: 'DD-MM-YYYY',
                applyLabel: 'Aplicar',
                cancelLabel: 'Cancelar',
                fromLabel: 'De',
                toLabel: 'Até',
                customRangeLabel: 'Personalizado',
                daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex','Sab'],
                monthNames:['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro']

            }
          }, function(start, end, label) {
            $('#resposta').html(`Boletos criados de ${start.format('DD-MM-YYYY')} até ${end.format('DD-MM-YYYY')}`);

            var data_inicio = start.format('YYYY-MM-DD');
            var data_fim = end.format('YYYY-MM-DD');
                $.ajax({
                    url: '/integracao/softwareexpress/atualizacao/busca_boletos.php',
                    type: 'POST',
                    data: {
                        data_inicio: data_inicio,
                        data_fim: data_fim
                    },
                    beforeSend: function(){
                        $("#resultado").html("Carregando...");
                    },
                    success: function(data){
                        $("#resultado").html(data);
                    }
                });
          });


            // $("#data").change(function(){
            //     var data = $(this).val();
            //     $.ajax({
            //         url: '/integracao/softwareexpress/atualizacao/busca_boletos.php',
            //         type: 'POST',
            //         data: {data: data},
            //         beforeSend: function(){
            //             $("#resultado").html("Carregando...");
            //         },
            //         success: function(data){
            //             $("#resultado").html(data);
            //         }
            //     });
            // });
        });
    </script>
   
    
  </body>
</html>