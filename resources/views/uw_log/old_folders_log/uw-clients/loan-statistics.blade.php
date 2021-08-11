@extends('uw_log.uw.dashboard')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Xisobotlar
            <small>jadval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
            <li><a href="#">underwriter</a></li>
            <li class="active">underwriter</li>
        </ol>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Xatolik!</strong> xatolik bor.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

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

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary" style="clear: both;">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <button onclick="fnExcelReport()" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Export excel</button><br><br>
                        <table id="example1" class="table table-striped table-bordered">
                            <thead>
                            <tr class="text-sm">
                                <th>#</th>
                                <th>Mijoz FIO</th>
                                <th>Tug`ilgan yili</th>
                                <th>STIR</th>
                                <th>Passport</th>
                                <th>Jinsi</th>
                                <th>Mijoz manzili</th>
                                <th>Ish joyi</th>
                                <th>Oila a`zolari soni</th>
                                <th>Oylik daromadi(o`rtacha)</th>
                                <th>Kredit turi</th>
                                <th>Kredit %</th>
                                <th>Kredit muddati</th>
                                <th>Kredit summasi</th>
                                <th class="text-center">@lang('blade.status')</th>
                                <th class="text-center">Filial BXO</th>
                                <th class="text-center">BXO inspektori</th>
                            </tr>
                            </thead>
                            <tbody id="roleTable">
                            <?php $i = 1 ?>
                            @foreach ($models as $key => $model)
                                <tr id="rowId_{{ $model->id }}" class="text-sm">
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $model->family_name. ' '.$model->name. ' '.$model->patronymic}}</td>
                                    <td>{{ \Carbon\Carbon::parse($model->birth_date)->format('d.m.Y') }}</td>
                                    <td>{{ $model->inn }}</td>
                                    <td>{{ $model->document_serial.' '.$model->document_number }}</td>
                                    <td>
                                        @if($model->gender == 1)
                                            Erkak
                                            @else
                                            Ayol
                                        @endif
                                    </td>
                                    <td>{{ $model->live_address }}</td>
                                    <td>{{ $model->job_address }}</td>
                                    <td class="text-center">{{ $model->live_number }}</td>
                                    <td class="text-bold text-center">{{ number_format($model->inps1($model->id), 2) }}</td>
                                    <td>Mikroqarz</td>
                                    <td>{{ $model->procent }}</td>
                                    <td>{{ $model->credit_duration }}</td>
                                    <td>{{ number_format($model->summa, 2) }}</td>
                                    <td>
                                        @if($model->status == 0)
                                            Taxrirlashda
                                            @elseif($model->status == 2)
                                            Yangi
                                            @elseif($model->status == 3)
                                            Tasdiqlandi
                                            @elseif($model->status == 4)
                                            Yopilgan
                                        @endif
                                    </td>
                                    <td>{{ $model->filial->filial_code??'' }} - {{ $model->filial->title??'' }}</td>
                                    <td>{{ $model->user->lname??'' }} {{ $model->user->fname??'' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>

        <script src="{{ asset ("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

        <script src="{{ asset ("/js/jquery.validate.js") }}"></script>

        <script src="{{ asset("/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>

        <script src="{{ asset("/admin-lte/plugins/datatables/dataTables.bootstrap.min.js") }}"></script>
        <script>

            $(function () {
                $("#example1").DataTable();
            });

            function fnExcelReport(filename = '')
            {
                var tab_text="<table border='2px'><tr bgcolor=''>";
                var textRange; var j=0;
                tab = document.getElementById('example1'); // id of table

                for(j = 0 ; j < tab.rows.length ; j++)
                {
                    tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
                    //tab_text=tab_text+"</tr>";
                }

                tab_text=tab_text+"</table>";
                tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
                tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
                tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

                var ua = window.navigator.userAgent;
                var msie = ua.indexOf("MSIE ");

                downloadLink = document.createElement("a");
                document.body.appendChild(downloadLink);
                filename = filename?filename+'.xls':'excel_data.xls';
                var dataType = 'application/vnd.ms-excel';

                if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
                {
                    txtArea1.document.open("txt/html","replace");
                    txtArea1.document.write(tab_text);
                    txtArea1.document.close();
                    txtArea1.focus();
                    sa=txtArea1.document.execCommand("SaveAs",true,"Say Thanks to Sumit.xls");
                }
                else                 //other browser not tested on IE 11
                    //sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
                    //return (sa);
                    /*window.open('data:application/vnd.ms-'+defaults.type+';filename=exportData.xlsx;' + base64data);*/

                    downloadLink.href = 'data:' + dataType + ', ' + encodeURIComponent(tab_text);

                    // Setting the file name
                    downloadLink.download = filename;

                    //triggering the function
                    downloadLink.click();
            }

        </script>
    </section>
    <!-- /.content -->
@endsection
