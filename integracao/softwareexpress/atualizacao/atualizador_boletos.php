<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <title>Atualizador de Boleto</title>
    <style>
        .header{
            background: #969391;
            position: fixed;
            top: 0;
            height: 150px;
            display: flex;
            align-items: center;
            justify-content: space-evenly;
            z-index: 1;
            box-shadow: 5px 1px 20px rgba(0, 0, 0, 0.5)  ; 
            -webkit-box-shadow: 5px 1px 20px rgba(0, 0, 0, 0.5)  ; 
            -moz-box-shadow: 5px 1px 20px rgba(0, 0, 0, 0.5)  ; 
        }
        .subheader{
            background: #f89848;;
            position: fixed;
            top: 150px;
            height: 60px;
            /* display: flex;
            align-items: center; */
            padding-left: 50px;
            z-index: 0;
            flex-wrap: nowrap !important;
            align-items: center;
            width: 100%;
            
        }
        .subheader>.row{
            /* display: flex; */
            /* align-items: center;
            justify-content: space-evenly; */
        }
        .subheader>.row>.col-6{
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .comands{
            /* display: flex;
            align-items: center;
            justify-content: space-evenly; */
        }
        .comands>div{
            width: 50%;
        }
        .main{
            margin-top: 240px;
        }
    </style>
</head>
<body>
    <div class="container-fluid header">
        <h3>Atualizador de boletos</h3>
        <img src="bem-servicos.png" alt="Logo Bem Serviços">
    </div>
    <div class="container-fluid subheader row">
        
            <div class="col-3"><button class="btn btn-success">Buscar e Atualizar</button></div>
            <div class="col-3"><button type="button" onclick="limpar()" class="btn btn-outline-danger">Limpar</button></div>
                
       
    </div>
    <div class="container main">        
        <div id="resposta"></div>                               
        <div class="row" id="result"></div>   
           

    </div>
    <script>
        $(document).ready(function(){
            $('#data').val('Selecione um período') 
            $('.btn-success').click(function(){
                var data_inicial = $('#data-inicial').val();
                var data_final = $('#data-final').val();
                // if(data_inicial == '' || data_final == ''){
                //     $('#resposta').html(`<div class="alert alert-danger" role="alert">
                //                             Selecione um período para buscar os boletos!
                //                         </div>`);
                //     return false;
                // }else{
                $.ajax({
                    url: 'asaas_api_schedule.php',
                    type: 'POST',
                    data: {data_inicial: data_inicial, data_final: data_final},
                    beforeSend: function(){
                        $('#result').html(`<button class="btn btn-primary mt-3" type="button" disabled>
                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                Carregando, aguarde...
                                            </button>`);
                    },
                    success: function(data){
                        $('#result').html(data);
                        $('#resposta').html('');
                        $('#data').val('Selecione um período')  
                    }
                });
            // }
            });
        });
        
        function limpar(){
            // $('#data-inicial').val('');
            // $('#data-final').val('');
            $('#resposta').html('');
            $('#result').html('');
        }

        $(function() {
          $('input[name="daterange"]').daterangepicker({
            opens: 'left',
            format: 'DD-MM-YYYY',
            autoUpdateInput: false,
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
            $('#resposta').html(`A busca e atualização será realizada no perído de ${start.format('DD/MM/YYYY')} até ${end.format('DD/MM/YYYY')}`);
            $('#data-inicial').val(start.format('YYYY-MM-DD'));
            $('#data-final').val(end.format('YYYY-MM-DD'));
                            
          });
         
        });
    </script>
</body>
</html>