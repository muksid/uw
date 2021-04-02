@extends('layouts.uw.dashboard')

@section('content')

    <section class="content-header">
        <h1>
            Kredit natijasi
            <small>jadval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
            <li><a href="#">underwriter</a></li>
            <li class="active">underwriter</li>
        </ol>

        @if (Session::has('message'))
            <div class="alert alert-{{ Session::get('status') }} alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-{{ Session::get('status') }}"></i> Message!</h4>
                {{ Session::get('message') }}
            </div>
        @endif

    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary" style="clear: both">
                    <!-- /.box-header -->
                    <div class="box-body">

                        <div id="loading-gif" class="loading-gif" style="display: none"></div>

                        <div class="row">

                            <div class="col-md-6">
                                <div class="col-md-12 bg-gray">
                                    <div class="box-body">
                                        <table class="table table-bordered">
                                            <tbody>
                                            <tr>
                                                <th style="width: 10px">#</th>
                                                <th colspan="3"><i class="fa fa-user"></i> Mijoz pasport ma`lumotlari</th>
                                            </tr>
                                            <tr>
                                                <td>1.</td>
                                                <td>FIO</td>
                                                <td>{{ $model->family_name ?? ''}} {{ $model->name ?? ''}} {{ $model->patronymic??'' }}</td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2.</td>
                                                <td>Tug`ilgan yili</td>
                                                <td><i class="fa fa-calendar"></i>
                                                    {{ \Carbon\Carbon::parse($model->birth_date)->format('d.m.Y') }} yil.
                                                </td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3.</td>
                                                <td>Jinsi</td>
                                                <td><i class="fa fa-child"></i>
                                                    {{ $model->gender == 1 ? 'Erkak' : 'Ayol' }}
                                                </td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>4.</td>
                                                <td>Pasport ma`lumotlari</td>
                                                <td><i class="fa fa-user"></i>
                                                    Pasport turi: <b>@if($model->document_type == 0)
                                                            ID karta
                                                        @elseif($model->document_type == 6)
                                                            Pasport
                                                        @endif
                                                    </b>
                                                    {{ $model->document_serial ?? ''}} {{ $model->document_number ?? ''}}
                                                    {{ \Carbon\Carbon::parse($model->document_date)->format('d.m.Y') }} yil.
                                                </td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>5.</td>
                                                <td>Pasport berilgan joyi</td>
                                                <td><i class="fa fa-globe"></i>
                                                    {{ \Carbon\Carbon::parse($model->document_date)->format('d.m.Y') }} yilda
                                                    {{ $model->region1->name?? '' }} {{ $model->docDistrict->name?? '1'}}  <br>
                                                    IIB tomonidan berilgan
                                                </td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>6.</td>
                                                <td>Yashash joy ma`lumotlari</td>
                                                <td><i class="fa fa-globe"></i>
                                                    {{ $model->region->name?? ''}}, {{ $model->district->name?? ''}}
                                                    {{ $model->registration_address ?? ''}}
                                                </td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>7.</td>
                                                <td>Telefon raqam</td>
                                                <td><i class="fa fa-phone"></i>
                                                    {{ $model->phone }}
                                                </td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>8.</td>
                                                <td>Mijoz INPS</td>
                                                <td><i class="fa fa-user"></i>
                                                    {{ $model->pin }}
                                                    <span class="text-danger text-bold">
                                                        @if($model->is_inps == 1)
                                                            Tashkilot xodimi (INPS bor)
                                                        @else
                                                            Nafaqada || Organ xodimi (INPS yo`q)
                                                        @endif
                                                    </span>
                                                </td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>9.</td>
                                                <td>Mijoz INN</td>
                                                <td>
                                                    {{ $model->inn }}
                                                </td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>10.</td>
                                                <td>Mijoz ish joy manzili</td>
                                                <td><i class="fa fa-map-marker"></i>
                                                    {{ $model->job_address }}
                                                </td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                            </tbody></table>
                                    </div>
                                </div>

                                <div class="col-md-12 bg-danger">
                                    <div class="box-body">
                                        <table class="table table-bordered">
                                            <tbody>
                                            <tr>
                                                <th style="width: 10px">#</th>
                                                <th colspan="3"><i class="fa fa-credit-card"></i> Kredit ma`lumotlari</th>
                                            </tr>
                                            <tr>
                                                <td>1.</td>
                                                <td>Kredit turi</td>
                                                <td class="text-bold"><i class="fa fa-car"></i> {{ $model->loanType->title ?? '' }}</td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2.</td>
                                                <td>Kredit davri</td>
                                                <td class="text-bold"><i class="fa fa-hourglass-1"></i>
                                                    {{ $model->loanType->credit_duration ?? '' }} oy.
                                                </td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2.</td>
                                                <td>Kredit imtiyozli davr</td>
                                                <td class="text-bold"><i class="fa fa-hourglass-2"></i> {{ $model->loanType->credit_exemtion ?? '' }} oy.</td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3.</td>
                                                <td>Kredit %</td>
                                                <td class="text-bold"><i class="fa fa-balance-scale"></i> {{ $model->loanType->procent ?? '' }} %</td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>4.</td>
                                                <td>Kredit summasi</td>
                                                <td class="text-bold"><i class="fa fa-cc"></i>
                                                    {{ number_format($model->summa ?? '')}} so`m.
                                                </td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>5.</td>
                                                <td>Ariza raqami</td>
                                                <td class="text-bold"><i class="fa fa-list"></i>
                                                    {{ $model->claim_id ?? ''}}
                                                </td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>6.</td>
                                                <td>Ariza sanasi</td>
                                                <td class="text-bold"><i class="fa fa-calendar"></i>
                                                    {{ \Carbon\Carbon::parse($model->claim_date)->format('d.m.Y') }}
                                                </td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>7.</td>
                                                <td>IABS unikal kodi</td>
                                                <td class="text-bold"><i class="fa fa-internet-explorer"></i>
                                                    {{ $model->iabs_num ?? ''}}
                                                </td>
                                                <td>
                                                    <i class="fa fa-check-circle text-info"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                            </tbody></table>
                                    </div>

                                </div>

                                <div class="col-md-12">
                                    <div class="box-body">
                                        <div class="box-header with-border">
                                            <div class="col-md-12">
                                                @if($model->status == 0 || $model->status == 1)
                                                    <a href="javascript:void(0)" class="btn btn-danger" id="add-new-debtor"><i class="fa fa-plus-circle"></i> Qo`shimcha qarzdor kiriting</a>
                                                @else
                                                    Qo`shimcha qarzdor ma`lumotlari
                                                @endif
                                            </div>
                                        </div>
                                        <!-- /.box-header -->
                                        <table class="table table-striped table-bordered" id="debtors_datatable">
                                            <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Qo`shimcha qarzdor FIO</th>
                                                <th>STIR</th>
                                                <th>Ish joy manzili</th>
                                                <th>Jami oylik daromadi</th>
                                                <th>Jami (Oy)da</th>
                                                <th>To`lov qobiliyati</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tfoot align="right" class="bg-danger">
                                            <tr>
                                                <th colspan="4">Jami:</th>
                                                <th colspan="2"></th>
                                                <th colspan="2"></th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="box-body">
                                        <div class="box-header with-border">
                                            <div class="col-md-12">
                                                @if($model->status == 0 || $model->status == 1)
                                                    <a href="javascript:void(0)" class="btn btn-warning" id="add-new-post"><i class="fa fa-plus-circle"></i> Ta`minot kiriting</a>
                                                @else
                                                    Kafil ma`lumotlari
                                                @endif
                                            </div>
                                        </div>
                                        <!-- /.box-header -->
                                        <table class="table table-striped table-bordered" id="guar_datatable">
                                            <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Ta`minot turi</th>
                                                <th>Nomi</th>
                                                <th>Ta`minot egasi</th>
                                                <th>Ta`minot summasi</th>
                                                <th>Manzil</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="box-body">
                                        <div class="box-header with-border">
                                            <div class="col-md-12">
                                                @if($model->status == 0 || $model->status == 1)
                                                    <a href="javascript:void(0)" class="btn btn-info" id="add-new-file"><i class="fa fa-plus-circle"></i> Ilova kiriting</a>
                                                @else
                                                    Ilova hujjatlari
                                                @endif
                                            </div>
                                        </div>
                                        <!-- /.box-header -->
                                        <table class="table table-striped table-bordered" id="file_datatable">
                                            <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Ilova nomi</th>
                                                <th>Ilova hajmi</th>
                                                <th>Yuklash</th>
                                                <th>O`chirish</th>
                                            </tr>
                                            </thead>
                                        </table>

                                    </div>

                                </div>

                            </div>

                            <div class="col-md-6">
                                <div class="col-md-12">
                                    <div class="box box-widget widget-user-2">
                                        <div class="widget-user-header bg-aqua-active">
                                            <button class="btn btn-flat btn-success margin inquiry-individual" data-id="{{ $model->id }}">
                                                <i class='fa fa-globe'></i> KATMga so`rov yuborish</button>
                                            <h5 class="widget-user-desc">online request</h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div id="katm_inps_buttons"></div>
                                    </div>
                                    <div class="box box-widget widget-user-2">
                                        <div class="widget-user-header bg-blue-active">
                                            <h3 class="widget-user-username">Ball: <span id="scoring_ball"></span> <i class='fa fa-line-chart'></i> Kredit ajratish natijasi</h3>
                                        </div>
                                        <div class="box-footer no-padding">
                                            <ul class="nav nav-stacked">
                                                <li><a href="#">1. Kredit qarzdorligi:
                                                        <span class="pull-right badge bg-red-active" style="font-size: large">
                                                            <span id="credit_debt"></span> so`m
                                                        </span>
                                                    </a>
                                                </li>
                                                <li><a href="#">2. Jami oylik ish xaqi:
                                                        <span class="pull-right badge bg-light-blue">
                                                            <span id="total_month_salary"></span> so`m <span id="total_monthly"></span> oyda
                                                        </span>
                                                    </a>
                                                </li>
                                                <li><a href="#">3. Xar oy to`lov qobiliyati:
                                                        <span class="pull-right badge bg-danger" style="font-size: large">
                                                            <span id="total_month_payment"></span> so`m
                                                        </span>
                                                    </a>
                                                </li>
                                                <li><a href="#">4. Mijoz so`ragan kredit:
                                                        <span class="pull-right badge bg-gray-active">
                                                            <span id="credit_sum"></span> so`m
                                                        </span>
                                                    </a>
                                                </li>
                                            </ul>
                                            <table class="table table-bordered">
                                                <tbody class="text-center">
                                                <tr>
                                                    <th colspan="2"><h4 class="text-maroon text-bold"><i class="fa fa-calculator"></i> Kredit grafik turini tanlang?</h4></th>
                                                </tr>
                                                <tr class="bg-blue-active">
                                                    <div class="form-group">
                                                        <th>
                                                            <label>
                                                                Differentsial (Oddiy)
                                                                <input type="radio" name="sch_type" value="1" class="flat-red" checked>
                                                            </label>
                                                        </th>
                                                        <th>
                                                            <label>
                                                                Annuitet
                                                                <input type="radio" name="sch_type" value="2" class="flat-red">
                                                            </label>
                                                        </th>
                                                    </div>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" class="text-bold bg-gray-active">Mijozning kredit bo`chiya o`rtacha oylik to`lovi:</td>
                                                </tr>
                                                <tr class="bg-aqua-active">
                                                    <td>
                                                        <h4><span id="monthly_pay"></span> so`m</h4>
                                                    </td>
                                                    <td>
                                                        <h4><span id="monthly_pay_ann"></span> so`m</h4>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" class="text-bold bg-gray-active">Mijozga shu miqdorda kredit ajratish mumkin:</td>
                                                </tr>
                                                <tr class="bg-aqua-active">
                                                    <td>
                                                        <h4><span id="credit_can_be"></span> so`m</h4>
                                                    </td>
                                                    <td>
                                                        <h4><span id="credit_can_be_ann"></span> so`m</h4>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="box-header with-border">
                                        @switch($model->status)
                                            @case(1)
                                            <div id="send_to_admin_buttons"></div>
                                            @break
                                            @case(2)
                                            <h3 class="text-primary"><i class="fa fa-clock-o"></i> Ariza Yuborilgan</h3>
                                            @break
                                            @case(3)
                                            <h3 class="text-green"><i class="fa fa-check-circle-o"></i> Ariza Tasdiqlandi</h3>
                                            @break
                                            @case(0)
                                            <h3 class="text-maroon"><i class="fa fa-pencil"></i> Ariza Taxrirlashda</h3>
                                            <h4 class="text-danger"><i class="fa fa-commenting"></i> Izoxlar tarixi</h4>
                                            @if($modelComments)
                                                @foreach($modelComments as $key => $value)
                                                    <div class="comment-text">
                                                          <span class="username">
                                                              <b>{{ $key+1 }}.</b>
                                                            <span class="text-muted pull-right"><i class="fa fa-clock-o"></i>
                                                                {{ \Carbon\Carbon::parse($value->created_at)->format('d.m.Y H:i') }}</span>
                                                          </span><!-- /.username -->
                                                        {{ $value->title }}
                                                    </div>
                                                @endforeach

                                            @endif

                                            <div id="send_to_admin_buttons"></div>
                                            @break
                                        @endswitch
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

        <!-- create / update DEBTOR modal -->
        <div class="modal fade" id="ajax-debtor-modal" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="debtorModal"></h4>
                    </div>
                    <div class="modal-body">
                        <form id="debtorForm" name="debtorForm">
                            <input type="hidden" name="debtor_id" id="debtor_id">
                            <input type="hidden" name="model_id" id="model_id" value="{{ $model->id }}">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Familiyasi</label>
                                        <input type="text" class="form-control" id="family_name" name="family_name" value="" required="">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Ismi</label>
                                        <input type="text" class="form-control" id="name" name="name" value="" required="">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Otasining ismi</label>
                                        <input type="text" class="form-control" id="patronymic" name="patronymic" value="" required="">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Jinsi</label>
                                        <select class="form-control" name="gender" id="gender">
                                            <option value="1" SELECTED>Erkak</option>
                                            <option value="2">Ayol</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Pas.Ser</label>
                                        <input type="text" class="form-control" id="document_serial" name="document_serial" minlength="2" maxlength="2" value="" required=""
                                               onkeydown="return alphaOnly(event);"
                                               oninput="this.value = this.value.toUpperCase()">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Pas.Raq</label>
                                        <input type="number" class="form-control" id="document_number" name="document_number" minlength="7" maxlength="7" value="" required="">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Berilgan vaqti</label>
                                        <input type="date" class="form-control" id="document_date" name="document_date" value="" required="">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>STIR</label>
                                        <input type="number" class="form-control" id="inn" name="inn" value="" minlength="9" maxlength="9" required="">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Resident</label>
                                        <select class="form-control" name="resident" id="resident">
                                            <option value="1" SELECTED>XA</option>
                                            <option value="2">YO`Q</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Tug`ilgan yili</label>
                                        <input type="date" class="form-control" id="birth_date" name="birth_date" value="" required="">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Viloyat</label>
                                        <select class="form-control" name="document_region" id="document_region">
                                            @foreach($regions as $key => $value)

                                                <option value="{{ $value->code }}">{{ $value->name }}</option>

                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Tuman</label>
                                        <select class="form-control" name="document_district" id="document_district">
                                            @foreach($districts as $key => $value)

                                                <option value="{{ $value->code }}">{{ $value->name }}</option>

                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Yashash manzili</label>
                                        <input type="text" class="form-control" id="live_address" name="live_address" maxlength="255" value="" required="">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Ish joy manzili</label>
                                        <input type="text" class="form-control" id="job_address" name="job_address" maxlength="255" value="" required="">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>PINFL</label>
                                        <input type="number" class="form-control" id="pin" name="pin" minlength="14" maxlength="14" value="" required="">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Jami oylik daromadi</label>
                                        <input type="number" class="form-control" id="total_sum" name="total_sum" value="" required="">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Jami (Oy) da</label>
                                        <input type="number" class="form-control" id="total_month" name="total_month" maxlength="2" value="" required="">
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Bekor qilish</button>
                                <button type="submit" class="btn btn-primary" id="btn-debtor-save" value="create"><i class="fa fa-save"></i> Saqlash</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- DELETE DEBTOR modal -->
        <div id="ConfirmDebtorModal" class="modal fade modal-danger" role="dialog">
            <div class="modal-dialog modal-sm">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title text-center">O`chirishni tasdiqlash</h4>
                    </div>

                    <div class="modal-body">
                        <h4 class="text-center"><span class="glyphicon glyphicon-info-sign"></span> Qo`shimcha qarzdor serverdan o`chiriladi!</h4>
                    </div>

                    <div class="modal-footer">
                        <center>
                            <button type="button" class="btn btn-outline pull-left"
                                    data-dismiss="modal">@lang('blade.cancel')</button>
                            <button type="button" class="btn btn-outline" id="yesDebtorDelete"
                                    value="create">Ha, O`chirish
                            </button>
                        </center>
                    </div>
                </div>
            </div>
        </div>

        <!-- create / update GUAR modal -->
        <div class="modal fade" id="ajax-crud-modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="postCrudModal"></h4>
                    </div>
                    <div class="modal-body">
                        <form id="postForm" name="postForm">
                            <input type="hidden" name="post_id" id="post_id">
                            <input type="hidden" name="model_id" id="model_id" value="{{ $model->id }}">
                            <input type="hidden" name="claim_id" id="claim_id" value="{{ $model->claim_id }}">
                            <div class="row">
                                <div class="col-sm-6">
                                    <!-- text input -->
                                    <div class="form-group">
                                        <label>Kafil turi</label>
                                        <select class="form-control" name="guar_type" id="guar_type">
                                            <option value="K" SELECTED>Kafillik</option>
                                            <option value="S">Sug`urta</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <!-- text input -->
                                    <div class="form-group">
                                        <label>Kafillik nomi</label>
                                        <input type="text" class="form-control" id="title" name="title" value="" required="">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <!-- text input -->
                                    <div class="form-group">
                                        <label>Kafillik egasi</label>
                                        <input type="text" class="form-control" id="guar_owner" name="guar_owner" value="" required="">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <!-- text input -->
                                    <div class="form-group">
                                        <label>Kafillik summasi</label>
                                        <input type="number" class="form-control" id="guar_sum" name="guar_sum" value="" required="">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <!-- text input -->
                                    <div class="form-group">
                                        <label>Manzil</label>
                                        <input type="text" class="form-control" id="address" name="address" value="" required="">
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Bekor qilish</button>
                                <button type="submit" class="btn btn-primary" id="btn-save" value="create"><i class="fa fa-save"></i> Saqlash</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- DELETE GUAR modal -->
        <div id="ConfirmModal" class="modal fade modal-danger" role="dialog">
            <div class="modal-dialog modal-sm">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title text-center">O`chirishni tasdiqlash</h4>
                    </div>

                    <div class="modal-body">
                        <h4 class="text-center"><span class="glyphicon glyphicon-info-sign"></span> Kafil serverdan o`chiriladi!</h4>
                    </div>

                    <div class="modal-footer">
                        <center>
                            <button type="button" class="btn btn-outline pull-left"
                                    data-dismiss="modal">@lang('blade.cancel')</button>
                            <button type="button" class="btn btn-outline" id="yesDelete"
                                    value="create">Ha, O`chirish
                            </button>
                        </center>
                    </div>
                </div>
            </div>
        </div>

        <!-- MESSAGE GUAR modal -->
        <div id="MessageModal" class="modal fade modal-warning" role="dialog">
            <div class="modal-dialog modal-sm">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title text-center">O`chirishda xatolik yuz berdi!</h4>
                    </div>

                    <div class="modal-body">
                        <h4 class="text-center"><span class="glyphicon glyphicon-info-sign"></span> <span id="message"></span></h4>
                    </div>

                    <div class="modal-footer">
                        <center>
                            <button type="button" class="btn btn-outline pull-right"
                                    data-dismiss="modal">@lang('blade.close')</button>
                        </center>
                    </div>
                </div>
            </div>
        </div>

        <!-- create FILE modal -->
        <div class="modal fade" id="ajax-crud-file-modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="postCrudFileModal"></h4>
                    </div>
                    <div class="modal-body">
                        <form method="POST" enctype="multipart/form-data" id="postFileForm" name="postFileForm">
                            {{ csrf_field() }}
                            <input type="hidden" name="model_file_id" id="model_file_id" value="{{ $model->id }}">
                            <div class="row">
                                <div class="col-sm-6">
                                    <!-- text input -->
                                    <div class="form-group">
                                        <label>File tanlang</label>
                                        <input type="file" name="model_file" id="model_file" multiple />
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Bekor qilish</button>
                                <button type="submit" class="btn btn-primary" id="btn-file-save" value="create"><i class="fa fa-save"></i> Saqlash</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- delete FILE modal -->
        <div id="ConfirmFileModal" class="modal fade modal-danger" role="dialog">
            <div class="modal-dialog modal-sm">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title text-center">O`chirishni tasdiqlash</h4>
                    </div>

                    <div class="modal-body">
                        <h4 class="text-center"><span class="glyphicon glyphicon-info-sign"></span> Fayl serverdan o`chiriladi!</h4>
                    </div>

                    <div class="modal-footer">
                        <center>
                            <button type="button" class="btn btn-outline pull-left"
                                    data-dismiss="modal">@lang('blade.cancel')</button>
                            <button type="button" class="btn btn-outline" id="yesFileDelete"
                                    value="create">Ha, O`chirish
                            </button>
                        </center>
                    </div>
                </div>
            </div>
        </div>

        <div id="ResultMessageModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-sm">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title text-center">
                            <span class="glyphicon glyphicon-info-sign"></span> <span id="result_header"></span>
                        </h4>
                    </div>

                    <div class="modal-body">
                        <h3 class="text-center"><span id="result_title"></span></h3>
                        <h3 class="text-center"><span id="result_text"></span></h3>
                    </div>

                    <div class="modal-footer">
                        <center>
                            <button type="button" class="btn btn-outline pull-left"
                                    data-dismiss="modal">@lang('blade.close')</button>
                        </center>
                    </div>
                </div>
            </div>
        </div>

        <!--confirm form-->
        <div class="modal fade modal-primary" id="modalFormSend" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modalHeader"></h4>
                    </div>
                    <div class="modal-body">
                        <h5><span class="glyphicon glyphicon-info-sign"></span> <span id="modalBody"></span></h5>
                    </div>
                    <form id="sendForm" name="sendForm">
                        <input type="hidden" name="uw_clients_id" id="uw_clients_id" value="{{ $model->id }}">
                        <div class="col-md-10">
                            <div class="box-body">
                                <div class="form-group">
                                    <label>IABS unikal raqam<span class="text-red">*</span></label>
                                    <input type="number" id="iabs_num" name="iabs_num"
                                           class="form-control" placeholder="60603030" maxlength="8" required>

                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <center>
                                <button type="submit" class="btn btn-outline" id="btn-save-send"
                                        value="create">Ha, Yuborish
                                </button>
                                <button type="button" class="btn btn-outline pull-left"
                                        data-dismiss="modal">@lang('blade.cancel')</button>
                            </center>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        {{--KATM scoring ball--}}
        <div class="modal fade" id="resultKATMModal" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="width: 1100px">
                <div class="modal-content">
                    <div class="modal-header bg-aqua-active">
                        <button type="button" class="btn btn-outline pull-right" onclick="print('resultKATMModal')">
                            <i class="fa fa-print"></i> @lang('blade.print')
                        </button>
                        <h4 class="modal-title text-center" id="success">KATM Scoring bali</h4>
                    </div>
                    <div id="scoringPage"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('blade.close')
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- INPS oylik ish haqi -->
        <div class="modal fade" id="resultINPSModal" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-aqua-active">
                        <button type="button" class="btn btn-outline pull-right" onclick="print('resultINPSModal')">
                            <i class="fa fa-print"></i> @lang('blade.print')
                        </button>
                        <h4 class="modal-title text-center" id="success_inps">Mijoz oylik ish xaqi daromadi (INPS)</h4>
                    </div>
                    <div class="modal-body">
                        <h4 id="base64INPSSuccess_result" class="text-center">Mijoz:
                            <b>{{ $model->family_name.' '.$model->name.' '.$model->patronymic }}</b>
                        </h4>
                        <div id="resultDataINPS"></div>
                        <div id="resultDataINPSTotal" class="text-bold"></div>
                    </div>
                </div>
            </div>
        </div>

        <script src="{{ asset ("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>
        <script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>
        <script src="{{ asset ("/js/jquery.validate.js") }}"></script>

        <style>
            .loading-gif {
                background: url({{asset('images/loading-3.gif')}}) no-repeat center center;
                position: absolute;
                top: 0;
                left: 0;
                height: 100%;
                width: 100%;
                z-index: 9999999;
                opacity: 0.5;
            }
            table{ border-collapse:collapse; width:100%; }
            table td, th{ border:1px solid #d0d0d0; }

            #main_block table {font-size:9px;}
            .header-color {background-color:#8EB2E2;}
            .mip-color {background-color:#e3fbf7;}
            .procent25_class {width:25%;}
        </style>

        <script>
            var resultButton = $("#katm_inps_buttons");
            var sendToAdminButton = $("#send_to_admin_buttons");
            var id = "{{ $model->id }}";
            var is_inps = "{{ $model->is_inps }}";
            var button_res_k = "<button class='btn btn-flat btn-bitbucket margin' id='getResultKATM' data-id='"+id+"'><i class='fa fa-history'></i> KATM natijasi</button>";
            var button_res_i = "<button class='btn btn-flat btn-bitbucket margin' id='getResultINPS' data-id='"+id+"'><i class='fa fa-credit-card'></i> INPS natijasi</button>";
            var button_send_a = "<button class='btn btn-flat btn-bitbucket margin' id='sendToAdmin' data-id='"+id+"'><i class='fa fa-send-o'></i> Adminstratorga yuborish</button>";
            function ResultButtons(res){
                //console.log(res);
                $('#credit_debt').empty().append(formatCurrency(res.credit_results.credit_debt));
                $('#credit_can_be').empty().append(formatCurrency(res.credit_results.credit_can_be));
                $('#credit_can_be_ann').empty().append(formatCurrency(res.credit_results.credit_can_be_ann));
                $('#credit_sum').empty().append(formatCurrency(res.credit_results.credit_sum));
                $('#total_month_payment').empty().append(formatCurrency(res.credit_results.total_month_payment));
                $('#total_month_salary').empty().append(formatCurrency(res.credit_results.total_month_salary));
                $('#total_monthly').empty().append(res.credit_results.total_monthly);
                $('#monthly_pay').empty().append(formatCurrency(res.credit_results.monthly_pay));
                $('#monthly_pay_ann').empty().append(formatCurrency(res.credit_results.monthly_pay_ann));
                $('#scoring_ball').empty().append(res.credit_results.scoring_ball);
                if (is_inps != 1){
                    button_res_i = '';
                }
                $('#katm_inps_buttons').empty().append(button_res_k+''+button_res_i);
                $('#send_to_admin_buttons').empty().append(button_send_a);
            }

            var katm_inps_route = "{{ route('uw.get-result-buttons', ['id' => $model->id]) }}";
            $.get(katm_inps_route, function(res){
                //console.log(res.data_i.length);
                $('#credit_can_be').append(formatCurrency(res.credit_results.credit_can_be));
                $('#credit_can_be_ann').append(formatCurrency(res.credit_results.credit_can_be_ann));
                $('#credit_sum').append(formatCurrency(res.credit_results.credit_sum));
                $('#total_month_payment').append(formatCurrency(res.credit_results.total_month_payment));
                $('#total_month_salary').append(formatCurrency(res.credit_results.total_month_salary));
                $('#total_monthly').append(res.credit_results.total_monthly);
                $('#monthly_pay').append(formatCurrency(res.credit_results.monthly_pay));
                $('#monthly_pay_ann').append(formatCurrency(res.credit_results.monthly_pay_ann));
                $('#scoring_ball').append(res.credit_results.scoring_ball);

                sendToAdminButton.append(button_send_a);

                if (res.data_k !== null) {
                    resultButton.append(button_res_k);
                    $('#credit_debt').append(formatCurrency(res.credit_results.credit_debt));
                }
                if (res.data_i.length > 0) {
                    resultButton.append(button_res_i);
                }

            });

            function formatCurrency(total) {
                var neg = false;
                if(total < 0) {
                    neg = true;
                    total = Math.abs(total);
                }
                return (neg ? "-" : '') + parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString();
            }

            function alphaOnly(event) {
                var key = event.keyCode;
                return ((key >= 65 && key <= 90) || key == 8);
            }

            $(document).ready( function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // request KATM
                $(".inquiry-individual").click(function(e){
                    e.preventDefault();

                    var id = $(this).data('id');

                    $.ajax({
                        url: "{{ route('uw.online.registration') }}",
                        data: {id: id},
                        dataType: 'JSON',
                        type: 'POST',
                        beforeSend: function(){

                            $(".inquiry-individual").prop('disabled', true);
                            $("#loading-gif").show();

                        },
                        success: function(response){
                            //console.log(response);
                            $(".inquiry-individual").prop('disabled', false);
                            $("#loading-gif").hide();
                            $('#ResultMessageModal').addClass('modal-'+response.status);
                            $('#result_header').empty().append(response.status);
                            $('#result_title').empty().append(response.message);
                            $('#result_text').empty().append(response.data);
                            $('#ResultMessageModal').modal('show');
                            ResultButtons(response);

                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            console.log('Error '+xhr.status+' | '+thrownError);

                        },
                    });

                });

                // BUTTON GET KATM RESULT
                $('body').on('click', '#getResultKATM', function () {
                    var id = $('#getResultKATM').data('id');
                    $.get('/uw/get-client-res-k/' + id, function (data) {
                        //console.log(data);
                        $('#scoringPage').empty();
                        $("#scoringPage").prepend(data.scoring_page);
                        $(".client_name").html(data.client.family_name + ' ' + data.client.name + ' ' + data.client.patronymic);
                        var formattedDate = new Date(data.client.birth_date);
                        var d = formattedDate.getDate();
                        var m = formattedDate.getMonth();
                        m += 1;  // JavaScript months are 0-11
                        if (d < 10) {
                            d = "0" + d;
                        }
                        if (m < 10) {
                            m = "0" + m;
                        }
                        var y = formattedDate.getFullYear();
                        $(".client_birth_date").html(d + "." + m + "." + y);
                        if (data.client.gender == 1) {
                            var gender = 'Erkak';
                        } else {
                            gender = 'Ayol';
                        }
                        $(".client_gender").html(gender);
                        $(".client_live_address").html(data.client.live_address);
                        $(".client_pin").html(data.client.pin);
                        $(".client_inn").html(data.client.inn);
                        $(".client_phone").html(data.client.phone);

                        var doc_formattedDate = new Date(data.client.document_date);
                        var doc_d = doc_formattedDate.getDate();
                        var doc_m = doc_formattedDate.getMonth();
                        doc_m += 1;  // JavaScript months are 0-11
                        if (doc_d < 10) {
                            doc_d = "0" + doc_d;
                        }
                        if (doc_m < 10) {
                            doc_m = "0" + doc_m;
                        }
                        var doc_y = doc_formattedDate.getFullYear();
                        $(".client_document").html(data.client.document_serial + ' ' + data.client.document_number + ' '
                            + doc_d + "." + doc_m + "." + doc_y);

                        $('.sc_ball').html(data.scoring.sc_ball);
                        $('.sc_level_info').html(data.scoring.sc_level_info);
                        $('.sc_version').html(data.scoring.sc_version);
                        $('.score_date').html(data.scoring.score_date);

                        $('.client_info_1').html(data.scoring.client_info_1); // fio
                        $('.client_info_2_text').html(data.scoring.client_info_2_text); // den roj
                        $('.client_info_4').html(data.scoring.client_info_4); // adress
                        $('.client_info_5').html(data.scoring.client_info_5); // pinfl
                        $('.client_info_6').html(data.scoring.client_info_6); // inn
                        $('.client_info_8').html(data.scoring.client_info_8); // passport

                        $('.score_img').html('' +
                            '<img id="score_chart" style="padding-left: 9px; margin-bottom: 15px; width: 311px;"' +
                            ' src="' + data.scoring_page1 + '" height="149">\n');

                        $('#tb_row_1_ot').html(data.table.row_1.open_total);
                        $('#tb_row_1_os').html(data.table.row_1.open_summ);
                        $('#tb_row_1_ct').html(data.table.row_1.close_total);
                        $('#tb_row_1_cs').html(data.table.row_1.close_summ);

                        $('#tb_row_2_ot').html(data.table.row_2.open_total);
                        $('#tb_row_2_os').html(data.table.row_2.open_summ);
                        $('#tb_row_2_ct').html(data.table.row_2.close_total);
                        $('#tb_row_2_cs').html(data.table.row_2.close_summ);

                        $('#tb_row_3_ot').html(data.table.row_3.open_total);
                        $('#tb_row_3_os').html(data.table.row_3.open_summ);
                        $('#tb_row_3_ct').html(data.table.row_3.close_total);
                        $('#tb_row_3_cs').html(data.table.row_3.close_summ);

                        $('#tb_row_4_ot').html(data.table.row_4.open_total);
                        $('#tb_row_4_os').html(data.table.row_4.open_summ);
                        $('#tb_row_4_ct').html(data.table.row_4.close_total);
                        $('#tb_row_4_cs').html(data.table.row_4.close_summ);

                        $('#tb_row_5_ot').html(data.table.row_5.open_total);
                        $('#tb_row_5_os').html(data.table.row_5.open_summ);
                        $('#tb_row_5_ct').html(data.table.row_5.close_total);
                        $('#tb_row_5_cs').html(data.table.row_5.close_summ);

                        $('#tb_row_6_ot').html(data.table.row_6.open_total);
                        $('#tb_row_6_os').html(data.table.row_6.open_summ);
                        $('#tb_row_6_ct').html(data.table.row_6.close_total);
                        $('#tb_row_6_cs').html(data.table.row_6.close_summ);

                        if (data.table.row_7){
                            $('#tb_row_7_ot').html(data.table.row_7.open_total);
                            $('#tb_row_7_os').html(data.table.row_7.open_summ);
                            $('#tb_row_7_ct').html(data.table.row_7.close_total);
                            $('#tb_row_7_cs').html(data.table.row_7.close_summ);
                        } else {
                            $('#tb_row_7_ot').html(0);
                            $('#tb_row_7_os').html(0);
                            $('#tb_row_7_ct').html(0);
                            $('#tb_row_7_cs').html(0);
                        }

                        if (data.table.row_8){
                            $('#tb_row_8_ot').html(data.table.row_8.open_total);
                            $('#tb_row_8_os').html(data.table.row_8.open_summ);
                            $('#tb_row_8_ct').html(data.table.row_8.close_total);
                            $('#tb_row_8_cs').html(data.table.row_8.close_summ);
                        } else {
                            $('#tb_row_8_ot').html(0);
                            $('#tb_row_8_os').html(0);
                            $('#tb_row_8_ct').html(0);
                            $('#tb_row_8_cs').html(0);
                        }

                        if (data.table.row_9){
                            $('#tb_row_9_ot').html(data.table.row_9.open_total);
                            $('#tb_row_9_os').html(data.table.row_9.open_summ);
                            $('#tb_row_9_ct').html(data.table.row_9.close_total);
                            $('#tb_row_9_cs').html(data.table.row_9.close_summ);
                        } else {
                            $('#tb_row_9_ot').html(0);
                            $('#tb_row_9_os').html(0);
                            $('#tb_row_9_ct').html(0);
                            $('#tb_row_9_cs').html(0);
                        }

                        $('#tb_row_12_agr_summ').html(data.table.row_12.agr_summ);
                        $('#tb_row_12_agr_comm2').html(data.table.row_12.agr_comm2.content);
                        $('#tb_row_12_agr_comm3').html(data.table.row_12.agr_comm3);
                        $('#tb_row_12_agr_comm4').html(data.table.row_12.agr_comm4);

                        $('#tb_ft_claim_id').html(data.client.claim_id);
                        $('#tb_ft_claim_date').html(data.client.claim_date);

                        $('#btn-save').val("KatmResult");
                        $('#katmClaimId').val(id);
                        $('#resultKATMModal').modal('show');
                    })
                });

                // BUTTON GET INPS RESULT
                $('body').on('click', '#getResultINPS', function () {

                    var id = $('#getResultINPS').data('id');

                    $.get('/uw/get-client-res-i/' + id, function (data) {
                        //console.log(data);
                        var data_inps = "";

                        data_inps +="<div class='box-body table-responsive no-padding'>" +
                            "<table class='tabla table-hover'>"+
                            "<tbody>" +
                            "<tr>" +
                            "<th style='border: 1px solid #02497f;'>#</th>" +
                            "<th style='border: 1px solid #02497f;'>Tashkilot INNsi</th>" +
                            "<th style='border: 1px solid #02497f;'>Mijoz oylik daromadi</th>" +
                            "<th style='border: 1px solid #02497f;'>Davr</th>" +
                            "<th style='border: 1px solid #02497f;'>Davr (oy)</th>" +
                            "<th style='border: 1px solid #02497f;'>Tashkilot nomi</th>" +
                            "</tr>";

                        var tot=0;
                        $.each(data, function (key, val) {
                            key++;
                            tot += val.INCOME_SUMMA;
                            var SUMM = (val.INCOME_SUMMA).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                            data_inps +="<tr>" +
                                "<td style='border: 1px solid #02497f;'>"+key+"</td>" +
                                "<td style='border: 1px solid #02497f;'>"+val.ORG_INN+"</td>" +
                                "<td style='border: 1px solid #02497f;'>"+SUMM+"</td>" +
                                "<td style='border: 1px solid #02497f;'>"+val.NUM+"</td>" +
                                "<td style='border: 1px solid #02497f;'>"+val.PERIOD+"</td>" +
                                "<td style='border: 1px solid #02497f;'>"+val.ORGNAME+"</td>" +
                                "</tr>"
                        });
                        data_inps += "</tbody></table></div>";
                        var total = (tot).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');

                        $('#resultINPSModal').modal('show');
                        $("#resultDataINPS").html(data_inps);
                        $("#resultDataINPSTotal").html("Total: "+total);
                    })
                });

                // BUTTON GET STATUS SEND TO ADMIN
                $('body').on('click', '#sendToAdmin', function () {
                    //console.log('ds');
                    var id = $('#sendToAdmin').data('id');

                    $.get('/uw/get-status-send/' + id, function (response) {
                        //console.log(response);
                        if(response.status === 1)
                        {
                            $('#btn-save-send').val("sendLoan");
                            $('#sendForm').trigger("reset");
                            $('#modalHeader').html("Ariza yuborish");
                            $('#modalBody').html("Arizani administratorga yuborish");
                            $('#modalFormSend').modal('show');
                        } else
                        {
                            $('#ResultMessageModal').removeClass('modal-success');
                            $('#ResultMessageModal').addClass('modal-warning');
                            $('#result_header').empty().append('Status Message');
                            $('#result_title').empty();
                            $('#result_text').empty().append(response.message);
                            $('#result_message').empty().append(response.message);
                            $('#ResultMessageModal').modal('show');
                        }
                    });

                });

                // BUTTON GET STATUS SEND TO ADMIN
                $('body').on('click', '#confirmSendToAdmin', function () {
                    //console.log('ds');
                    var id = $('#confirmSendToAdmin').data('id');

                    $.get('/uw/get-confirm-send/' + id, function (response) {
                        //console.log(response);
                        if(response.status === 1)
                        {
                            $('#btn-save-send').val("sendLoan");
                            $('#sendForm').trigger("reset");
                            $('#modalHeader').html("Ariza yuborish");
                            $('#modalBody').html("Arizani administratorga yuborish");
                            $('#modalFormSend').modal('show');
                        } else
                        {
                            $('#ResultMessageModal').addClass('modal-'+response.modal_style);
                            $('#result_header').empty().append(response.status);
                            $('#result_title').empty().append(response.message_sc);
                            $('#result_text').empty().append(response.message_sum);
                            $('#result_message').empty().append(response.message);
                            $('#ResultMessageModal').modal('show');
                        }
                    });

                });

                // request DEBTORS
                $('#debtors_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    paginate: false,
                    searching: false,
                    bInfo: false,
                    ajax: {
                        url: "{{ url('uw-debtors', ['id' => $model->id]) }}",
                        type: 'GET',
                    },
                    columns: [
                        { data: 'id', name: 'id', 'visible': true, "searchable": false},
                        { data: null,
                            render: function ( data, type, row ) {
                                return data.family_name +' '+ data.name;
                            }
                        },
                        { data: 'inn', name: 'inn' },
                        { data: 'live_address', name: 'live_address' },
                        { data: null,
                            render: function ( data, type, row ) {
                                return formatCurrency(data.total_sum);
                            }
                        },
                        { data: 'total_month', name: 'total_month' },
                        { data: null,name: 'total',
                            render: function ( data, type, row ) {
                                var payment = (data.total_sum/data.total_month*0.87)*0.5;
                                return formatCurrency(payment);
                            }
                        },
                        { data: 'action', name: 'action', orderable: false},
                    ],
                    footerCallback: function (row, data, start, end, display) {
                        console.log(data)
                        var totalAmount = 0;
                        for (var i = 0; i < data.length; i++) {
                            totalAmount += parseFloat(data[i]['total_sum']);
                        }
                        var totalPayment = 0;
                        for (var j = 0; j < data.length; j++) {
                            totalPayment += (parseFloat(data[j]['total_sum'])*0.87)/parseFloat(data[j]['total_month'])*0.5;
                        }
                        var api = this.api();
                        $(api.column(4).footer()).html(formatCurrency(totalAmount));
                        $(api.column(6).footer()).html(formatCurrency(totalPayment));
                    },
                    order: [[0, 'desc']]
                });

                $('#add-new-debtor').click(function () {
                    $('#btn-debtor-save').val("create-debtor");
                    $('#debtor_id').val('');
                    $('#debtorForm').trigger("reset");
                    $('#debtorModal').html("Add Debtor");
                    $('#ajax-debtor-modal').modal('show');
                });

                $('body').on('click', '.edit-debtor', function () {
                    var debtor_id = $(this).data('id');
                    $.get('/uw-debtors/'+debtor_id+'/edit', function (data) {
                        console.log(data);
                        $('#debtorModal').html("Edit Debtor");
                        $('#btn-debtor-save').val("edit-debtor");
                        $('#ajax-debtor-modal').modal('show');
                        $('#debtor_id').val(data.id);
                        $('#family_name').val(data.family_name);
                        $('#name').val(data.name);
                        $('#patronymic').val(data.patronymic);
                        $('#inn').val(data.inn);
                        $('#resident').val(data.resident);
                        $('#document_serial').val(data.document_serial);
                        $('#document_number').val(data.document_number);
                        $('#document_date').val(data.document_date);
                        $('#gender').val(data.gender);
                        $('#birth_date').val(data.birth_date);
                        $('#document_region').val(data.document_region);
                        $('#document_district').val(data.document_district);
                        $('#pin').val(data.pin);
                        $('#live_address').val(data.live_address);
                        $('#job_address').val(data.job_address);
                        $('#total_sum').val(data.total_sum);
                        $('#total_month').val(data.total_month);
                    })
                });

                $('body').on('click', '#delete-debtor', function (e) {

                    e.preventDefault();
                    var id = $(this).data("id");

                    $('#ConfirmDebtorModal').data('id', id).modal('show');
                });

                $('#yesDebtorDelete').click(function () {
                    var id = $('#ConfirmDebtorModal').data('id');
                    $.ajax(
                        {
                            type: "DELETE",
                            url: "{{ url('uw-debtors') }}/"+id,
                            beforeSend: function(){
                                $("#loading-gif").show();
                            },
                            success: function (data) {
                                //console.log(data);
                                $("#loading-gif").hide();
                                var oTable = $('#debtors_datatable').dataTable();
                                oTable.fnDraw(false);
                            },
                            error: function (data) {
                                console.log('Error:', data);
                            }
                        });

                    $('#ConfirmDebtorModal').modal('hide');
                });


                // request Guards
                $('#guar_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    paginate: false,
                    searching: false,
                    bInfo: false,
                    ajax: {
                        url: "{{ route('uw.get-client-guars', ['id' => $model->id]) }}",
                        type: 'GET',
                    },
                    columns: [
                        { data: 'id', name: 'id', 'visible': true, "searchable": false},
                        { data: 'guar_type', name: 'guar_type' },
                        { data: 'title', name: 'title' },
                        { data: 'guar_owner', name: 'guar_owner' },
                        { data: 'guar_sum', name: 'guar_sum' },
                        { data: 'address', name: 'guar_sum' },
                        { data: 'action', name: 'action', orderable: false},
                    ],
                    order: [[0, 'desc']]
                });

                $('#add-new-post').click(function () {
                    $('#btn-save').val("create-post");
                    $('#post_id').val('');
                    $('#postForm').trigger("reset");
                    $('#postCrudModal').html("Add Guard");
                    $('#ajax-crud-modal').modal('show');
                });

                $('body').on('click', '.edit-post', function () {
                    var post_id = $(this).data('id');
                    $.get('/uw/edit-client-guar/'+post_id, function (data) {
                        $('#postCrudModal').html("Edit Guard");
                        $('#btn-save').val("edit-post");
                        $('#ajax-crud-modal').modal('show');
                        $('#post_id').val(data.id);
                        $('#title').val(data.title);
                        $('#guar_type').val(data.guar_type);
                        $('#guar_owner').val(data.guar_owner);
                        $('#guar_sum').val(data.guar_sum);
                        $('#address').val(data.address);
                    })
                });

                $('body').on('click', '#delete-post', function (e) {

                    e.preventDefault();
                    var id = $(this).data("id");

                    $('#ConfirmModal').data('id', id).modal('show');
                });

                $('#yesDelete').click(function () {
                    var id = $('#ConfirmModal').data('id');
                    $.ajax(
                        {
                            type: "GET",
                            url: "{{ url('/uw/delete-client-guar') }}/"+id,
                            beforeSend: function(){
                                $("#loading-gif").show();
                            },
                            success: function (data) {
                                //console.log(data);
                                $("#loading-gif").hide();
                                if (data.code === 200){
                                    var oTable = $('#guar_datatable').dataTable();
                                    oTable.fnDraw(false);
                                }
                                else if(data.code === 201){
                                    $('#MessageModal').modal('show');
                                    $('#message').html(data.message);
                                }
                            },
                            error: function (data) {
                                console.log('Error:', data);
                            }
                        });

                    $('#ConfirmModal').modal('hide');
                });

                // request Files
                $('#file_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    paginate: false,
                    searching: false,
                    bInfo: false,
                    ajax: {
                        url: "{{ route('uw.get-client-files', ['id' => $model->id]) }}",
                        type: 'GET',
                    },
                    columns: [
                        { data: 'id', name: 'id', 'visible': true, "searchable": false},
                        { data: 'file_name', name: 'file_name' },
                        { data: 'file_size', name: 'file_size' },
                        { data: 'view', name: 'view', orderable: false},
                        { data: 'trash', name: 'trash', orderable: false},
                    ],
                    order: [[0, 'desc']]
                });

                $('#add-new-file').click(function () {
                    $('#btn-save').val("create-file");
                    $('#post_file_id').val('');
                    $('#postFileForm').trigger("reset");
                    $('#postCrudFileModal').html("Add File");
                    $('#ajax-crud-file-modal').modal('show');
                });

                $('#postFileForm').on('submit', function(event){
                    event.preventDefault();
                    $.ajax({
                        url:"{{ route('uw.create-client-file') }}",
                        method:"POST",
                        data:new FormData(this),
                        dataType:'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        beforeSend: function(){
                            $("#loading-gif").show();
                        },
                        success: function (data) {
                            //console.log(data);
                            $("#loading-gif").hide();
                            $('#ajax-crud-file-modal').modal('hide');
                            var oTable = $('#file_datatable').dataTable();
                            oTable.fnDraw(false);
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    })
                });

                $('body').on('click', '#delete-file', function (e) {

                    e.preventDefault();
                    var id = $(this).data("id");

                    $('#ConfirmFileModal').data('id', id).modal('show');
                });

                $('#yesFileDelete').click(function () {
                    var id = $('#ConfirmFileModal').data('id');
                    $.ajax(
                        {
                            type: "GET",
                            url: "{{ url('/uw/delete-client-file') }}/"+id,
                            beforeSend: function(){
                                $("#loading-gif").show();
                            },
                            success: function (data) {
                                //console.log(data);
                                $("#loading-gif").hide();
                                var oTable = $('#file_datatable').dataTable();
                                oTable.fnDraw(false);
                            },
                            error: function (data) {
                                console.log('Error:', data);
                            }
                        });

                    $('#ConfirmFileModal').modal('hide');
                });

            });

            // Debtor form
            if ($("#debtorForm").length > 0) {
                $("#debtorForm").validate({
                    submitHandler: function(form) {
                        var actionType = $('#btn-debtor-save').val();
                        $('#btn-debtor-save').html('Sending..');
                        $.ajax({
                            data: $('#debtorForm').serialize(),
                            url: "{{ url('uw-debtors') }}",
                            type: "POST",
                            dataType: 'json',
                            beforeSend: function(){
                                $("#loading-gif").show();
                            },
                            success: function (data) {
                                console.log(data);
                                $("#loading-gif").hide();
                                $('#debtorForm').trigger("reset");
                                $('#ajax-debtor-modal').modal('hide');
                                $('#btn-debtor-save').html('Save Changes');
                                var oTable = $('#debtors_datatable').dataTable();
                                oTable.fnDraw(false);
                            },
                            error: function (data) {
                                console.log('Error:', data);
                                $('#btn-debtor-save').html('Save Changes');
                            }
                        });
                    }
                })
            }

            // Guar form
            if ($("#postForm").length > 0) {
                $("#postForm").validate({
                    submitHandler: function(form) {
                        var actionType = $('#btn-save').val();
                        $('#btn-save').html('Sending..');

                        $.ajax({
                            data: $('#postForm').serialize(),
                            url: "{{ route('uw.create-client-guar') }}",
                            type: "POST",
                            dataType: 'json',
                            beforeSend: function(){
                                $("#loading-gif").show();
                            },
                            success: function (data) {
                                //console.log(data);
                                $("#loading-gif").hide();
                                $('#postForm').trigger("reset");
                                $('#ajax-crud-modal').modal('hide');
                                $('#btn-save').html('Save Changes');
                                var oTable = $('#guar_datatable').dataTable();
                                oTable.fnDraw(false);
                            },
                            error: function (data) {
                                console.log('Error:', data);
                                $('#btn-save').html('Save Changes');
                            }
                        });
                    }
                })
            }

            // File form
            if ($("#postFileForm").length > 0) {

                $("#postFileForm").validate({
                    submitHandler: function(form) {
                        var actionType = $('#btn-file-save').val();
                        $('#btn-file-save').html('Sending..');

                        $.ajax({
                            data: $('#postFileForm').serialize(),
                            url: "{{ route('uw.create-client-file') }}",
                            type: "POST",
                            dataType:'JSON',
                            contentType: false,
                            cache: false,
                            processData: false,
                            beforeSend: function(){
                                $("#loading-gif").show();
                            },
                            success: function (data) {
                                //console.log(data);
                                $("#loading-gif").hide();
                                $('#postFileForm').trigger("reset");
                                $('#ajax-crud-file-modal').modal('hide');
                                $('#btn-save').html('Save Changes');
                                var oTable = $('#file_datatable').dataTable();
                                oTable.fnDraw(false);
                            },
                            error: function (data) {
                                console.log('Error:', data);
                                $('#btn-save').html('Save Changes');
                            }
                        });
                    }
                })
            }

            if ($("#sendForm").length > 0) {

                $("#sendForm").validate({
                    submitHandler: function (form) {

                        var actionType = $('#btn-save-send').val();

                        $('#btn-save-send').html('Sending..');

                        $.ajax({
                            data: $('#sendForm').serialize(),
                            url: "{{ url('/uw/cs-app-send') }}",
                            type: "POST",
                            dataType: 'json',
                            beforeSend: function(){
                                $("#loading-gif").show();
                            },
                            success: function (data) {
                                $("#loading-gif").hide();
                                $('#sendForm').trigger("reset");
                                $('#modalFormSend').modal('hide');
                                $('#send_to_admin_buttons').html('<h3 class="text-primary"><i class="fa fa-clock-o"></i> Ariza Yuborildi</h3>');
                                $('#btn-save-send').html('Save Changes');

                            },
                            error: function (data) {
                                console.log('Error:', data);
                                $('#btn-save-send').html('Save Changes');
                            }
                        });
                    }
                })
            }


            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $("#document_region").change(function () {
                var region_code = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "/get-districts",
                    data: {_token: CSRF_TOKEN, region_code: region_code},
                    dataType: 'JSON',
                    success: function (res) {
                        var districtData = "";
                        var obj = res;
                        if (res) {
                            $("#document_district").empty();

                            if (obj['msg'] != 0) {

                                $('#document_district').show();

                                $("#document_district").append('<option value="" disabled selected>Tumanni tanlang</option>');

                                $.each(obj['msg'], function (key, val) {
                                    districtData += '<option value="' + val.code + '">' + val.name + '</option>';
                                });

                            } else {
                                $('#document_district').hide();
                            }

                            $("#document_district").append(districtData); //// For Append
                        }
                    },

                    error: function () {
                        console.log('error');
                    }
                });
            });

            function print(id)
            {
                var divToPrint=document.getElementById(id);
                var newWin=window.open('',id, 'height=700,width=800');
                newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML);
                newWin.document.write('<link rel="stylesheet" href="/admin-lte/dist/css/AdminLTE.min.css" type="text/css" />');
                newWin.document.write('<link rel="stylesheet" href="/admin-lte/bootstrap/css/bootstrap.css" type="text/css" />');
                newWin.document.write('</body></html>');
                newWin.document.close();
            }

            $(function () {
                $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                    checkboxClass: 'icheckbox_flat-green',
                    radioClass: 'iradio_flat-green'
                });
            });

        </script>
    </section>
    <!-- /.content -->
@endsection
