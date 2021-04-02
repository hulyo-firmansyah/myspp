<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Helpers\Main;
use App\StudentModel;
use App\SppModel;
use App\TransactionCartModel;
use App\PaymentModel;
use App\AdminModel;

class TransactionController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'checkrole:admin,worker']);
    }

    
    public function index()
    {   
        $userData = Main::getCurrectUserDetails();
        return view('transaction.index', compact('userData'));
    }

    public function transactionProcess(Request $request)
    {   
        $rTCode = Crypt::decrypt($request->t_code);
        $rSId = Crypt::decrypt($request->s_id);
        $rSId = preg_replace('/[^0-9]/', '', $rSId) === "" ? null : intval(preg_replace('/[^0-9]/', '', $rSId));
        $sTCode = Crypt::decrypt($request->session()->get('t_data')['t_code']);
        $sSId = Crypt::decrypt($request->session()->get('t_data')['s_id']);
        $sSId = preg_replace('/[^0-9]/', '', $sSId) === "" ? null : intval(preg_replace('/[^0-9]/', '', $sSId));
        $officerId = AdminModel::where('data_of', Auth::user()->id_user)->first()->id_petugas;

        // dd($rTCode, $sTCode, $rSId, $sSId);
        if($rTCode === $sTCode && $rSId === $sSId){
            $transactionCart = TransactionCartModel::where('id_siswa', $sSId);
            foreach(Main::genArray($transactionCart->get()) as $t_crt_i => $t_crt){
                $payment = new PaymentModel;
                $payment->kode_pembayaran = $rTCode.$t_crt_i;
                $payment->id_petugas = $officerId;
                $payment->id_siswa = $sSId;
                $payment->tgl_bayar = Carbon::now()->format('Y-m-d H:i:s');
                $payment->bulan_dibayar = $t_crt->bulan_dibayar;
                $payment->tahun_dibayar = Carbon::now()->format('Y');
                $payment->id_spp = $t_crt->id_spp;
                $payment->jumlah_bayar = $t_crt->jumlah_bayar;
                $payment->save();
            }
            $studentName = $transactionCart->first()->student->nama;
            $transactionCart->delete();
            return redirect()->route('transaction.index')->with('success', ['msg' => 'Pembayaran Berhasil!', 'student_name' => $studentName, 'date' => Carbon::now()->format('d-m-Y')]);
        }
        return redirect()->route('transaction.index')->with('error', 'Pembayaran gagal!');
    }


    private function generateTransactionCode()
    {
        $code = dechex(Carbon::now()->timestamp);
        return $code;
    }

    private function getTransactionCart($studentId)
    {
        $transactionData = TransactionCartModel::where('id_siswa', $studentId)->orderBy('created_at', 'ASC')->get();

        $transactionCart = collect([]);
        foreach(Main::genArray($transactionData) as $trd){
            $transactionCart->push([
                'trs_id' => Crypt::encrypt($trd->id_keranjang),
                'trs_officer_name' => $trd->officer->nama_petugas,
                'trs_spp_year' => $trd->spp->tahun,
                'trs_spp_periode' => $trd->spp->periode,
                'trs_spp_steps' => Main::classStepsFilter($trd->spp->step->tingkatan),
                'trs_nominal' => $trd->jumlah_bayar,
                'trs_nominal_formatted' => Main::rupiahCurrency($trd->jumlah_bayar),
                'trs_month' => Main::getMonth($trd->bulan_dibayar),
            ]);
        }

        return $transactionCart;
    }


    public function api_searchStudent(Request $request)
    {
        if(!$request->ajax()) abort(404);
        $query = $request->q;

        $studentResult = Main::getStudent(['query' => $query]);
        return Main::generateAPI($studentResult);
    }

    public function api_getTransaction(Request $request)
    {
        if(!$request->ajax()) abort(404);

        $studentId = Crypt::decrypt($request->id);
        $studentId = preg_replace('/[^0-9]/', '', $studentId) === "" ? null : intval(preg_replace('/[^0-9]/', '', $studentId));
        // $studentId = 14;//Vera
        $studentData = StudentModel::findOrFail($studentId);
        $paymentType = SppModel::get();
        $paymentTemp = collect([]);
        foreach(Main::genArray($paymentType) as $payment){
            // $getPayment = PaymentModel::where('id_spp', $payment->id_spp)
            //     ->where('id_petugas', AdminModel::where('data_of', Auth::user()->id_user)->first()->id_petugas)
            //     ->where('id_siswa', $studentId);
            
            $paymentTemp->push([
                'id' => Crypt::encrypt($payment->id_spp),
                'year' => $payment->tahun,
                'nominal_per_periode' => $payment->nominal / 12,
                'nominal_per_periode_formatted' => Main::rupiahCurrency($payment->nominal / 12),
                'nominal' => $payment->nominal,
                'nominal_formatted' => Main::rupiahCurrency($payment->nominal),
                'periode' => 12,
                'steps' => Main::classStepsFilter($payment->step->tingkatan),
                'payment_info_per_periode'
            ]);
            //SPP XII RPL 1 | 2020
        }
        $data = collect([
            'payment_code' => $this->generateTransactionCode(),
            'payment_date' => Carbon::now()->format('Y-m-d'),
            'student_nisn' => $studentData->nisn,
            'student_name' => $studentData->nama,
            'student_class' => Main::classStepsFilter($studentData->classes->step->tingkatan)." ".$studentData->classes->competence->kompetensi_keahlian,
            'payment_type' => $paymentTemp,
            'transaction_cart' => $this->getTransactionCart($studentId),
            'enc' => [
                't_code' => Crypt::encrypt($this->generateTransactionCode()),
                's_id' => Crypt::encrypt($studentData->id_siswa)   
            ],
        ]);
        
        $request->session()->put('t_data', [
            't_code' => $data['enc']['t_code'],
            's_id' => $data['enc']['s_id']
        ]);

        return Main::generateAPI($data);
    }

    public function api_addToCartTransaction(Request $request)
    {
        if(!$request->ajax()) abort(404);

        $this->validate($request, [
            'spp_id' => 'required',
            't_code' => 'required',
            's_id' => 'required',
            'nominal' => 'required|numeric',
            'month' => 'required|numeric|min:1|max:12'
        ]);

        $s_id = Crypt::decrypt($request->s_id);
        $s_id = preg_replace('/[^0-9]/', '', $s_id) === "" ? null : intval(preg_replace('/[^0-9]/', '', $s_id));
        $spp_id = Crypt::decrypt($request->spp_id);
        $spp_id = preg_replace('/[^0-9]/', '', $spp_id) === "" ? null : intval(preg_replace('/[^0-9]/', '', $spp_id));
        
        $transactionCheck = TransactionCartModel::where('id_spp', $spp_id)->where('id_siswa', $s_id)->where('bulan_dibayar', $request->month)->first();
        if($transactionCheck) return Main::generateAPI([]);

        $officerId = AdminModel::where('data_of', Auth::user()->id_user)->first()->id_petugas;

        $transactionCart = new TransactionCartModel();
        $transactionCart->id_spp = $spp_id;
        $transactionCart->id_siswa = $s_id;
        $transactionCart->id_petugas = $officerId;
        $transactionCart->jumlah_bayar = $request->nominal;
        $transactionCart->bulan_dibayar = $request->month;
        $transactionCart->save();

        return Main::generateAPI($this->getTransactionCart($s_id));
    }

    public function api_removeFromCartTransaction(Request $request)
    {
        if(!$request->ajax()) abort(404);

        $t_id = Crypt::decrypt($request->id);
        $t_id = preg_replace('/[^0-9]/', '', $t_id) === "" ? null : intval(preg_replace('/[^0-9]/', '', $t_id));
        $transactionCart = TransactionCartModel::findOrFail($t_id);
        $s_id = $transactionCart->id_siswa;
        $transactionCart->delete();

        return Main::generateAPI($transactionCart ? $this->getTransactionCart($s_id) : []);
    }

}
