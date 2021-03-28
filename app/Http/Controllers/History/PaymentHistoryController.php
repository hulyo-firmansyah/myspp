<?php

namespace App\Http\Controllers\History;

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

class PaymentHistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'checkrole:admin,worker']);
    }

    public function index()
    {
        $userData = Main::getCurrectUserDetails();
        return view('history.index', compact('userData'));
    }


    private function getHistories(array $options = array())
    {
        $defaults = [
            'id' => null,
            'trash' => false,
        ];
        $options = array_merge($defaults, $options);
        extract($options);

        $history = PaymentModel::with([
            'worker', 
            'worker.users',
            'student', 
            'student.users',
            'student.classes', 
            'spp'
        ]);

        if($trash) $history = $history->onlyTrashed();
        $history = $history->get();

        $data = collect([]);
        foreach(Main::genArray($history) as $rd){
            $worker = collect([
               'id'  => Crypt::encrypt($rd->worker->id_petugas),
               'name' => $rd->worker->nama_petugas,

               'username' => $rd->worker->users->username,
               'email' => $rd->worker->users->email,
               'role' => $rd->worker->users->role,
            ]);
            $student = collect([
                'id'  => Crypt::encrypt($rd->student->id_siswa),
                'nisn' => $rd->student->nisn,
                'nis' => $rd->student->nis,
                'name' => $rd->student->nama,
                'phone' => $rd->student->no_telp,
                'address' => $rd->student->alamat,

                'class_competence' => $rd->student->classes->kompetensi_keahlian,
                'class_name' => $rd->student->classes->nama_kelas,
                'class' => Main::classStepsFilter($rd->student->classes->tingkatan)." ".$rd->student->classes->kompetensi_keahlian,

                'username' => $rd->student->users->username,
                'email' => $rd->student->users->email,
                'role' => $rd->student->users->role,
            ]);
            $spp = collect([
                'id' => Crypt::encrypt($rd->spp->id_spp),
                'year' => $rd->spp->tahun,
                'nominal' => $rd->spp->nominal,
                'periode' => $rd->spp->periode,
                'step' => Main::classStepsFilter($rd->spp->tingkat),
            ]);
            $data->push([
                'id' => Crypt::encrypt($rd->id_pembayaran),
                'transaction_code'  => $rd->kode_pembayaran,
                'payment_nominal' => $rd->jumlah_bayar,
                'payment_nominal_formatted' => Main::rupiahCurrency($rd->jumlah_bayar),

                'worker' => $worker->toArray(),
                'student' => $student->toArray(),
                'spp' => $spp->toArray(),

                'created_at' => Carbon::parse($rd->created_at)->format('d-m-Y'),
                'updated_at' => Carbon::parse($rd->updated_at)->format('d-m-Y'),
                'deleted_at' => $rd->deleted_at ? Carbon::parse($rd->deleted_at)->format('d-m-Y') : null,
            ]);
        }

        return $data;
    }


    public function api_getHistory(Request $request)
    {
        // if(!$request->ajax()) abort(404);

        $history = $this->getHistories();
        return Main::generateAPI($history);
    }
}
