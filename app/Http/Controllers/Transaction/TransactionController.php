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
        $pageData = new \stdClass();
        $pageData->title = Main::createTitle('Transaksi');
        $userData = Main::getCurrectUserDetails();
        return view('transaction.index', compact('userData', 'pageData'));
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

        if($rTCode === $sTCode && $rSId === $sSId){
            $transactionCart = TransactionCartModel::where('id_siswa', $sSId);
            // dd($rTCode, $sTCode, $rSId, $sSId, $officerId, $transactionCart->get());
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

            $transactionClone = clone $transactionCart;
            $studentName = $transactionClone->first()->student->nama;
            $transactionCart->delete();
            $request->session()->forget('t_data');
            return redirect()->route('transaction.index')->with('success', ['msg' => 'Pembayaran Berhasil!', 'student_name' => $studentName, 'date' => Carbon::now()->format('d-m-Y')]);
        }
        return redirect()->route('transaction.index')->with('error', 'Pembayaran gagal!');
    }


    private function generateTransactionCode()
    {
        // $code = dechex(Carbon::now()->timestamp);
        // return $code;
        $length = 8;
        $characters = '0123456789abcdefghijklmnopqrs092u3tuvwxyzaskdhfhf9882323ABCDEFGHIJKLMNksadf9044OPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
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
        $studentData = StudentModel::findOrFail($studentId);
        $studentClone = clone $studentData;
        $studentSteps = $studentClone->classes->step->id_tingkatan;
        $paymentType = SppModel::where('id_tingkatan' ,'<=', $studentSteps)->get();
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
                'steps' => Main::classStepsFilter($payment->step->tingkatan)
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

    public function api_getPaymentTypeDetails(Request $request, $id)
    {
        if(!$request->ajax()) abort(404);

        $id = Crypt::decrypt($id);
        $id = preg_replace('/[^0-9]/', '', $id) === "" ? null : intval(preg_replace('/[^0-9]/', '', $id));
        $s_id = Crypt::decrypt($request->session()->get('t_data')['s_id']);
        $s_id = preg_replace('/[^0-9]/', '', $s_id) === "" ? null : intval(preg_replace('/[^0-9]/', '', $s_id));
        $sppData = SppModel::with('payments')->where('id_spp', $id)->first();

        $data = array_flip(["1","2","3","4","5","6","7","8","9","10","11","12"]);

        $paymentPerMonth = $sppData->nominal / 12;
        foreach(Main::genArray($sppData->payments) as $payment){
            if($payment->id_siswa === $s_id)
                $data[$payment->bulan_dibayar] = [
                    'payment_month' => intval($payment->bulan_dibayar),
                    'payment_month_formatted' => Main::getMonth($payment->bulan_dibayar),
                    'payment_total' => $sppData->nominal,
                    'payment_total_formatted' => Main::rupiahCurrency($sppData->nominal),
                    'payment_per_month' => $paymentPerMonth,
                    'payment_per_month_formatted' => Main::rupiahCurrency($paymentPerMonth),
                    'payment_per_month_minus' => ($paymentPerMonth - $payment->jumlah_bayar),
                    'payment_per_month_minus_formatted' => Main::rupiahCurrency(($paymentPerMonth - $payment->jumlah_bayar)),
                ];
        }
        
        foreach(Main::genArray($data) as $index => $dt){
            if(!is_array($dt)){
                $data[$index] = [
                    'payment_month' => intval($index),
                    'payment_month_formatted' => Main::getMonth($index),
                    'payment_total' => $sppData->nominal,
                    'payment_total_formatted' => Main::rupiahCurrency($sppData->nominal),
                    'payment_per_month' => $paymentPerMonth,
                    'payment_per_month_formatted' => Main::rupiahCurrency($paymentPerMonth),
                    'payment_per_month_minus' => $paymentPerMonth,
                    'payment_per_month_minus_formatted' => Main::rupiahCurrency($paymentPerMonth)
                ];
            }
        }

        $sppDataWithPayment = [
            'id' => Crypt::encrypt($sppData->id_spp),
            'year' => $sppData->tahun,
            'nominal_per_periode' => $sppData->nominal / 12,
            'nominal_per_periode_formatted' => Main::rupiahCurrency($sppData->nominal / 12),
            'nominal' => $sppData->nominal,
            'nominal_formatted' => Main::rupiahCurrency($sppData->nominal),
            'periode' => 12,
            'steps' => Main::classStepsFilter($sppData->step->tingkatan),
            'payment_data' => $data
        ];
        
        return Main::generateAPI($sppDataWithPayment);
    }

    public function api_addToCartTransaction(Request $request)
    {
        if(!$request->ajax()) abort(404);

        $this->validate($request, [
            'spp_id' => 'required',
            't_code' => 'required',
            's_id' => 'required',
            'nominal' => 'required|numeric|min:1',
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
