<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // count() //
        /*@include('oracle_connect.php');

        $rs = oci_parse($conn??'',
            "select count(*) from ln_card a
            join Dwh_Loan_Card d on a.loan_id = d.loan_id
            where 1=1
            ");

        oci_execute($rs);

        $row = oci_fetch_row($rs);

        $result["total"] = $row[0];

        $sql = "select a.loan_id,a.filial_code,a.client_code,a.client_name,a.claim_number,a.loan_number,a.committee_number,
        a.currency,a.contract_code,a.summ_loan,a.contract_date,d.ln_type_code,a.product_id,d.ln_type_name,d.ln_status_name 
        from ln_card a
        join dwh_Loan_Card d on a.loan_id = d.loan_id
        where 1=1 
        order by a.loan_id desc OFFSET 0 ROWS FETCH NEXT 20 ROWS ONLY ";

        $rs = oci_parse($conn, $sql);

        oci_execute($rs);

        $items = array();

        while (($row = oci_fetch_object($rs)) != false) {
            array_push($items, $row);
        }
        $result["rows"] = $items;
        //echo json_encode($result);
        oci_close($conn);*/


        $roles = Role::orderBy('role_code', 'ASC')->get();

        // count() //
        @include('count_message.php');

        return view('roles.index', compact('roles',
            'inbox_count','sent_count','term_inbox_count','all_inbox_count'));
    }

    public function ora()
    {
        // count() //
        @include('oracle_connect.php');

        $rs = oci_parse($conn??'',
            "SELECT count (*)
FROM (
  SELECT saldo.ID, account_code, saldo.saldo_equival_out, oper_day,
         rank() over (partition by account_code order by oper_day desc) AS rnk
  FROM saldo
  where saldo.code_filial = '00111'
  and oper_day between to_date('01.09.2020', 'dd.MM.yyyy') and to_date('24.09.2020', 'dd.MM.yyyy') 
  and substr(account_code, 8, 1) = 4
  and substr(account_code, 25, 3) between 701 and 950
  ) a, accounts ac, core_groups cg
WHERE account_code = ac.code and ac.code_filial = cg.filial_code and ac.group_code = cg.group_code
and a.rnk = 1
            ");

        oci_execute($rs);

        $row = oci_fetch_row($rs);

        $result["total"] = $row[0];

        $sql = "SELECT a.ID,ac.code_filial,cg.group_code,cg.name,cg.description,ac.acc_external,ac.name,a.saldo_equival_out, a.oper_day
FROM (
  SELECT saldo.ID, account_code, saldo.saldo_equival_out, oper_day,
         rank() over (partition by account_code order by oper_day desc) AS rnk
  FROM saldo
  where saldo.code_filial = '00111'
  and oper_day between to_date('01.09.2020', 'dd.MM.yyyy') and to_date('24.09.2020', 'dd.MM.yyyy') 
  and substr(account_code, 8, 1) = 4
  and substr(account_code, 25, 3) between 701 and 950
  ) a, accounts ac, core_groups cg
WHERE account_code = ac.code and ac.code_filial = cg.filial_code and ac.group_code = cg.group_code
and a.rnk = 1
        OFFSET 0 ROWS FETCH NEXT 20 ROWS ONLY ";

        $rs = oci_parse($conn, $sql);

        oci_execute($rs);

        $items = array();

        while (($row = oci_fetch_object($rs)) != false) {
            array_push($items, $row);
        }
        $result["rows"] = $items;
        //echo json_encode($result);
        oci_close($conn);

        //print_r($result); die;


        $roles = Role::orderBy('role_code', 'ASC')->get();

        // count() //
        @include('count_message.php');

        return view('roles.ora', compact('roles','result',
            'inbox_count','sent_count','term_inbox_count','all_inbox_count'));
    }

    public function store(Request $request)
    {
        $row_id = $request->model_id;

        $model = Role::updateOrCreate(['id' => $row_id],
            [
                'title' => $request->title,
                'title_ru' => $request->title_ru,
                'role_code' => $request->role_code
            ]);

        return response()->json($model);
    }

    public function edit($id)
    {
        //
        $user = Role::find($id);

        return response()->json($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);

        return response()->json($role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $role = Role::find($id);
        $check = User::where('roles', 'LIKE', '%'.$role->role_code.'%')->first();
        if ($check) {
            $message = 'Bu rolda foydalanuvchi mavjud';
            $code = 0;
        } else {
            Role::find($id)->delete();
            $message = 'Role o`chirildi';
            $code = 1;
        }

        return response()->json([
            'success' => 'Role',
            'code' => $code,
            'message' => $message
        ]);
    }
}
