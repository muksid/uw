@include('layouts.head')
<link href="{{asset('/admin-lte/plugins/select2/select2.min.css')}}" rel="stylesheet">



    <!-- Content Header (Page header) -->
    <section class="content-header">
        
        <h1>
        <a href="{{ ($client_type == 'phy') ? '/madmin/app-list-phy':'/madmin/app-list-jur' }}" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini">
                <img src="{{ asset('images/logo_png.png') }}" height="70" class="d-inline-block align-top" alt="">
            </span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Talabnoma</b>
                <small>
                    @if ($client_type =='phy')
                        Physical
                    @else
                        Juridical
                    @endif
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
                                    <h4>@lang('blade.template_name'): {!! $app->title !!}</h4> 
                                </div>
                                <div class="col-sm-2 text-right">
                                    <input type='button' class="btn btn-success" id='btn' style="margin: 10px" value="@lang('blade.print')" onclick='printDiv();'>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 col-xs-offset-2 with-border">
                                    <div id='DivIdToPrint' class="box box-solid box-default" style="width: 210mm; height: auto; padding: 4em">
                                        {!! $app->body !!}
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
        <script src="{{ asset ('/admin-lte/plugins/ckeditor/ckeditor.js') }}"></script>
        <script src="{{ asset ('/admin-lte/plugins/ckeditor/samples/js/sample.js') }}"></script>

        <input type="text" id="loanName" value="{{$model->loanType->title}}" hidden></input>
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

                let client_type = "<?php echo $client_type; ?>";

                if (client_type == 'phy') {
                    
                    // Replace words
                    let client_address  = "<?php echo $model->live_address??''; ?>"
                    let filial_name     = "<?php echo $model->filial->title??''; ?>"
                    let client_name     = "<?php echo substr($model->name,0,1).'.'.substr($model->patronymic,0,1).'.'.$model->family_name; ?>"
                    let client_date     = "<?php echo $model->claim_date??''; ?>"
                    let loan_exemtion   = "<?php echo $model->loanType->credit_exemtion??''; ?>"; /* Imtiyozli kredit */
                    let loan_duration   = "<?php echo $model->loanType->credit_duration??''; ?>";
                    let loan_name       = $("#loanName").val()
                    let loan_percentage = "<?php echo $model->loanType->procent??''; ?>";
                    let summa           = "<?php echo $model->convertNumberToWord($model->summa??'');?>"

                    let guards = <?php echo $guard; ?>;

                    $.each(guards,  function(key,guard){
                        if(guard.guar_type == 'G'){
                            console.log(guard)
                            $("#DivIdToPrint").each(function(index) {
                                $(this).html(
                                    $(this).html().
                                        replaceAll("[[GUARD_OWNER"+key+"]]", guard.guar_owner).
                                        replaceAll("[[GUARD_ADDRESS"+key+"]]", guard.address)
                                    )
                            })
                        }else{
                            $("#DivIdToPrint").each(function(index) {
                                $(this).html(
                                    $(this).html().
                                        replaceAll("[[GUARD_OWNER"+key+"]]", '____________________').
                                        replaceAll("[[GUARD_ADDRESS"+key+"]]", '__________________________________________')
                                    )
                            })
                        }
                    })
                    
                    $("#DivIdToPrint").each(function() {
                        $(this).html(
                            $(this).html().
                                replaceAll("[[CLIENT_ADDRESS]]",    client_address).
                                replaceAll("[[FILIAL_NAME]]",       filial_name).
                                replaceAll("[[CLIENT_FULLNAME]]",   client_name).
                                replaceAll("[[CLAIM_DATE]]",        client_date).
                                replaceAll("[[LOAN_EXEMTION]]",     loan_exemtion).
                                replaceAll("[[LOAN_DURATION]]",     loan_duration).
                                replaceAll("[[LOAN_NAME]]",         loan_name).
                                replaceAll("[[LOAN_PERCENTAGE]]",   loan_percentage+"%").
                                replaceAll("[[LOAN_SUMM]]",         summa) 
                            )
                    })

                } else {
                    
                    // Replace words
                    let filial_name     = "<?php echo $model->filial->title; ?>"
                    let client_name     = "<?php echo $model->jur_name; ?>";
                    let client_date     = "<?php echo $model->claim_date.""; ?>"
                    let loan_exemtion   = "<?php echo $model->loanType->credit_exemtion; ?>"; /* Imtiyozli kredit */
                    let loan_duration   = "<?php echo $model->loanType->credit_duration; ?>";
                    let loan_name       = $("#loanName").val()
                    let loan_percentage = "<?php echo $model->loanType->procent; ?>";
                    let summa           = "<?php echo $model->convertNumberToWord($model->summa);?>"
                    
                    $("#DivIdToPrint").each(function() {
                        $(this).html(
                            $(this).html().
                                replaceAll("[[FILIAL_NAME]]",       filial_name).
                                replaceAll("[[CLIENT_FULLNAME]]",   client_name).
                                replaceAll("[[CLAIM_DATE]]",        client_date).
                                replaceAll("[[LOAN_EXEMTION]]",     loan_exemtion).
                                replaceAll("[[LOAN_DURATION]]",     loan_duration).
                                replaceAll("[[LOAN_NAME]]",         loan_name).
                                replaceAll("[[LOAN_PERCENTAGE]]",   loan_percentage+"%").
                                replaceAll("[[LOAN_SUMM]]",         summa)
                            )
                    })
                    
                }
            })

            function printDiv() {
                var divToPrint=document.getElementById('DivIdToPrint');

                var newWin=window.open('','Print-Window');

                newWin.document.open();

                newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

                newWin.document.close();

                setTimeout(function(){newWin.close();},10);
            }


        </script>
    </section>
    @include('layouts.footer')

    <!-- /.content -->
