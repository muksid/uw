@include('layouts.head')
<link href="{{asset('/admin-lte/plugins/select2/select2.min.css')}}" rel="stylesheet">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <a href="{{ url( '/madmin/app-list') }}" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini">
                    <img src="{{ asset('images/logo_png.png') }}" height="70" class="d-inline-block align-top" alt="">
                </span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b>Talabnoma</b>
                    <small>
                        
                    </small>        
                </span>
            </a>
        </h1>

        @if(session('success'))
            <div class="box box-default">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-success">
                                <h4 class="modal-title"> {{ session('success') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </section>
    <div id="loading" class="loading-gif" style="display: none"></div>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box box-primary">
                    <div class="box-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-8">
                                    <h4>@lang('blade.template_name'): {!! $template->title !!}</h4> 
                                </div>
                                <div class="col-sm-2 text-right">
                                    <input type='button' class="btn btn-success" id='btn' style="margin: 10px" value="@lang('blade.print')" onclick='printDiv();'>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 col-xs-offset-2 with-border">
                                    <div id='DivIdToPrint' class="box box-solid box-default" style="width: 210mm; height: auto; padding: 4em">
                                        {!! $template->body !!}
                                        <div class="col-sm">
                                            <span class="">
                                                {!! QrCode::size(70)->generate('https://online.turonbank.uz:3347/acc/'); !!} 
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>

        <script src="{{ asset ("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>
        <script src="{{ asset ("/js/jquery.validate.js") }}"></script>



        <!-- ckeditor -->


        <script>

            $(function () {
                //Initialize Select2 Elements
                $(".select2").select2()

                //Date picker
                $('#datepicker').datepicker({
                    autoclose: true
                })

                $('.input-datepicker').datepicker({
                    todayBtn: 'linked',
                    todayHighlight: true,
                    format: 'yyyy-mm-dd',
                    autoclose: true
                })

                $('.input-daterange').datepicker({
                    todayBtn: 'linked',
                    forceParse: false,
                    todayHighlight: true,
                    format: 'yyyy-mm-dd',
                    autoclose: true
                })
            })

            $(document).ready(function () {
                    
                // Replace words
                let loan_id         = "<?php echo $model->loan_id??''; ?>"
                let client_code     = "<?php echo $model->client_code??''; ?>"
                let contract_code   = "<?php echo $model->contract_code??''; ?>"
                let contract_date   = "<?php echo date('d.m.Y', strtotime($model->contract_date??'00-00-0000'))??''; ?>"
                let summ_loan       = <?php echo ($model->summ_loan??'сумма топилмади!'); ?>; /* Imtiyozli kredit */
                let client_name     = '<?php echo $model->client_name??''; ?>';
                let address         = "<?php echo $model->address??''; ?>";
                let filial_code     = "<?php echo $model->filial->title??''; ?>";
                let saldo_in_5      = <?php echo $model->saldo_in_5??''; ?>;
                let saldo_in_all    = <?php echo $model->saldo_in_all??'';?>;
                let saldo_added     = saldo_in_5+saldo_in_all;

                $("#DivIdToPrint").each(function() {
                    $(this).html(
                        $(this).html().
                            replaceAll("[[loan_id]]",       loan_id).
                            replaceAll("[[client_code]]",   client_code).
                            replaceAll("[[contract_code]]", contract_code).
                            replaceAll("[[contract_date]]", contract_date).
                            replaceAll("[[summ_loan]]",     formatCurrency(summ_loan/100)).
                            replaceAll("[[client_name]]",   client_name).
                            replaceAll("[[address]]",       address).
                            replaceAll("[[filial_code]]",   filial_code).
                            replaceAll("[[saldo_in_5]]",    formatCurrency(saldo_in_5/100)).
                            replaceAll("[[saldo_in_all]]",  formatCurrency(saldo_in_all/100)). 
                            replaceAll("[[saldo_added]]",   formatCurrency(saldo_added/100)) 
                        )
                })

            })

            function printDiv() {
                var divToPrint=document.getElementById('DivIdToPrint');

                var newWin=window.open('','Print-Window');

                newWin.document.open();

                newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

                newWin.document.close();

                setTimeout(function(){newWin.close();},10);
            }

            function formatCurrency(total) {
                var neg = false;
                if(total < 0) {
                    neg = true;
                    total = Math.abs(total);
                }
                return (neg ? "-" : '') + parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString();
            }


        </script>
    </section>
    @include('layouts.footer')

    <!-- /.content -->
